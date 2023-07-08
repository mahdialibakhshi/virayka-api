<?php

use App\Models\Cart;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAttrVariation;
use App\Models\ProductOption;
use App\Models\ProductVariation;
use App\Models\Province;
use App\Models\Wallet;
use Carbon\Carbon;

function generateFileName($name)
{
    $year = Carbon::now()->year;
    $month = Carbon::now()->month;
    $day = Carbon::now()->day;
    $hour = Carbon::now()->hour;
    $minute = Carbon::now()->minute;
    $second = Carbon::now()->second;
    $microsecond = Carbon::now()->microsecond;
    return $year . '_' . $month . '_' . $day . '_' . $hour . '_' . $minute . '_' . $second . '_' . $microsecond . '_' . $name;
}

function convertShamsiToGregorianDate($date)
{
    if ($date == null) {
        return null;
    }
    $pattern = "/[-\s]/";
    $shamsiDateSplit = preg_split($pattern, $date);

    $arrayGergorianDate = verta()->getGregorian($shamsiDateSplit[0], $shamsiDateSplit[1], $shamsiDateSplit[2]);

    return implode("-", $arrayGergorianDate) . " " . $shamsiDateSplit[3];
}

function cartTotalSaleAmount()
{
    $cartTotalSaleAmount = 0;
    foreach (\Cart::getContent() as $item) {
        if (sizeof($item->attributes) > 0) {
            $variation = ProductVariation::where('id', $item->attributes[0])->first();
            if (($variation->percentSalePrice > 0 && Carbon::now() > $variation->date_on_sale_from && Carbon::now() < $variation->date_on_sale_to) or ($variation->percentSalePrice > 0 && $variation->has_discount == 1)) {
                $cartTotalSaleAmount += $item->quantity * ($variation->price - $variation->sale_price);
            }

        } else {
            if ($item->associatedModel->percentSalePrice > 0 && Carbon::now() > $item->associatedModel->DateOnSaleFrom && Carbon::now() < $item->associatedModel->DateOnSaleTo) {
                $cartTotalSaleAmount += $item->quantity * ($item->associatedModel->price - $item->associatedModel->salePrice);
            }
        }
    }

    return $cartTotalSaleAmount;
}

function cartTotalAmount()
{
    $delivery_price = 0;
    if (auth()->check()) {
        if (session()->has('delivery_price')) {
            $delivery_price = session()->get('delivery_price');
        }
    }
    $delivery_price = intval($delivery_price);
    if (session()->has('coupon')) {
        if (session()->get('coupon.amount') > (\Cart::getTotal() + $delivery_price)) {
            return 0;
        } else {
            return (\Cart::getTotal() + $delivery_price - session()->get('coupon.amount'));
        }
    } else {
        return \Cart::getTotal() + $delivery_price;
    }


}

function checkCoupon($code)
{
    $coupon = Coupon::where('code', $code)->first();
    if ($coupon->user_id == null) {
        $coupon = Coupon::where('code', $code)
            ->where('expired_at', '>', Carbon::now())
            ->where('times', '>', 0)
            ->first();
    } else {
        if (!auth()->check()) {
            session()->forget('coupon');
            return ['error' => 'برای استفاده از کد تخفیف لازم است وارد شوید'];
        }
        $userId = auth()->id();
        $coupon = Coupon::where('code', $code)
            ->where('expired_at', '>', Carbon::now())
            ->where('user_id', $userId)
            ->where('times', '>', 0)
            ->first();
    }
    if ($coupon == null) {
        session()->forget('coupon');
        return ['error' => 'کد تخفیف وارد شده وجود ندارد'];
    }

//    if (Order::where('user_id', auth()->id())->where('coupon_id', $coupon->id)->where('payment_status', 1)->exists()) {
//        session()->forget('coupon');
//        return ['error' => 'شما قبلا از این کد تخفیف استفاده کرده اید'];
//    }


    if ($coupon->getRawOriginal('type') == 'amount') {
        session()->put('coupon', ['id' => $coupon->id, 'code' => $coupon->code, 'amount' => $coupon->amount]);
    } else {
        $total = calculateCartPrice()['sale_price'];

        $amount = (($total * $coupon->percentage) / 100) > $coupon->max_percentage_amount ? $coupon->max_percentage_amount : (($total * $coupon->percentage) / 100);

        session()->put('coupon', ['id' => $coupon->id, 'code' => $coupon->code, 'amount' => $amount]);
    }
    return ['success' => 'کد تخفیف برای شما ثبت شد'];
}

function province_name($provinceId)
{
    return Province::findOrFail($provinceId)->name;
}

function city_name($cityId)
{
    return City::findOrFail($cityId)->name;
}

function dayOfWeek($day)
{
    switch ($day) {
        case '0';
            $dayName = 'شنبه';
            break;
        case '1';
            $dayName = 'یکشنبه';
            break;
        case '2';
            $dayName = 'دوشنبه';
            break;
        case '3';
            $dayName = 'سه شنبه';
            break;
        case '4';
            $dayName = 'چهارشنبه';
            break;
        case '5';
            $dayName = 'پنجشنبه';
            break;
        case '6';
            $dayName = 'جمعه';
            break;
    }
    return $dayName;

}

function convert($string)
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $num = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $string);

    return $convertedPersianNums;
}

function imageExist($env, $image)
{
    $path = public_path($env . $image);
    if (file_exists($path) and !is_dir($path)) {
        $src = url($env . $image);
    } else {
        $src = url('no_image.png');
    }
    return $src;
}

function unlink_image_helper_function($path)
{
    if (file_exists($path) and !is_dir($path)) {
        unlink($path);
    }
}

function product_price($product_id, $product_attr_variation_id = null)
{
    $user = auth()->user();
    if ($product_attr_variation_id != null) {
        $product_attr_variation = ProductAttrVariation::where('id', $product_attr_variation_id)->first();
        //محاسبه قیمت برای کاربران عادی
        if ($user == null or $user->getRawOriginal('role') == 4) {
            $price = $product_attr_variation->price_user_normal;
            $percent_sale = $product_attr_variation->percent_sale_user_normal;
            $sale_price = calculateDiscount($price, $percent_sale);
            return [$price, $percent_sale, $sale_price];
        }
        //محاسبه قیمت برای کاربران اینستاگرام
        if ($user->getRawOriginal('role') == 5) {
            $price = $product_attr_variation->price_user_instagram;
            $percent_sale = $product_attr_variation->percent_sale_user_instagram;
            $sale_price = calculateDiscount($price, $percent_sale);
            return [$price, $percent_sale, $sale_price];
        }
        //محاسبه قیمت برای همکاران
        if ($user->getRawOriginal('role') == 6 or $user->getRawOriginal('role') == 1 or $user->getRawOriginal('role') == 2 or $user->getRawOriginal('role') == 3) {
            $price = $product_attr_variation->price_user_coworker;
            $percent_sale = $product_attr_variation->percent_sale_user_coworker;
            $sale_price = calculateDiscount($price, $percent_sale);
            return [$price, $percent_sale, $sale_price];
        }
    }
    $product = Product::where('id', $product_id)->first();
    if (($product->DateOnSaleTo > Carbon::now() && $product->DateOnSaleFrom < Carbon::now()) or ($product->has_discount == 1)) {
        //محاسبه قیمت برای کاربران عادی
        if ($user == null or $user->getRawOriginal('role') == 4) {
            $price = $product->price_user_normal;
            $percent_sale = $product->percent_sale_user_normal;
            $sale_price = calculateDiscount($price, $percent_sale);
            return [$price, $percent_sale, $sale_price];
        }
        //محاسبه قیمت برای کاربران اینستاگرام
        if ($user->getRawOriginal('role') == 5) {
            $price = $product->price_user_instagram;
            $percent_sale = $product->percent_sale_user_instagram;
            $sale_price = calculateDiscount($price, $percent_sale);
            return [$price, $percent_sale, $sale_price];
        }
        //محاسبه قیمت برای همکاران
        if ($user->getRawOriginal('role') == 6 or $user->getRawOriginal('role') == 1 or $user->getRawOriginal('role') == 2 or $user->getRawOriginal('role') == 3) {
            $price = $product->price_user_coworker;
            $percent_sale = $product->percent_sale_user_coworker;
            $sale_price = calculateDiscount($price, $percent_sale);
            return [$price, $percent_sale, $sale_price];
        }
    } else {
        //محاسبه قیمت برای کاربران عادی
        if ($user == null or $user->getRawOriginal('role') == 4) {
            $price = $product->price_user_normal;
            $percent_sale = 0;
            $sale_price = calculateDiscount($price, $percent_sale);
            return [$price, $percent_sale, $sale_price];
        }
        //محاسبه قیمت برای کاربران اینستاگرام
        if ($user->getRawOriginal('role') == 5) {
            $price = $product->price_user_instagram;
            $percent_sale = 0;
            $sale_price = calculateDiscount($price, $percent_sale);
            return [$price, $percent_sale, $sale_price];
        }
        //محاسبه قیمت برای همکاران
        if ($user->getRawOriginal('role') == 6 or $user->getRawOriginal('role') == 1 or $user->getRawOriginal('role') == 2 or $user->getRawOriginal('role') == 3) {
            $price = $product->price_user_coworker;
            $percent_sale = 0;
            $sale_price = calculateDiscount($price, $percent_sale);
            return [$price, $percent_sale, $sale_price];
        }
    }

}

function product_price_for_user_normal($product_id, $product_attr_variation_id = null)
{
    if ($product_attr_variation_id != null) {
        $product_attr_variation = ProductAttrVariation::where('id', $product_attr_variation_id)->first();
        //محاسبه قیمت برای کاربران عادی
        $price = $product_attr_variation->price;
        $percent_sale_price = $product_attr_variation->percent_sale_price;
        $sale_price = calculateDiscount($price, $percent_sale_price);
        $sale_price=intval($sale_price);

        $sale_price=round($sale_price,-1);
        $sale_price=round($sale_price,-3);
        return [$price, $percent_sale_price, $sale_price];
    }

    $product = Product::where('id', $product_id)->first();
    if (($product->DateOnSaleTo > Carbon::now() && $product->DateOnSaleFrom < Carbon::now()) or ($product->has_discount == 1)) {
        //محاسبه قیمت برای کاربران عادی
        $price = $product->price;
        $percent_sale = $product->percent_sale_price;
        $sale_price = calculateDiscount($price, $percent_sale);

        $sale_price=intval($sale_price);
        $sale_price=round($sale_price,-1);
        $sale_price=round($sale_price,-3);

        return [$price, $percent_sale, $sale_price];
    } else {
        //محاسبه قیمت برای کاربران عادی
        $price = $product->price;
        $percent_sale = 0;
        $sale_price = calculateDiscount($price, $percent_sale);
        $sale_price=intval($sale_price);
        $sale_price=round($sale_price,-1);
        $sale_price=round($sale_price,-3);
        return [$price, $percent_sale, $sale_price];
    }

}

function calculateDiscount($price, $percent_sale)
{
        if (is_numeric($percent_sale)){
            $percent_sale=$percent_sale;
        }else{
            $percent_sale=0;
        }
    return $price - ($price * $percent_sale / 100);
}

function calculateCartProductPrice($product_price, $product_options)
{
    if ($product_options != null) {
        foreach ($product_options as $product_option) {
            $price_option = ProductOption::where('id', $product_option)->first()->price;
            $product_price += $price_option;
        }
    }

    return $product_price;
}

function calculateCartPrice()
{
    $user_id = auth()->id();
    $carts = Cart::where('user_id', $user_id)->get();
    $original_price = 0;
    $sale_price = 0;
    foreach ($carts as $cart) {
        $product_attr_variation = ProductAttrVariation::where('product_id', $cart->product_id)
            ->where('attr_value', $cart->variation_id)
            ->where('color_attr_value', $cart->color_id)
            ->first();
        if ($product_attr_variation != null) {
            $product_attr_variation_id = $product_attr_variation->id;
            $cart['product_attr_variation_id'] = $product_attr_variation_id;
        }
        $option_ids = json_decode($cart->option_ids);
        $cart['option_ids'] = $option_ids;
    }
    foreach ($carts as $cart) {
        $original_price += calculateCartProductPrice(product_price_for_user_normal($cart->product_id,$cart->product_attr_variation_id)[0],$cart->option_ids)*$cart->quantity;
        $sale_price += calculateCartProductPrice(product_price_for_user_normal($cart->product_id,$cart->product_attr_variation_id)[2],$cart->option_ids)*$cart->quantity;
    }
    $total_sale=$original_price - $sale_price;
    return [
        'original_price'=>$original_price,
        'sale_price'=>$sale_price,
        'total_sale'=>$total_sale,
    ];
}

function summery_cart(){
    //get user wallet
    $wallet_amount=0;
    $use_wallet=0;
    //calculate cart-summery options
    $original_price=calculateCartPrice()['original_price'];
    $total_sale=calculateCartPrice()['total_sale'];
    $sale_price=calculateCartPrice()['sale_price'];
    $coupon_amount=session()->get('coupon.amount');
    $delivery_price=session()->get('delivery_price');
    $total_amount=intval($sale_price)+intval($delivery_price)-intval($coupon_amount);
    $payment=$total_amount;
    $left_over_wallet=$wallet_amount;
    //if use wallet
    if (session()->exists('use_wallet')){
        $use_wallet=session()->get('use_wallet');
    }
    if ($use_wallet!=0){
        $wallet=Wallet::where('user_id',auth()->id())->first();
        if ($wallet!=null){
            $wallet_amount=$wallet->amount;
        }
        if ($total_amount>$wallet_amount or $total_amount==$wallet_amount){
            $payment=$total_amount-$wallet_amount;
            $left_over_wallet=0;
        }else{
            $payment=0;
            $left_over_wallet=$wallet_amount-$total_amount;
        }
        $wallet_amount=$wallet_amount-$left_over_wallet;
    }else{
        $wallet=Wallet::where('user_id',auth()->id())->first();
        $wallet==null?$left_over_wallet=0:$left_over_wallet=$wallet->amount;
    }
    return
        [
            'original_price'=>$original_price,
            'total_sale'=>$total_sale,
            'sale_price'=>$sale_price,
            'coupon_amount'=>$coupon_amount,
            'total_amount'=>$total_amount,
            'payment'=>$payment,
            'left_over_wallet'=>$left_over_wallet,
            'delivery_price'=>$delivery_price,
            'wallet_amount'=>$wallet_amount,
        ];
}

function discount_timer_creator($DateOnSaleTo){
    $year=Carbon::createFromFormat('Y-m-d H:i:s', $DateOnSaleTo)->year;
    $month=Carbon::createFromFormat('Y-m-d H:i:s', $DateOnSaleTo)->month;
    $day=Carbon::createFromFormat('Y-m-d H:i:s', $DateOnSaleTo)->day;
    $hour=Carbon::createFromFormat('Y-m-d H:i:s', $DateOnSaleTo)->hour;
    $minute=Carbon::createFromFormat('Y-m-d H:i:s', $DateOnSaleTo)->minute;
    $second=Carbon::createFromFormat('Y-m-d H:i:s', $DateOnSaleTo)->second;
    $data_until=$year.', '.$month.', '.$day;
    $data_labels_short=$day.':'.$hour.':'.$minute.':'.$second;
    return [
        'data_until'=>$data_until,
        'data_labels_short'=>$data_labels_short,
    ];
}



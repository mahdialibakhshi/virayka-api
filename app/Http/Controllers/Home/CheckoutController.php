<?php

namespace App\Http\Controllers\Home;

use App\Alopeyk\Alopeyk;
use App\Http\Controllers\Controller;
use App\Models\AlopeykConfig;
use App\Models\Cart;
use App\Models\DeliveryConfig;
use App\Models\DeliveryMethod;
use App\Models\DeliveryMethodAmount;
use App\Models\LimitConfig;
use App\Models\Order;
use App\Models\PaymentMethods;
use App\Models\ProductAttrVariation;
use App\Models\Province;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function checkout()
    {
        if (auth()->check()) {
            $user = \auth()->user();
            $carts = Cart::where('user_id', $user->id)->get();
            if (count($carts) == 0) {
                alert()->error('سبد خرید شما خالی است', 'متاسفیم');
                return redirect()->back();
            }
        } else {
            alert()->warning('سبد خرید شما خالی است', 'دقت کنید');
            return redirect()->back();
        }
        session()->forget('alopeyk_price');
        session()->forget('use_wallet');
        $delivery_config = DeliveryConfig::first();
        $order_send_after = $delivery_config->order_send_after;
        $sent_times = $delivery_config->sent_times;
        $days_count = $delivery_config->days_count;
        $holidays = $delivery_config->holidays;
        $date = [];
        $holidaysName = [];
        if ($holidays) {
            $holidays = explode(',', $holidays);
            foreach ($holidays as $holiday) {
                array_push($holidaysName, dayOfWeek($holiday));
            }
        }
        if ($sent_times) {
            $sent_times = explode(',', $sent_times);
        } else {
            $sent_times = [];
        }
        for ($i = 0 + $order_send_after; $i < $days_count + $order_send_after; $i++) {
            $order_send_start = Carbon::now()->addDay($i);
            array_push($date, $order_send_start);
        }

        $cart = Cart::where('user_id', auth()->id())->get();
        if ($cart == null) {
            alert()->warning('سبد خرید شما خالی میباشد', 'دقت کنید');
            return redirect()->route('home.index');
        }

        $addresses = UserAddress::where('user_id', auth()->id())->latest()->get();
        $PaymentMethods = PaymentMethods::where('is_active', 1)->get();
        $deliveryMethod = DeliveryMethod::where('is_active', 1)->get();
        $provinces = Province::all();

        //calculate delivery price
        if (count($addresses) > 0) {
            $address_id = $addresses[0]->id;
            $province_id = $addresses[0]->province_id;
            foreach ($deliveryMethod as $item) {
                $this->calculateDeliveryPrice($province_id, $addresses[0], $item);
            }
            //check alopeyk
            $alopeyk = $this->exist_aloPeyk($addresses[0]);
        } else {
            $address_id = null;
            $province_id = null;
            $alopeyk = false;
        }
        //set time for methods
        $set_time_for = $delivery_config->methods_id;
        $set_time_for = explode(',', $set_time_for);
        $selected_time = false;
        foreach ($set_time_for as $item) {
            if ($item == $deliveryMethod[0]->id) {
                $selected_time = true;
            }
        }
        return view('home.checkout', compact('addresses',
            'provinces',
            'PaymentMethods',
            'deliveryMethod',
            'address_id',
            'province_id',
            'date',
            'holidaysName',
            'sent_times',
            'selected_time',
            'alopeyk',
            'user'
        ));
    }

    //calculate delivery price function ajax
    public function checkout_calculate_delivery(Request $request)
    {
        $delivery_config = DeliveryConfig::first();
        $address_id = $request->address_id;
        $deliveryMethod = DeliveryMethod::where('is_active', 1)->get();
        $address = UserAddress::where('id', $address_id)->first();
        $province_id = $address->province_id;
        foreach ($deliveryMethod as $item) {
            $this->calculateDeliveryPrice($province_id, $address, $item);
        }
        $html = '';
        foreach ($deliveryMethod as $key => $item) {
            $passKeraye = '';
            if ($item->id == 3) {
                $passKeraye = '<input type="hidden" class="send_method" value="1">
                                                                        انتخاب کنید';
            }
            if ($item->id == 4) {
                $passKeraye = '<input type="hidden" class="send_method" value="1">
پس کرایه';
            }
            if ($item->id == 5) {
                $passKeraye = ' <input type="hidden" class="send_method" value="5">
                                                                        0';
            }
            if ($item->price_for_post == null) {
                $price = '';
            } else {
                $price = number_format($item->price_for_post) . ' تومان ';
            }
            if ($item->price_for_post == null and $item->id == 4) {
                $price = ' ';
            }
            if ($item->price_for_post == null and $item->id == 2) {
                $price = '-';
            }
            if ($item->exist_service == true) {
                $deactive = ' ';
            } else {
                $deactive = 'deActive';
            }
            if ($key == 0) {
                $kmActive = 'km-active';
            } else {
                $kmActive = ' ';
            }
            if ($item->exist_service == true) {
                $onclick = 'onclick="selectDeliveryMethod(this,' . $item->id . ')"';
            } else {
                $onclick = '';
            }
            $html = $html . '<div ' . $onclick . ' class="d-md-flex justify-content-between km-delivery-type-style ' . $deactive . ' ' . $kmActive . '">
                                                    <input checked="" class="km-value-control none km-active"
                                                           type="radio" value="">
                                                    <div class="d-flex flex-y-center">
                                                        <div class="km-img">
                                                            <img alt="پیک موتوری"
                                                                 src="' . imageExist(env('DELIVERY_METHOD_ICON'), $item->image) . '">
                                                        </div>
                                                        <div class="km-content">
                                                            <div class="km-title">' . $item->name . '</div>
                                                            <div class="km-description">
                                                                ' . $item->description . '
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="price">
                                                        <div class="title">هزینه:</div>
                                                        <div class="priceIn">'
                . $passKeraye . $price . '</div></div></div>';
        }
        //set time for methods
        $set_time_for = $delivery_config->methods_id;
        $set_time_for = explode(',', $set_time_for);
        $selected_time = false;
        foreach ($set_time_for as $item) {
            if ($item == $deliveryMethod[0]->id) {
                $selected_time = true;
            }
        }
        return \response()->json([1, $html, $deliveryMethod[0]->id, $selected_time]);
    }

    //show send time
    public function select_delivery_method(Request $request)
    {
        $delivery_config = DeliveryConfig::first();
        $delivery_selected_id = $request->delivery_selected_id;
        //set time for methods
        $set_time_for = $delivery_config->methods_id;
        $set_time_for = explode(',', $set_time_for);
        $selected_time = false;
        foreach ($set_time_for as $item) {
            if ($item == $delivery_selected_id) {
                $selected_time = true;
            }
        }
        return \response()->json([1, $selected_time]);
    }

    //calculateAloPeykPrice
    public function calculateAloPeykPrice(Request $request)
    {
        if ($request->alopeyk_location == null) {
            return response()->json([0, 'انتخاب محل دقیق تحویل کالا الزامی است']);
        }
        $apiResponse = Alopeyk::authenticate();
        if ($apiResponse && $apiResponse->status == "success") {
            //origin location
            $origin_location = AlopeykConfig::first()->alopeyk_location;
            $origin_location = explode('-', $origin_location);
            $origin = [
                "type" => "origin",
                "lat" => $origin_location[0],
                "lng" => $origin_location[1],
            ];
            //destination
            $destination_location = $request->alopeyk_location;
            $destination_location = explode('-', $destination_location);
            session()->put('lat', $destination_location[0]);
            session()->put('lng', $destination_location[1]);
            $destination = [
                "type" => "destination",
                "lat" => $destination_location[0],
                "lng" => $destination_location[1],
            ];

            $user = $apiResponse->object->user;
            //calculatePrice
            $price_response = Alopeyk::NormalPrice($origin, $destination);
            $alopeyk_response = $price_response->object->price;
            session()->put('alopeyk_price', intval($alopeyk_response * 1.2));
            return response()->json([1, number_format($alopeyk_response * 1.2) . ' تومان ']);
        }
    }

    public function checkoutSaveStep1(Request $request)
    {
        $address_id = $request->address_id;
        $delivery_method_selected_id = $request->delivery_method_selected_id;
        $dayNameInput = $request->dayNameInput;
        $send_time_select = $request->send_time_select;
        $alopeyk_location = $request->alopeyk_location;
        //validation
        if ($address_id == null) {
            return \response()->json([0, 'انتخاب آدرس برای ارسال سفارش الزامی است.']);
        }
        if ($delivery_method_selected_id == null) {
            return \response()->json([0, 'انتخاب روش ارسال سفارش الزامی است.']);
        }
        if ($delivery_method_selected_id == 3 and $alopeyk_location == null) {
            return \response()->json([2, 'محل دقیق خود را انتخاب کنید']);
        }
        if ($delivery_method_selected_id == 3 and !session()->exists('alopeyk_price')) {
            return \response()->json([2, 'خطا در محاسبه قیمت الوپیک.لطفا دوباره امتحان کنید']);
        }
        //check send day
        $delivery_config = DeliveryConfig::first();
        $set_time_for = $delivery_config->methods_id;
        $set_time_for = explode(',', $set_time_for);
        foreach ($set_time_for as $item) {
            if ($item == $delivery_method_selected_id) {
                if ($dayNameInput == null) return \response()->json([0, 'انتخاب روز برای ارسال سفارش الزامی است.']);
                if ($send_time_select == null) return \response()->json([0, 'انتخاب ساعت برای ارسال سفارش الزامی است.']);
            }
        }
        session()->put('address_info', [
            'address_id' => $address_id,
            'delivery_method_selected_id' => $delivery_method_selected_id,
            'dayNameInput' => $dayNameInput,
            'send_time_select' => $send_time_select,
        ]);
//        $address = UserAddress::where('id', $address_id)->first();
//        if ($delivery_method_selected_id == 1 and $address->postal_code == null) {
//            return \response()->json([3, $address->address]);
//        }
        return \response()->json([1, 'آدرس با موفقیت ثبت گردید']);

    }

    public function AddPostalCodeToAddress(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'modal_postalCode' => 'required|iran_postal_code'
        ]);
        if ($validate->fails()) {
            $err = 'کد پستی وارد شده صحیح نمیباشد';
            return \response()->json([0, $err]);
        }
        try {
            DB::beginTransaction();
            $address = UserAddress::where('id', $request->address_id)->first();
            $address->update([
                'postal_code' => $request->modal_postalCode
            ]);
            $msg = 'کد پستی با موفقیت ثبت شد';
            DB::commit();
            return \response()->json([1, $msg]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json([0, $exception->getMessage()]);
        }
    }

    //check alopeyk exist
    public function exist_aloPeyk($address)
    {
        $alopeyk = false;
        $exist_cities = [304, 303, 311];
        foreach ($exist_cities as $exist_city) {
            if ($exist_city == $address->city_id) {
                $alopeyk = true;
            }
        }
        return $alopeyk;
    }

    //calculate delivery price function
    public function calculateDeliveryPrice($province_id, $address, $item)
    {
        $delivery_price = DeliveryMethodAmount::where('province_id', $province_id)->where('method_id', $item->id)->first();
        if ($delivery_price) {
            $price = $delivery_price->price;
            $item['price_for_post'] = $price;
            $item['exist_service'] = true;
            if ($price==0){
                session()->put('delivery_price', 'پس کرایه');
            }else{
                session()->put('delivery_price', intval($price));
            }

        } else {
            $item['price_for_post'] = null;
            $item['exist_service'] = false;
        }
        if ($item->id == 3) {
            $alopeyk = $this->exist_aloPeyk($address);
            if ($alopeyk) {
                $item['exist_service'] = true;
                $alopeyk_price = session()->get('alopeyk_price');
                session()->put('delivery_price', $alopeyk_price);
            }
        }
        if ($item->id == 4) {
            $item['exist_service'] = true;
            session()->put('delivery_price', 'پرداخت تیپاکس(پس کرایه)');
        }
        if ($item->id == 5) {
            $item['exist_service'] = true;
            session()->put('delivery_price', 'پرداخت حضوری');
        }

    }

    public function preview_checkout()
    {
        if (auth()->check()) {
            $user = \auth()->user();
        } else {
            alert()->warning('ابتدا باید وارد سبد خرید خود شوید', 'دقت کنید');
            return redirect()->route('home.index');
        }
        $carts = Cart::where('user_id', auth()->id())->get();
        if (count($carts) == 0) {
            alert()->error('سبد خرید شما خالی است', 'متاسفیم');
            return redirect()->route('home.index');
        }
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
        $address_info = session()->get('address_info');
        $address_id = $address_info['address_id'];
        $delivery_method_selected_id = $address_info['delivery_method_selected_id'];
        $delivery_method = DeliveryMethod::where('id', $delivery_method_selected_id)->first();
        $delivery_day = $address_info['dayNameInput'];
        $delivery_time = $address_info['send_time_select'];
        $address = UserAddress::where('id', $address_id)->first();
        //day and time for send
        //check send day
        $delivery_config = DeliveryConfig::first();
        $set_time_for = $delivery_config->methods_id;
        $set_time_for = explode(',', $set_time_for);
        $set_time = false;
        foreach ($set_time_for as $item) {
            if ($item == $delivery_method_selected_id) {
                $set_time = true;
            }
        }
      
		//calculate delivery price
        session()->forget('delivery_price');
        $this->calculateDeliveryPrice($address->province_id, $address, $delivery_method);
        //payments
        $PaymentMethods = PaymentMethods::where('is_active', 1)->get();
        $wallet = Wallet::where('user_id', auth()->id())->first();
        if ($wallet != null) {
            $amount = $wallet->amount;
        } else {
            $amount = 0;
        }
        return view('home.preview_check_out', compact('carts',
            'address',
            'delivery_method',
            'delivery_day',
            'delivery_time',
            'set_time',
            'PaymentMethods',
            'amount',
        ));
    }

    public function WalletUsage(Request $request)
    {
        $use_wallet = $request->use_wallet;
        if ($use_wallet == 1) {
            session()->put('use_wallet', 1);
        } else {
            session()->forget('use_wallet');
        }
        $original_price = summery_cart()['original_price'];
        $total_sale = summery_cart()['total_sale'];
        $coupon_amount = summery_cart()['coupon_amount'];
        $delivery_price = summery_cart()['delivery_price'];
        if (intval($delivery_price) == 0) {
            $delivery_price = $delivery_price;
        } else {
            $delivery_price = number_format($delivery_price) . ' تومان ';
        }
        $total_amount = summery_cart()['total_amount'];
        $wallet_amount = summery_cart()['wallet_amount'];
        $payment = summery_cart()['payment'];
        $html = ' <div class="cart-summary mb-4">
                                        <h3 class="cart-title text-uppercase">خلاصه سبد خرید </h3>
                                        <div class="cart-subtotal d-flex align-items-center justify-content-between">
                                            <label class="ls-25">کل سبد خرید </label>
                                            <span>' . number_format($original_price) . ' تومان </span>
                                        </div>
                                        <hr class="divider">
                                        <div class="order-total d-flex justify-content-between align-items-center">
                                            <label>تخفیف</label>
                                            <span>' . number_format($total_sale) . ' تومان </span>
                                        </div>
                                        <hr class="divider">
                                        <div class="order-total d-flex justify-content-between align-items-center">
                                            <label>مبلغ کد تخفیف</label>
                                            <span>' . number_format($coupon_amount) . ' تومان </span>
                                        </div>
                                        <hr class="divider">
                                        <div class="order-total d-flex justify-content-between align-items-center">
                                            <label>هزینه ارسال</label>
                                            <span>' . $delivery_price . '</span>
                                        </div>
                                        <hr class="divider-black">
                                        <div class="order-total text-black d-flex justify-content-between align-items-center">
                                            <label>جمع فاکتور</label>
                                            <span>' . number_format($total_amount) . ' تومان </span>
                                        </div>
                                        <hr class="divider">
                                        <div class="order-total text-black d-flex justify-content-between align-items-center">
                                            <label>کسر از کیف پول</label>
                                            <span>' . number_format($wallet_amount) . ' تومان </span>
                                        </div>
                                        <hr class="divider">
                                        <div class="order-total text-black d-flex justify-content-between align-items-center">
                                            <label>مبلغ قابل پرداخت</label>
                                            <span>' . number_format($payment) . ' تومان </span>
                                        </div>
                                    </div>';
        return response()->json([1, $html]);
    }

    public function check_limit()
    {
        if (!auth()->check()){
            $route=route('home.cart');
            $msg='خطا در شناسایی کاربر!لطفا دوباره وارد شوید';
            return [
                'status' => 0,
                'message' => $msg,
                'redirect' => $route,
            ];
        }
        $user = auth()->user();
        $role = $user->Role->id;
        $limit = LimitConfig::first();
        $count_limit = $limit->count;
        $day = (-1) * ($limit->day);
        if ($role != 2 or $count_limit == 0 or $limit->day == 0 or $limit->is_active == 0) {
            return [
                'status' => 1,
            ];
        }
        $limit_date = Carbon::now()->addDay($day);
        $orders = Order::where('user_id', $user->id)->where('status', '!=', 0)->where('created_at', '>', $limit_date)->get();
//        if (count($orders) == 0) {
//            return [
//                'status' => 1,
//            ];
//        }
        $total_quantity = 0;
        foreach ($orders as $order) {
            $total_quantity += $order->orderItems->sum('quantity');
        }
        $quantity_can_order = $count_limit - $total_quantity;
        if ($quantity_can_order < 1) {
            $end_limit_date = Carbon::parse($orders[0]->created_at)->addDay($limit->day);
            $end_limit_date = verta($end_limit_date)->format('%d %B Y H:i');
            $msg = 'محدودیت خرید! شما هر ' . $limit->day . ' روز میتوانید ' . $limit->count . ' کالا را خریداری نمایید.محدودیت شما در تاریخ ' . $end_limit_date . ' به پایان میرسد';
            $route=route('home.index');
            return [
                'status' => 0,
                'message' => $msg,
                'redirect' => $route,
            ];
        }
        $cart = Cart::where('user_id', $user->id)->get();
        $quantity_product_in_cart = $cart->sum('quantity');
        if ($quantity_product_in_cart <= $quantity_can_order) {
            return [
                'status' => 1,
            ];
        }
        if (isset($orders[0])){
            $end_limit_date = Carbon::parse($orders[0]->created_at)->addDay($limit->day);
            $end_limit_date = verta($end_limit_date)->format('%d %B Y H:i');
            $route=route('home.cart');
            $msg = 'محدودیت خرید! شما تا تاریخ '.$end_limit_date.' حداکثر '.$quantity_can_order.' کالای دیگر را میتوانید خریداری کنید';

        }else{
            $route=route('home.cart');
            $msg = 'محدودیت خرید! شما هر ' . $limit->day . ' روز میتوانید ' . $limit->count . ' کالا را خریداری نمایید.';
        }
       return [
            'status' => 0,
            'message' => $msg,
            'redirect' => $route,
        ];

    }

    public function check_national_code(Request $request)
    {
        if (!auth()->check()){
            $route=route('home.cart');
            $msg='خطا در شناسایی کاربر!لطفا دوباره وارد شوید';
            return [
                'status' => 0,
                'message' => $msg,
                'redirect' => $route,
            ];
        }
        $user =User::find($request->user_id);
        $national_code=$user->national_code;
        if ($national_code==null){
            $msg='وارد کردن کد ملی الزامی است';
            return [
                'status' => 2,
                'message' => $msg,
            ];
        }
        return [
            'status' => 1,
        ];
    }

    public function add_national_code(Request $request)
    {
        if (!auth()->check()){
            $route=route('home.cart');
            $msg='خطا در شناسایی کاربر!لطفا دوباره وارد شوید';
            return [
                'status' => 0,
                'message' => $msg,
                'redirect' => $route,
            ];
        }
        $request->validate([
            'national_code'=>'required|melli_code',
        ]);
        $user =User::find($request->user_id);
        $user->update([
            'national_code'=>$request->national_code,
        ]);
        return response()->json([1,'کد ملی شما با موفقیت ثبت شد']);
    }

}


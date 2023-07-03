<?php

namespace App\Http\Controllers\Home;

use App\Models\Cart;
use App\Models\Coupon;
use App\Alopeyk\Alopeyk;
use App\Models\AlopeykConfig;
use App\Models\DeliveryConfig;
use App\Models\DeliveryMethod;
use App\Models\DeliveryMethodAmount;
use App\Models\Order;
use App\Models\PaymentMethods;
use App\Models\ProductAttrVariation;
use App\Models\ProductOption;
use App\Models\ProductVariation;
use App\Models\Province;
use App\Models\User;
use App\Models\UserAddress;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            alert()->error('ابتدا وارد حساب کاربری خود شوید', 'متاسفیم');
            return redirect()->route('login');
        }
        session()->forget('use_wallet');
        session()->forget('coupon');
        $user_id = auth()->id();
        $carts = Cart::where('user_id', $user_id)->get();
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
        return view('home.cart', compact('carts'));
    }

    public function add(Request $request)
    {
        if (!auth()->check()) {
            return \response()->json([0, 'login']);
        }
        if ($request->is_single_page == 0) {
            $exist_productVariation = ProductAttrVariation::where('product_id', $request->product_id)->exists();
            if ($exist_productVariation) {
                $product = Product::where('id', $request->product_id)->first();
                $alias = $product->alias;
                $route = route('home.product', ['alias' => $alias]);
                return response()->json(['has_attr', $route]);
            }
            $exist_product_option = ProductOption::where('product_id', $request->product_id)->exists();
            if ($exist_product_option) {
                $product = Product::where('id', $request->product_id)->first();
                $alias = $product->alias;
                $route = route('home.product', ['alias' => $alias]);
                return response()->json(['has_option', $route]);
            }
        }
        $product_id = $request->product_id;
        $product_has_option = $request->product_has_option;
        $variation_id = $request->variation_id;
        $color_id = $request->color_id;
        $product_has_variation = $request->product_has_variation;
        $product_has_color = $request->product_has_color;
        $option_ids = json_encode($request->option_ids);
        if ($request->option_ids == null) {
            $option_ids = null;
        }
        $product_price=product_price_for_user_normal($product_id)[2];
        if ($product_price==0){
            return response()->json([0,'price_error']);
        }
        $user_id = auth()->id();
        $product_exist_in_cart = Cart::where('product_id', $product_id)
            ->where('variation_id', $variation_id)
            ->where('color_id', $color_id)
            ->where('option_ids', $option_ids)
            ->where('user_id', $user_id)->first();
        if ($product_exist_in_cart == null) {
            //create cart
            $check_product_quantity = parent::check_product_quantity($product_id, $product_has_variation, $variation_id, $color_id, $request->quantity);
            if (!$check_product_quantity[0]) {
                return response()->json([0, 'quantity']);
            }
            Cart::create([
                'user_id' => $user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'variation_id' => $variation_id,
                'color_id' => $color_id,
                'option_ids' => $option_ids,
            ]);
            if (session()->exists('coupon.code')) {
                checkCoupon(session()->get('coupon.code'));
            }
            return response()->json([1, 'ok']);
        }
        if ($product_exist_in_cart != null) {
            //update cart
            $quantity = $product_exist_in_cart->quantity + $request->quantity;
            $check_product_quantity = parent::check_product_quantity($product_id, $product_has_variation, $variation_id, $color_id, $quantity);
            if (!$check_product_quantity[0]) {
                return response()->json([0, 'quantity']);
            }
            $product_exist_in_cart->update([
                'quantity' => $quantity
            ]);
            if (session()->exists('coupon.code')) {
                checkCoupon(session()->get('coupon.code'));
            }
            return response()->json([1, 'ok']);
        }

    }

    public function clear()
    {
        Cart::clear();
        return response()->json('ok');
    }

    public function update(Request $request)
    {
        $cart = Cart::where('id', $request->cart_id)->first();
        $product_id = $cart->product_id;
        $variation_id = $cart->variation_id;
        $color_id = $cart->color_id;
        $quantity = $request->quantity;
        if ($quantity == 0) {
            $cart->delete();
        }
        $variation_exists = ProductAttrVariation::where('product_id', $product_id)
            ->where('attr_value', $variation_id)
            ->where('color_attr_value', $color_id)->exists();
        if ($variation_exists) {
            $product_has_variation = 1;
        } else {
            $product_has_variation = 0;
        }

        $check_product_quantity = parent::check_product_quantity($product_id, $product_has_variation, $variation_id, $color_id, $quantity);
        if (!$check_product_quantity[0]) {
            return response()->json([0, $check_product_quantity[1]]);
        }
        $cart->update([
            'quantity' => $request->quantity
        ]);
        if (session()->exists('coupon.code')) {
            checkCoupon(session()->get('coupon.code'));
        }
        return response()->json([1, $check_product_quantity[1]]);

    }

    public function remove_cart(Request $request)
    {
        try {
            DB::beginTransaction();
            $cart_id = $request->cart_id;
            $user_id = auth()->id();
            $cart = Cart::where('id', $cart_id)
                ->where('user_id', $user_id)
                ->first();
            $cart->delete();
            DB::commit();
            $msg = 'کالای مورد نظر با موفقیت از سبد خرید شما حذف گردید';
            return response()->json([1, $msg]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([0, $exception->getMessage()]);
        }

    }

    public function remove_carts(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_id = auth()->id();
            Cart::where('user_id', $user_id)->delete();
            DB::commit();
            $msg = 'سبد خرید شما خالی شد';
            return response()->json([1, $msg]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([0, $exception->getMessage()]);
        }

    }


    public function checkCoupon(Request $request)
    {

        $request->validate([
            'couponCode' => 'required'
        ]);
        $coupon = Coupon::where('code', $request->couponCode)->first();
        if ($coupon == null) {
            session()->forget('coupon');
            alert()->error('کد تخفیف وارد شده معتبر نیست', 'دقت کنید');
            return redirect()->back();
        }
        if ($coupon->user_id == null) {
            $result = checkCoupon($request->couponCode);
            if (array_key_exists('error', $result)) {
                alert()->error($result['error'], 'دقت کنید');
            } else {
                alert()->success($result['success'], 'باتشکر');
            }
            return redirect()->back();
        }

        if (!auth()->check()) {
            session()->forget('coupon');
            alert()->error('برای استفاده از کد تخفیف نیاز هست ابتدا وارد وب سایت شوید', 'دقت کنید');
            return redirect()->back();
        }

        $result = checkCoupon($request->couponCode);

        if (array_key_exists('error', $result)) {
            alert()->error($result['error'], 'دقت کنید');
        } else {
            alert()->success($result['success'], 'باتشکر');
        }
        return redirect()->back();
    }

    public function get()
    {
        $carts = Cart::where('user_id', auth()->id())->get();
        $carts=view('home.sections.cart',compact('carts'))->render();
        return \response()->json(['ok', $carts]);
    }

    public function checkCartAjax()
    {
        $check = parent::checkCart();
        if (array_key_exists('success', $check)) {
            return response()->json([1]);
        }
        return response()->json([0, $check['error']]);
    }

    public function check_order(Request $request){
        $order_number=$request->order_number;
        $order_exist=Order::where('order_number',$order_number)->exists();
        if ($order_exist){
            $order=Order::where('order_number',$order_number)->first();
            $status=$order->DeliveryMethodStatus->title;
            $msg='<span> وضعیت سفارش : '.$status.'</span>';
            if ($order->TrackingCode!=null){
                $tracking_code='<span> شماره رهگیری پست : '.$order->TrackingCode.'</span>';
                $msg=$msg.'<br>'.$tracking_code;
            }
            return response()->json([1,$msg]);
        }
        $msg='<span>سفارشی یافت نشد</span>';
        return response()->json([1,$msg]);
    }


}

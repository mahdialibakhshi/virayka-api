<?php

namespace App\PaymentGateway;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Gift;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductAttrVariation;
use App\Models\ProductOption;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Notifications\GiftSMS;
use App\Notifications\PaymentReceipt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Payment
{
    public function createOrder($addressId, $token, $gateway_name)
    {
        try {
            $use_wallet = 0;
            DB::beginTransaction();
            $address_info = session()->get('address_info');
            $delivery_method_selected_id = $address_info['delivery_method_selected_id'];
            $dayNameInput = $address_info['dayNameInput'];
            $send_time_select = $address_info['send_time_select'];
            //
            $payment_type = session()->get('payment_type');
            //پرداخت از کیف پول
            if (session()->exists('use_wallet')) {
                $use_wallet = session()->get('use_wallet');
            }
            if (summery_cart()['payment'] == 0 and $use_wallet != 0) {
                $payment_type = 'پرداخت از کیف پول';
            }
            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $addressId,
                'coupon_id' => session()->has('coupon') ? session()->get('coupon.id') : null,
                'total_amount' => summery_cart()['total_amount'],
                'delivery_amount' => intval(summery_cart()['delivery_price']),
                'coupon_amount' => summery_cart()['coupon_amount'],
                'paying_amount' => summery_cart()['payment'],
                'wallet' => summery_cart()['wallet_amount'],
                'delivery_date' => $dayNameInput,
                'delivery_time' => $send_time_select,
                'payment_type' => $payment_type,
                'delivery_method' => $delivery_method_selected_id,
                'delivery_status' => 1
            ]);

            $order_number = intval($order->id) + 9750;
            $order->update([
                'order_number' => $order_number,
            ]);

            $carts = Cart::where('user_id', auth()->id())->get();
            foreach ($carts as $cart) {
                $product_attr_variation = ProductAttrVariation::where('product_id', $cart->product_id)
                    ->where('attr_value', $cart->variation_id)
                    ->where('color_attr_value', $cart->color_id)
                    ->first();
                if ($product_attr_variation != null) {
                    $product_attr_variation_id = $product_attr_variation->id;
                    $cart['product_attr_variation_id'] = $product_attr_variation_id;
                }
                $product_price = product_price_for_user_normal($cart->product_id, $cart->product_attr_variation_id)[2];
                $option_price = 0;
                if ($cart->option_ids != null) {
                    $option_ids = json_decode($cart->option_ids);
                    foreach ($option_ids as $option) {
                        $price = ProductOption::where('id', $option)->first()->price;
                        $option_price = $option_price + $price;
                    }
                }
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'variation_id' => $cart->variation_id,
                    'product_price' => $product_price,
                    'option_price' => $option_price,
                    'quantity' => $cart->quantity,
                    'color_id' => $cart->color_id,
                    'option_ids' => $cart->option_ids,
                    'subtotal' => ($cart->quantity * ($option_price + $product_price)),
                ]);
            }
            Transaction::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'amount' => summery_cart()['payment'],
                'token' => $token,
                'gateway_name' => $gateway_name,
                'description' => 'َخرید کالا',
            ]);
            DB::commit();
            return $order;
        } catch (\Exception $ex) {
            DB::rollBack();
            return ['error' => $ex->getMessage()];
        }

        return ['success' => 'success!'];
    }

    public function updateOrder($token, $refId, $orderId = null)
    {
        try {
            $use_wallet = 0;
            DB::beginTransaction();
            //پرداخت از کیف پول
            if (session()->exists('use_wallet')) {
                $use_wallet = session()->get('use_wallet');
            }
            if (summery_cart()['payment'] == 0 and $use_wallet != 0) {
                $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            } else {
                if ($refId == 'cash') {
                    $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
                } else {
                    if ($token == null) {
                        $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
                    } else {
                        $transaction = Transaction::where('token', $token)->firstOrFail();
                    }
                }
            }
            $transaction->update([
                'status' => 1,
                'ref_id' => $refId
            ]);
            $order = Order::findOrFail($transaction->order_id);
            $user = User::where('id', $order->user_id)->first();
            $order->update([
                'payment_status' => $refId == 'cash' ? 2 : 1,
                // 'payment_type' => $refId=='cash' ? 2 : 1,
                'status' => $refId == 'cash' ? 1 : 2,
                'delivery_status' => $refId == 'cash' ? 1 : 2,
            ]);
            $coupon = session()->get('coupon');
            if ($coupon != []) {
                $code_id = $coupon['id'];
                $coupon = Coupon::where('id', $code_id)
                    ->where('id', $code_id)
                    ->first();
                if (!empty($coupon)) {
                    $times = $coupon->times;
                    $coupon->update([
                        'times' => $times - 1
                    ]);
                }
                session()->forget('coupon');
            }


            //کم کردن تعداد محصول پس از خرید
            $carts = Cart::where('user_id',$user->id)->get();
            foreach ($carts as $cart) {
                $this->minus_product_after_sale($cart);
            }

            //به روز کردن کیف پول در صورتیکه خرید نهایی شده باشد
            $wallet_exists = Wallet::where('user_id', $user->id)->exists();
            if ($wallet_exists) {
                $wallet = Wallet::where('user_id', $user->id)->first();
                //ثبت رکورد برای استفاده از کیف پول
                if (summery_cart()['wallet_amount'] != 0) {
                    WalletHistory::create([
                        'user_id' => auth()->id(),
                        'amount' => summery_cart()['wallet_amount'],
                        'type' => 4,
                        'increase_type' => 0,
                        'previous_amount' => $wallet->amount,
                    ]);
                    $wallet->update([
                        'amount' => summery_cart()['left_over_wallet'],
                    ]);
                }
            }
            //هدیه تراکنش
            $this->transaction_gift($user);

            //پاک کردن سبد خرید
            $carts = Cart::where('user_id',$user->id)->get();
            foreach ($carts as $cart) {
                $cart->delete();
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return [0, $ex->getMessage()];
        }
        try {
            DB::beginTransaction();
            //send sms for user
            $user->notify(new PaymentReceipt($order->id, $order->order_number, $order->paying_amount, $refId));
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return [2, $ex->getMessage()];
        }
        return [1];
    }

    public function minus_product_after_sale($cart)
    {
        try {
        $product_id = $cart->product_id;
        $variation_id = $cart->variation_id;
        $color_id = $cart->color_id;
        $quantity = $cart->quantity;
        $product_has_variation = 0;
        if ($quantity == 0) {
            $cart->delete();
        }
        $variation_exists = ProductAttrVariation::where('product_id', $product_id)
            ->where('attr_value', $variation_id)
            ->where('color_attr_value', $color_id)->exists();
        if ($variation_exists) {
            $product_has_variation = 1;
        }
        //
        //product has variations
        if ($product_has_variation != 0) {
            $product_attr_variation = ProductAttrVariation::where('product_id', $product_id)
                ->where('attr_value', $variation_id)
                ->where('color_attr_value', $color_id)->first();
            $product_attr_variation->update([
                'quantity' => ($product_attr_variation->quantity) - $quantity
            ]);
        }
        $product = Product::where('id', $product_id)->first();
        $product->update([
            'quantity' => ($product->quantity) - $quantity
        ]);

        }catch (\Exception $exception){
            Log::error('minus_quantity_error:'.$exception->getMessage());
        }
    }

    public function transaction_gift($user)
    {
        $use_wallet = 0;
        $payment = summery_cart()['payment'];
        $gift_amount = 0;
        $gift_transaction_amount = 0;
        if (session()->exists('use_wallet')) {
            $use_wallet = session()->get('use_wallet');
        }
        if (summery_cart()['payment'] == 0 and $use_wallet != 0) {
            $payment = summery_cart()['wallet_amount'];
        }
        $gifts = Gift::orderby('transaction', 'desc')->get();
        foreach ($gifts as $gift) {
            $gift_transaction = $gift->transaction;
            if ($payment >= $gift_transaction) {
                $gift_amount = $gift->gift;
                $gift_transaction_amount = $gift_transaction;
                break;
            }
        }
        if ($gift_amount != 0) {
            $previous_amount = 0;
            $wallet_exists = Wallet::where('user_id', $user->id)->exists();
            if ($wallet_exists) {
                $wallet = Wallet::where('user_id', $user->id)->first();
                $previous_amount = $wallet->amount;
            } else {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'amount' => 0,
                ]);
            }
            //create wallet history
            WalletHistory::create([
                'user_id' => $user->id,
                'amount' => $gift_amount,
                'previous_amount' => $previous_amount,
                'increase_type' => 1,
                'type' => 3,
            ]);
            //update wallet
            $new_wallet_amount = $previous_amount + $gift_amount;
            $wallet->update([
                'amount' => $new_wallet_amount
            ]);
            $user = User::where('id', $user->id)->first();
            $user->notify(new GiftSMS($gift_transaction_amount, $gift_amount, $new_wallet_amount));
        }
    }
}

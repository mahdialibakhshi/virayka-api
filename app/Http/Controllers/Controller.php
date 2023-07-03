<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\InformMe;
use App\Models\Product;
use App\Models\ProductAttrVariation;
use App\Models\ProductVariation;
use App\Models\User;
use App\Notifications\ProductUpdateSms;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Image;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function aliasCreator($alias)
    {
        return str_replace(' ', '_', $alias);
    }

    public function createThumbnail($width, $image, $env, $name)
    {
        //create thumbnail
        $img = Image::make($image->path());
        $img->resize($width, 'auto', function ($const) {
            $const->aspectRatio();
        })->save($env . $name);
    }

    public function updateProductWithAttr($product_id)
    {
        $product_variations = ProductAttrVariation::where('product_id', $product_id)->get();
        if (sizeof($product_variations) > 0) {
            $quantity = 0;
            foreach ($product_variations as $item) {
                //set quantity
                $quantity = $quantity + $item->quantity;
                //if variation has discount
            }
            $cheapest_attr_variation_exists=ProductAttrVariation::where('product_id',$product_id)->where('quantity','>',0)->orderby('price','asc')->exists();
            if($cheapest_attr_variation_exists){
                $cheapest_attr_variation=ProductAttrVariation::where('product_id',$product_id)->where('quantity','>',0)->orderby('price','asc')->first();
            }else{
                $cheapest_attr_variation=ProductAttrVariation::where('product_id',$product_id)->orderby('price','asc')->first();
            }
            Product::where('id', $product_id)->update([
                'quantity' => $quantity,
                'price' => $cheapest_attr_variation->price,
                'percent_sale_price' => $cheapest_attr_variation->percent_sale_price,
                'sale_price' => ($cheapest_attr_variation->price)-($cheapest_attr_variation->price*$cheapest_attr_variation->percent_sale_price/100),
            ]);
        }
    }

    public function check_product_quantity($product_id, $product_has_variation, $variation_id, $color_id, $quantity)
    {
        //product has variations
        if ($product_has_variation != 0) {
            $product = ProductAttrVariation::where('product_id', $product_id)
                ->where('attr_value', $variation_id)
                ->where('color_attr_value', $color_id)->first();
        } else {
            //single product
            $product = Product::where('id', $product_id)->first();
        }
        $product_quantity = $product->quantity;
        //check product exist
        if ($quantity > $product_quantity) {
            return [false, $product_quantity];
        }
        return [true, $quantity];
    }

    public function checkCart()
    {
        $carts = Cart::where('user_id', auth()->id())->get();

        if (count($carts) == 0) {
            return ['error' => 'سبد خرید شما خالی می باشد'];
        }
        $error_quantity = false;
        $error_quantity_msg = '';
        foreach ($carts as $cart) {
            $product_id = $cart->product_id;
            $variation_id = $cart->variation_id;
            $color_id = $cart->color_id;
            $quantity = $cart->quantity;
            $variation_exists = ProductAttrVariation::where('product_id', $product_id)
                ->where('attr_value', $variation_id)
                ->where('color_attr_value', $color_id)->exists();
            if ($variation_exists) {
                $product_has_variation = 1;
            } else {
                $product_has_variation = 0;
            }

            $check_product_quantity = $this->check_product_quantity($product_id, $product_has_variation, $variation_id, $color_id, $quantity);
            if (!$check_product_quantity[0]) {
                $product = $cart->Product->name;
                if ($check_product_quantity[1] == 0) {
                    $cart->delete();
                    $msg = 'موجودی ' . $product . ' به پایان رسیده است. این کالا از سبد خرید شما حذف شد.';
                } else {
                    $cart->update([
                        'quantity' => $check_product_quantity[1]
                    ]);
                    $msg = 'از کالای ' . $product . ' تنها ' . $check_product_quantity[1] . ' عدد موجود است.';
                }
                $error_quantity = true;
                $error_quantity_msg .= $msg;
            }
        }
        if ($error_quantity) {
            return ['error' => $error_quantity_msg];
        }
        return ['success' => 'success'];
    }

    public function send_sms_to_user_if_product_exist($previous_quantity, $new_quantity, $product_id)
    {
        if ($previous_quantity != 0) {
            return true;
        }
        if (!$new_quantity > 0) {
            return true;
        }
        $user_ids = [];
        $product = Product::where('id', $product_id)->first();
        $informMe = InformMe::where('product_id', $product_id)->get();
        foreach ($informMe as $item) {
            array_push($user_ids, $item->user_id);
        }
        foreach ($user_ids as $user_id) {
            $user_exists = User::where('id', $user_id)->exists();
            if ($user_exists) {
                $user = User::where('id', $user_id)->first();
                $user->notify(new ProductUpdateSms($product));
            }
        }

    }

}

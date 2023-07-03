<?php

namespace App\Channels;

use App\Models\OrderItem;
use App\Models\Setting;
use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;

class PaymentReceiptSmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receptor = $notifiable->cellphone;
        $type = 1;
        $param1 = $notification->order_number;
        $order_id = $notification->order_id;
        if ($notification->refId=='cash'){
            $payment_type='نقدی';
            $template = "cashReceipt";
            $param2 = number_format($notification->amount);
            $api = new GhasedakApi(env('GHASEDAK_API_KEY'));
            $api->Verify($receptor, $type, $template, $param1, $param2);
        }elseif ($notification->refId=='پرداخت از کیف پول'){
            $payment_type='پرداخت از کیف پول';
            $template = "WalletPayment";
            $api = new GhasedakApi(env('GHASEDAK_API_KEY'));
            $api->Verify($receptor, $type, $template, $param1);
        } else{
            $payment_type='پرداخت آنلاین';
            $template = "paymentReceipt";
            $param2 = number_format($notification->amount);
            $param3 = $notification->refId;
//            $param3=str_replace(' ',',',$param3);
            $api = new GhasedakApi(env('GHASEDAK_API_KEY'));
            $api->Verify($receptor, $type, $template, $param1, $param2, $param3);
        }
        //send sms for admin
        $order_items=OrderItem::where('order_id',$order_id)->get();
        $message='';
        foreach ($order_items as $item){
            if(isset($item->AttributeValues->id) and  $item->AttributeValues->id!=217){
                $attr_name=$item->AttributeValues->name;
            }else{
                $attr_name='';
            }
            if (isset($item->Color->id) and  $item->Color->id!=346){
                $color_name=$item->Color->name;
            }else{
                $color_name='';
            }
            $product_name=$item->Product->name.'/'.$attr_name.'/'.$color_name;
            $product_price=number_format($item->subtotal);
            $quantity=$item->quantity;
            $product_name=' نام محصول '.$product_name;
            $product_price=' قیمت '.$product_price;
            $quantity=' تعداد '.$quantity;
            $message=$product_name.' '.$product_price.' '.$quantity.'.......'.$message;
        }
        $admins=Setting::first()->delivery_order_numbers;
        $admins_cellphone=explode(',',$admins);
        $param2=$payment_type;
//        $param2 = str_replace(' ','.',$payment_type);;
//        $message=str_replace(' ','.',$message);
        $param3 = $message;
        foreach ($admins_cellphone as $receptor){
            $template = "AdminOrderReceipt";
            $api = new GhasedakApi(env('GHASEDAK_API_KEY'));
            $api->Verify($receptor, $type, $template, $param1,$param2,$param3);
        }
    }
}

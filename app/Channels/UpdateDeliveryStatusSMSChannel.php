<?php

namespace App\Channels;

use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;
use App\Models\OrderStatus;
use App\Models\Setting;

class UpdateDeliveryStatusSMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receptor = $notifiable->cellphone;
        $type = 1;
        $template = "changeOrderStatus";
        $setting=Setting::first();
        $param1= $setting->productCode.'-'.$notification->order_number;
        $param2=OrderStatus::where('id',$notification->delivery_status)->first()->title;
//        $param2=str_replace(' ','.',$param2);
        $api = new GhasedakApi(env('GHASEDAK_API_KEY'));
        $api->Verify(
            $receptor,  // receptor
            $type,              // 1 for text message and 2 for voice message "my-template",  // name of the template which you've created in you account
            $template,       // parameters (supporting up to 10 parameters)
            $param1,
            $param2,
        );
    }
}

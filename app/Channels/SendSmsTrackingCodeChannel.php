<?php

namespace App\Channels;

use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;

class SendSmsTrackingCodeChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receptor = $notifiable->cellphone;
        $type = 1;
        $template = "trackingCode";
        $param1=$notifiable->name;
//        $param1=str_replace(' ','.',$param1);
        $param2 = $notification->order_number;
        $param3 = $notification->trackingCode;
        $api = new GhasedakApi(env('GHASEDAK_API_KEY'));
        $api->Verify(
            $receptor,  // receptor
            $type,              // 1 for text message and 2 for voice message "my-template",  // name of the template which you've created in you account
            $template,       // parameters (supporting up to 10 parameters)
            $param1,
            $param2,
            $param3,
        );


    }
}

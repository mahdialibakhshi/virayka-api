<?php

namespace App\Channels;

use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;

class userChargeWalletChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receptor = $notifiable->cellphone;
        $param1=$notifiable->name;
        //$param1=str_replace(' ','.',$user);
        $type = 1;
        $template = "userChargeWallet";
        $param2 = number_format($notification->amount);
        $param3 = number_format($notification->new_amount);
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

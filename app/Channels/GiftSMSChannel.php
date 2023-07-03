<?php

namespace App\Channels;

use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;

class GiftSMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receptor = $notifiable->cellphone;
        $param1=$notifiable->name;
//        $param1=str_replace(' ',',',$user);
        $type = 1;
        $template = "giftChargeWallet";
        $param2 = number_format($notification->gift_amount);
        $param3 = number_format($notification->gift_transaction_amount);
        $param4 = number_format($notification->new_wallet_amount);
        $api = new GhasedakApi(env('GHASEDAK_API_KEY'));
        $api->Verify(
            $receptor,  // receptor
            $type,              // 1 for text message and 2 for voice message "my-template",  // name of the template which you've created in you account
            $template,       // parameters (supporting up to 10 parameters)
            $param1,
            $param2,
            $param3,
            $param4,
        );
    }
}

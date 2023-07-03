<?php

namespace App\Channels;

use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;

class changeStatusTicketSmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receptor = $notifiable->cellphone;
        $type = 1;
        $template = "changeStatus";
        $param1 = $notification->status;
//        $param1=str_replace(' ','.',$param1);
        $api = new GhasedakApi(env('GHASEDAK_API_KEY'));
        $api->Verify(
            $receptor,  // receptor
            $type,              // 1 for text message and 2 for voice message "my-template",  // name of the template which you've created in you account
            $template,       // parameters (supporting up to 10 parameters)
            $param1,
        );


    }
}

<?php

namespace App\Channels;

use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;

class newTicketChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receptor = $notifiable->cellphone;
        $type = 1;
        $template = "newTicket";
        $param = $notifiable->ticket_sender;
        $param = preg_replace('/\s+/', '', $param);
        $api = new GhasedakApi(env('GHASEDAK_API_KEY'));

        $api->Verify(
            $receptor,  // receptor
            $type,              // 1 for text message and 2 for voice message "my-template",  // name of the template which you've created in you account
            $template,       // parameters (supporting up to 10 parameters)
            $param,
        );


    }
}

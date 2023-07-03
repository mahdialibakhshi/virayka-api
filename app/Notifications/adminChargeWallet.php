<?php

namespace App\Notifications;

use App\Channels\adminChargeWalletChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class adminChargeWallet extends Notification
{
    use Queueable;
    public $amount;
    public $new_amount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($amount,$new_amount,$increase_type)
    {
        $this->amount=$amount;
        $this->new_amount=$new_amount;
        $this->increase_type=$increase_type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [adminChargeWalletChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toSms($notifiable)
    {
        return [$this->amount, $this->new_amount];
    }
}

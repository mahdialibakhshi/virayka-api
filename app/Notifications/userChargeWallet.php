<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Channels\userChargeWalletChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class userChargeWallet extends Notification
{
    use Queueable;
    public $amount;
    public $new_amount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($amount,$new_amount)
    {
        $this->amount=$amount;
        $this->new_amount=$new_amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [userChargeWalletChannel::class];
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

<?php

namespace App\Notifications;

use App\Channels\GiftSMSChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GiftSMS extends Notification
{
    use Queueable;

    public $gift_transaction_amount;
    public $gift_amount;
    public $new_wallet_amount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($gift_transaction_amount,$gift_amount,$new_wallet_amount)
    {
        $this->gift_transaction_amount=$gift_transaction_amount;
        $this->gift_amount=$gift_amount;
        $this->new_wallet_amount=$new_wallet_amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [GiftSMSChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
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
        return [
            $this->gift_transaction_amount,
            $this->gift_amount,
            $this->new_wallet_amount
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TransactionNotification extends Notification
{
    use Queueable;

    public $permitApplication;

    public function __construct($permitApplication)
    {
        $this->permitApplication = $permitApplication;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Transaction Confirmation'))
            ->markdown('emails.transaction_notification', ['permitApplication' => $this->permitApplication]);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

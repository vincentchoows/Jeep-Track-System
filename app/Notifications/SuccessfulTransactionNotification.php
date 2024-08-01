<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SuccessfulTransactionNotification extends Notification
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
            ->subject(__('Successful Transaction'))
            ->line('Dear ' . $this->permitApplication->user->name . ',')
            ->line('We are pleased to inform you that your transaction has been successfully processed.')
            ->line('Here are the details of your application:')
            ->markdown('emails.successful_transaction', ['permitApplication' => $this->permitApplication]);
    }
}

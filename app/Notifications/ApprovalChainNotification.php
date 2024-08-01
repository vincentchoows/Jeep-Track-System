<?php

// app/Notifications/ApprovalChainNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApprovalChainNotification extends Notification
{
    use Queueable;

    public $permitApplication;
    public $user;

    public function __construct($permitApplication, $user )
    {
        $this->permitApplication = $permitApplication;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject(__('Permit Application Review'))
        ->line('Dear ' . $this->user->name . ',')
        ->markdown('emails.approval_chain_notification', ['permitApplication' => $this->permitApplication, 'user' => $this->user]);
    }
}

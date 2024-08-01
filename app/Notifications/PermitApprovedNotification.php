<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermitApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $permitApplication;
    public function __construct($permitApplication)
    {
        $this->permitApplication = $permitApplication;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Permit Card is Fully Approved and Activated')
            ->line('Congratulations! Your permit card has been fully approved and activated.')
            ->line('Here are the details:')
            ->markdown('emails.permit_card_approved', ['permitApplication' => $this->permitApplication]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

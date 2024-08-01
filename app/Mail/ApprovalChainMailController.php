<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovalChainMailController extends Mailable
{
    use Queueable, SerializesModels;

    public $permitApplication;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $permitApplication
     */
    public function __construct($user, $permitApplication)
    {
        $this->user = $user;
        $this->permitApplication = $permitApplication;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.approval_notification')
                    ->layout('mails.layout.email')  // Specify the default email layout if needed
                    ->subject('Permit Application Updated');  // Subject of the email
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountVerificationAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Response status
     */
    public $status;

    /**
     * @var array Response
     */
    public $response;

    /**
     * @var string Error
     */
    public $error;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->status = $mailData['status'];
        $this->response = $mailData['response'];
        $this->error = $mailData['error'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Account verification')
            ->view('emails.account-verification-admin');
    }
}

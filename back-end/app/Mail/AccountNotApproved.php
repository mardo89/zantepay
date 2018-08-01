<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountNotApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Activation link
     */
    public $accountLink;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->accountLink = action('UserController@profileSettings');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Account verification')
            ->view('emails.account-not-approved');
    }
}

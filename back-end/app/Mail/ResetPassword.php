<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Activation link
     */
    public $resetLink;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     *
     */
    public function __construct($mailData)
    {
        $this->resetLink = action('IndexController@resetPassword', ['rt' => $mailData['resetToken']]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset password')
            ->view('emails.reset-password');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateAccount extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Activation link
     */
    public $activationLink;

    /**
     * Create a new message instance.
     *
     * @param string $activationLink
     *
     */
    public function __construct($activationLink)
    {
        $this->activationLink = $activationLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Account activation')
            ->from('info@zantepay.com')
            ->replyTo(env('CONTACT_EMAIL'))
            ->view('emails.activate-accaunt');
    }
}

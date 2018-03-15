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
     * @param string $uid
     *
     */
    public function __construct($uid)
    {
        $this->activationLink = action('IndexController@confirmActivation', ['uid' => $uid]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Account activation')
            ->view('emails.activate-accaunt');
    }
}

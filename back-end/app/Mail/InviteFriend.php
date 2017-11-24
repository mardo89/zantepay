<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteFriend extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string User email
     */
    public $userEmail;

    /**
     * @var string Referral link
     */
    public $referralLink;

    /**
     * Create a new message instance.
     *
     * @param string $userEmail
     * @param string $referralLink
     *
     */
    public function __construct($userEmail, $referralLink)
    {
        $this->userEmail = $userEmail;
        $this->referralLink = $referralLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Account activation')
            ->from(env('CONTACT_EMAIL'))
            ->replyTo($this->userEmail)->view('emails.invite-friend');
    }
}

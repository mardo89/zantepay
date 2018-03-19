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
     * @var string Referral link
     */
    public $referralLink;

    /**
     * Create a new message instance.
     *
     * @param string $uid
     *
     */
    public function __construct($uid)
    {
        $this->referralLink = action('IndexController@confirmInvitation', ['ref' => $uid]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Account activation')
            ->view('emails.invite-friend');
    }
}

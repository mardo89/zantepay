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
     * @param array $mailData
     *
     */
    public function __construct($mailData)
    {
        $this->referralLink = action('IndexController@confirmInvitation', ['ref' => $mailData['uid']]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('ZANTEPAY Invitation')
            ->view('emails.invite-friend');
    }
}

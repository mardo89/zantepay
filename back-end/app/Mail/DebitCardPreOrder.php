<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DebitCardPreOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Link to the main page
     */
    public $loginLink;

    /**
     * @var string Referral link
     */
    public $referralLink;

    /**
     * @var int Card design
     */
    public $cardDesign;

    /**
     * Create a new message instance.
     *
     * @param string $uid
     * @param int $cardDesign
     *
     * @return void
     */
    public function __construct($uid, $cardDesign)
    {
        $this->loginLink = action('IndexController@main');
        $this->referralLink = action('IndexController@confirmInvitation', ['ref' => $uid]);
        $this->cardDesign = $cardDesign;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Debit Card Pre-order')
            ->view('emails.card-preordered');
    }
}

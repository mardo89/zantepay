<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IcoRegistrationAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Pre-ICO User
     */
    public $email;

    /**
     * @var string Pre-ICO Currency
     */
    public $currency;

    /**
     * @var string Pre-ICO amount
     */
    public $amount;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     */
    public function __construct($mailData)
    {
        $this->email = $mailData['email'];
        $this->currency = $mailData['currency'];
        $this->amount = $mailData['amount'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('PRE-ICO Registration')
            ->view('emails.ico-registration-admin');
    }
}

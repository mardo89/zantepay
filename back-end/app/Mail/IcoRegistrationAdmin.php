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
     * @param string $email
     * @param integer $currency
     * @param integer $amount
     *
     */
    public function __construct($email, $currency, $amount)
    {
        $this->email = $email;
        $this->currency = $currency;
        $this->amount = $amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('PRE-ICO Registration')
            ->to('mardo@zantepay.com')
            ->to('lena@zantepay.com')
            ->view('emails.ico-registration-admin');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IcoRegistration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string User email
     */
    public $email;

    /**
     * @var string Currency
     */
    public $currency;

    /**
     * @var float Amount
     */
    public $amount;

    /**
     * Create a new message instance.
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
            ->from('info@zantepay.com')
            ->replyTo($this->email)->view('emails.ico-registration');
    }
}

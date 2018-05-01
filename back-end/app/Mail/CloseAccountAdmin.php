<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CloseAccountAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Pre-ICO User
     */
    public $email;


    /**
     * Create a new message instance.
     *
     * @param array $mailData
     */
    public function __construct($mailData)
    {
        $this->email = $mailData['email'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Close Account')
            ->view('emails.close-account-admin');
    }
}

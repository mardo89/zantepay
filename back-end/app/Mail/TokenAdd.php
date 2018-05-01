<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TokenAdd extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Link to the main page
     */
    public $loginLink;

    /**
     * @var string Referral link
     */
    public $znxAmount;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->loginLink = action('IndexController@main');
        $this->znxAmount = $mailData['znxAmount'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tokens Added')
            ->view('emails.token-added');
    }
}

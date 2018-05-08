<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TokenSale extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string ZNX amount
     */
    public $znxAmount;

    /**
     * @var string ETH amount
     */
    public $ethAmount;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->znxAmount = $mailData['znxAmount'];
        $this->ethAmount = $mailData['ethAmount'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Token Sale')
            ->view('emails.token-sale');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProtectAction extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Alert event
     */
    public $event;

    /**
     * @var string Alert message
     */
    public $signature;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->signature = $mailData['signature'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Protection Action')
            ->view('emails.protect-action');
    }
}

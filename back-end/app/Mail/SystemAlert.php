<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SystemAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string Alert event
     */
    public $event;

    /**
     * @var string Alert message
     */
    public $alertMessage;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->event = $mailData['event'];
        $this->alertMessage = $mailData['message'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->event)
            ->view('emails.system-alert');

    }
}

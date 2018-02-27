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
     * @return void
     */
    public function __construct($event, $message)
    {
        $this->event = $event;
        $this->alertMessage = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->event)
            ->from(env('CONTACT_EMAIL'))
            ->to(env('SERVICE_EMAIL'))
            ->view('emails.system-alert');

    }
}

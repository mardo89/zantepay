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
     * @var string Pre order link
     */
    public $link;

    /**
     * Create a new message instance.
     *
     * @param string $link
     *
     */
    public function __construct($link)
    {
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('PRE-ICO Registration')
            ->view('emails.ico-registration');
    }
}

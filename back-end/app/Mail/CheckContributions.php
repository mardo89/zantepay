<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckContributions extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array Incorrect Contributions List
     */
    public $contributionsList;

    /**
     * Create a new message instance.
     *
     * @param array $contributions
     *
     * @return void
     */
    public function __construct($contributions)
    {
        $this->contributionsList = $contributions;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Contributions Checking System')
            ->to(env('SERVICE_EMAIL'))
            ->view('emails.check-contributions');
    }
}

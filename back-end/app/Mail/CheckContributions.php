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
     * @param array $mailData
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->contributionsList = $mailData['contributions'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Contributions Checking System')
            ->view('emails.check-contributions');
    }
}

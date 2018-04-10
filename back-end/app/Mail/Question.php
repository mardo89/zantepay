<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Question extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string User name
     */
    public $userName;

    /**
     * @var string User email
     */
    public $userEmail;

    /**
     * @var string User question
     */
    public $userQuestion;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     */
    public function __construct($mailData)
    {
        $this->subject = $mailData['subject'];
        $this->userName = $mailData['name'];
        $this->userEmail = $mailData['email'];
        $this->userQuestion = $mailData['question'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.question');
    }
}

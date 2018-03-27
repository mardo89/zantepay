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
     * @param string $subject
     * @param string $userName
     * @param string $userEmail
     * @param string $userQuestion
     *
     */
    public function __construct($subject, $userName, $userEmail, $userQuestion)
    {
        $this->subject = $subject;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userQuestion = $userQuestion;
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

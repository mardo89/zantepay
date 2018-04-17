<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
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
     * @var string User message
     */
    public $userMessage;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     */
    public function __construct($mailData)
    {
       $this->userName = $mailData['name'];
       $this->userEmail = $mailData['email'];
       $this->userMessage = $mailData['message'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Contact Us')->view('emails.contact-us');
    }
}

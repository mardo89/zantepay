<?php

namespace App\Models\Services;


use App\Jobs\ProcessMailEvent;
use App\Mail\ContactUs;
use App\Models\DB\MailEvent;

class MailService
{
    /**
     * @var array User Statuses
     */
    public static $eventsList = [
        MailEvent::EVENT_TYPE_CONTACT_US => [
            'name' => 'Contact Us',
            'mailClass' => 'ContactUs'
        ],
        MailEvent::EVENT_TYPE_ASK_QUESTION => [
            'name' => 'FAQ',
            'mailClass' => 'Question'
        ],
        MailEvent::EVENT_TYPE_ACTIVATE_ACCOUNT => [
            'name' => 'Activate Account',
            'mailClass' => 'ActivateAccount'
        ],
        MailEvent::EVENT_TYPE_RESET_PASSWORD => [
            'name' => 'Reset Password',
            'mailClass' => 'ResetPassword'
        ],
        MailEvent::EVENT_TYPE_CHANGE_PASSWORD => [
            'name' => 'Change Password',
            'mailClass' => 'ChangePassword'
        ],
    ];


    /**
     * Send Activate Account email
     *
     * @param string $email
     * @param string $name
     * @param string $message
     *
     * @throws
     */
    public static function sendContactUsEmail($email, $name, $message)
    {
        $event = MailEvent::EVENT_TYPE_CONTACT_US;
        $to = env('CONTACT_EMAIL');
        $data = [
            'email' => $email,
            'name' => $name,
            'message' => $message,
        ];

        self::send($event, $to, $data);
    }

    /**
     * Send Activate Account email
     *
     * @param string $email
     * @param string $name
     * @param string $question
     * @param string $subject
     *
     * @throws
     */
    public static function sendQuestionEmail($email, $name, $question, $subject)
    {
        $event = MailEvent::EVENT_TYPE_ASK_QUESTION;
        $to = env('CONTACT_EMAIL');
        $data = [
            'email' => $email,
            'name' => $name,
            'question' => $question,
            'subject' => $subject,
        ];

        self::send($event, $to, $data);
    }


    /**
     * Send Activate Account email
     *
     * @param string $email
     * @param string $uid
     *
     */
    public static function sendActivateAccountEmail($email, $uid)
    {
        $event = MailEvent::EVENT_TYPE_ACTIVATE_ACCOUNT;
        $to = $email;
        $data = [
            'uid' => $uid,
        ];

        self::send($event, $to, $data);
    }

    /**
     * Send Reset Password email
     *
     * @param string $email
     * @param string $resetToken
     *
     */
    public static function sendResetPasswordEmail($email, $resetToken)
    {
        $event = MailEvent::EVENT_TYPE_RESET_PASSWORD;
        $to = $email;
        $data = [
            'resetToken' => $resetToken,
        ];

        self::send($event, $to, $data);
    }

    /**
     * Send Reset Password email
     *
     * @param string $email
     * @param string $resetToken
     *
     */
    public static function sendChangePasswordEmail($email)
    {
        $event = MailEvent::EVENT_TYPE_CHANGE_PASSWORD;
        $to = $email;
        $data = [];

        self::send($event, $to, $data);
    }


    /**
     * Get mail object
     *
     * @param MailEvent $event
     *
     * @return object
     * @throws
     */
    public static function getMailObj($event)
    {
        $eventInfo = self::$eventsList[$event->event_type];

        $mailClass = 'App\\Mail\\' . $eventInfo['mailClass'];

        return new $mailClass($event->mail_data);
    }

    /**
     * Send email object using Queue
     *
     * @param string $event
     * @param string $to
     * @param array $data
     *
     */
    private static function send($event, $to, $data)
    {
        $mailEvent = self::createEvent(
            $event,
            $to,
            $data
        );

        ProcessMailEvent::dispatch($mailEvent->id);
    }

    /**
     * Create Mail Event
     *
     * @param string $event
     * @param string $to
     * @param array $mailData
     *
     * @return MailEvent
     */
    private static function createEvent($event, $to, $mailData)
    {

        return MailEvent::create(
            [
                'event_type' => $event,
                'mail_to' => $to,
                'mail_data' => $mailData,
                'status' => MailEvent::EVENT_STATUS_IN_PROGRESS,
                'error' => ''
            ]
        );

    }

}

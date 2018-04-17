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
        MailEvent::EVENT_TYPE_REGISTER_FOR_ICO => [
            'name' => 'Register for PRE-ICO',
            'mailClass' => 'IcoRegistrationMail'
        ],
        MailEvent::EVENT_TYPE_REGISTER_FOR_ICO_ADMIN => [
            'name' => 'Register for PRE-ICO Admin',
            'mailClass' => 'IcoRegistrationAdminMail'
        ],
        MailEvent::EVENT_TYPE_APPROVE_DOCUMENTS => [
            'name' => 'Approve Documents',
            'mailClass' => 'ApproveDocuments'
        ],
        MailEvent::EVENT_TYPE_WELCOME => [
            'name' => 'Welcome',
            'mailClass' => 'Welcome'
        ],
        MailEvent::EVENT_TYPE_INVITE_FRIEND => [
            'name' => 'Invite Friend',
            'mailClass' => 'InviteFriend'
        ],
        MailEvent::EVENT_TYPE_ORDER_DEBIT_CARD => [
            'name' => 'Pre-order Debit Card',
            'mailClass' => 'DebitCardPreOrder'
        ],
        MailEvent::EVENT_TYPE_SYSTEM_ALERT => [
            'name' => 'System Alert',
            'mailClass' => 'SystemAlert'
        ],
        MailEvent::EVENT_TYPE_CHECK_CONTRIBUTIONS => [
            'name' => 'Check Contributions Alert',
            'mailClass' => 'CheckContributionMail'
        ],
    ];

    /**
     * @var array Event Statuses
     */
    public static $eventStatuses = [
        MailEvent::EVENT_STATUS_IN_PROGRESS => 'In-Progress',
        MailEvent::EVENT_STATUS_SENT => 'Sent',
        MailEvent::EVENT_STATUS_FAILED => 'Failed',
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
     * Send Change Password email
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
     * Send Register for Pre-ICO email
     *
     * @param string $email
     */
    public static function sendIcoRegistrationEmail($email)
    {
        $event = MailEvent::EVENT_TYPE_REGISTER_FOR_ICO;
        $to = $email;
        $data = [];

        self::send($event, $to, $data);
    }

    /**
     * Send Register for Pre-ICO Admin email
     *
     * @param string $email
     * @param int $currency
     * @param float $amount
     */
    public static function sendIcoRegistrationAdminEmail($email, $currency, $amount)
    {
        $event = MailEvent::EVENT_TYPE_REGISTER_FOR_ICO_ADMIN;
        $to = [
            'mardo@zantepay.com',
            'lena@zantepay.com'
        ];
        $data = [
            'email' => $email,
            'currency' => $currency,
            'amount' => $amount
        ];

        foreach ($to as $toAddress) {
            self::send($event, $toAddress, $data);
        }
    }

    /**
     * Send Approve Documents email
     *
     * @param string $email
     */
    public static function sendApproveDocumentsEmail($email)
    {
        $event = MailEvent::EVENT_TYPE_APPROVE_DOCUMENTS;
        $to = $email;
        $data = [];

        self::send($event, $to, $data);
    }

    /**
     * Send Welcome email
     *
     * @param string $email
     */
    public static function sendWelcomeEmail($email)
    {
        $event = MailEvent::EVENT_TYPE_WELCOME;
        $to = $email;
        $data = [];

        self::send($event, $to, $data);
    }

    /**
     * Send Invite Friend email
     *
     * @param string $email
     * @param string $uid
     */
    public static function sendInviteFriendEmail($email, $uid)
    {
        $event = MailEvent::EVENT_TYPE_INVITE_FRIEND;
        $to = $email;
        $data = [
            'uid' => $uid,
        ];

        self::send($event, $to, $data);
    }

    /**
     * Send Order Debit Card email
     *
     * @param string $email
     * @param string $uid
     * @param int $design
     */
    public static function sendOrderDebitCardEmail($email, $uid, $design)
    {
        $event = MailEvent::EVENT_TYPE_ORDER_DEBIT_CARD;
        $to = $email;
        $data = [
            'uid' => $uid,
            'design' => $design
        ];

        self::send($event, $to, $data);
    }

    /**
     * Send System Alert email
     *
     * @param string $systemEvent
     * @param string $systemMessage
     */
    public static function sendSystemAlertEmail($systemEvent, $systemMessage)
    {
        $event = MailEvent::EVENT_TYPE_SYSTEM_ALERT;
        $to = env('SERVICE_EMAIL');
        $data = [
            'event' => $systemEvent,
            'message' => $systemMessage
        ];

        self::send($event, $to, $data);
    }

    /**
     * Send Check Contributions email
     *
     * @param array $contributionsList
     */
    public static function sendCheckContributionsEmail($contributionsList)
    {
        $event = MailEvent::EVENT_TYPE_CHECK_CONTRIBUTIONS;
        $to = env('SERVICE_EMAIL');
        $data = [
            'contributions' => $contributionsList
        ];

        self::send($event, $to, $data);
    }

    /**
     * Get list of event types
     */
    public static function getEventTypes()
    {
        $eventTypesList = [];

        foreach (self::$eventsList as $eventID => $mailEvent) {
            $eventTypesList[] = [
                'id' => $eventID,
                'name' => $mailEvent['name'],
            ];
        }

        return $eventTypesList;
    }

    /**
     * Get list of event statuses
     */
    public static function getEventStatuses()
    {
        $eventStatusesList = [];

        foreach (self::$eventStatuses as $eventID => $eventStatus) {
            $eventStatusesList[] = [
                'id' => $eventID,
                'name' => $eventStatus,
            ];
        }

        return $eventStatusesList;
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
     * Get event name
     *
     * @param int $eventType
     *
     * @return string
     */
    public static function getEventName($eventType)
    {
        $eventInfo = self::$eventsList[$eventType];

        return $eventInfo['name'] ?? '';
    }

    /**
     * Get event status
     *
     * @param int $eventStatus
     *
     * @return string
     */
    public static function getEventStatus($eventStatus)
    {
        return self::$eventStatuses[$eventStatus] ?? '';
    }

    /**
     * Check if transaction status is Sent
     *
     * @param int $eventStatus
     *
     * @return string
     */
    public static function checkEventStatus($eventStatus)
    {
        return $eventStatus == MailEvent::EVENT_STATUS_SENT;
    }

    /**
     * Check if transaction status is Sent
     *
     * @param int $eventID
     *
     */
    public static function resendEvent($eventID)
    {
        ProcessMailEvent::dispatch($eventID);
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

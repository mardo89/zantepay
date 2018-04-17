<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class MailEvent extends Model
{
    /**
     * Mail Event statuses
     */
    const EVENT_STATUS_IN_PROGRESS = 0;
    const EVENT_STATUS_SENT = 1;
    const EVENT_STATUS_FAILED = 2;

    /**
     * Mail Events
     */
    const EVENT_TYPE_CONTACT_US = 0;
    const EVENT_TYPE_ASK_QUESTION = 1;
    const EVENT_TYPE_ACTIVATE_ACCOUNT = 2;
    const EVENT_TYPE_RESET_PASSWORD = 3;
    const EVENT_TYPE_CHANGE_PASSWORD = 4;
    const EVENT_TYPE_REGISTER_FOR_ICO = 5;
    const EVENT_TYPE_REGISTER_FOR_ICO_ADMIN = 6;
    const EVENT_TYPE_APPROVE_DOCUMENTS = 7;
    const EVENT_TYPE_WELCOME = 8;
    const EVENT_TYPE_INVITE_FRIEND = 9;
    const EVENT_TYPE_ORDER_DEBIT_CARD = 10;
    const EVENT_TYPE_SYSTEM_ALERT = 11;
    const EVENT_TYPE_CHECK_CONTRIBUTIONS = 12;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_type', 'mail_to', 'mail_data', 'status', 'error'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Accessors and Mutators for mail_data field
     */
    public function setMailDataAttribute($value)
    {
        $this->attributes['mail_data'] = serialize($value);
    }

    public function getMailDataAttribute($value)
    {
        return unserialize($value);
    }

}

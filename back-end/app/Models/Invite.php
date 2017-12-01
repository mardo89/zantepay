<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    /**
     * Invitation status
     */
    const INVITATION_STATUS_PENDING = 0;
    const INVITATION_STATUS_VERIFYING = 1;
    const INVITATION_STATUS_COMPLETE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Get status
     *
     * @param int $status
     *
     * @return string
     */
    public static function getStatus($status) {
        switch ($status) {
            case self::INVITATION_STATUS_PENDING:
                return 'Invitation pending';

            case self::INVITATION_STATUS_VERIFYING:
                return 'Verification not finished';

            case self::INVITATION_STATUS_COMPLETE:
                return 'Signed up!';

            default:
                return 'Invitation pending';
        }
    }
}

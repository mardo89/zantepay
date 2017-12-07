<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * User roles
     */
    const USER_ROLE_ADMIN = 0;
    const USER_ROLE_MANAGER = 1;
    const USER_ROLE_USER = 2;

    /**
     * User statuses
     */
    const USER_STATUS_INACTIVE = 0;
    const USER_STATUS_NOT_VERIFIED = 1;
    const USER_STATUS_IDENTITY_VERIFIED = 2;
    const USER_STATUS_ADDRESS_VERIFIED = 3;
    const USER_STATUS_VERIFIED = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'uid', 'status', 'referrer',
        'first_name', 'last_name', 'phone_number','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'uid', 'password', 'remember_token', 'role'
    ];

    /**
     * Return status name
     *
     * @param int $status
     *
     * @return string
     */
    public static function getStatus($status) {
        switch ($status) {
            case self::USER_STATUS_INACTIVE:
                return 'IN-ACTIVE';

            case self::USER_STATUS_NOT_VERIFIED:
                return 'NOT VERIFIED';

            case self::USER_STATUS_IDENTITY_VERIFIED:
                return 'IDENTITY VERIFIED';

            case self::USER_STATUS_ADDRESS_VERIFIED:
                return 'ADDRESS VERIFIED';

            case self::USER_STATUS_VERIFIED:
                return 'VERIFIED';

            default:
                return '';
        }
    }

    /**
     * Return role name
     *
     * @param int $role
     *
     * @return string
     */
    public static function getRole($role) {
        switch ($role) {
            case self::USER_ROLE_ADMIN:
                return 'ADMIN';

            case self::USER_ROLE_MANAGER:
                return 'MANAGER';

            case self::USER_ROLE_USER:
                return 'USER';

            default:
                return '';
        }
    }

    /**
     * Get list of available user roles
     * @return array
     */
    public static function getRolesList() {
        return [
            [
                'id' => self::USER_ROLE_ADMIN,
                'name' => self::getRole(self::USER_ROLE_ADMIN)
            ],
            [
                'id' => self::USER_ROLE_MANAGER,
                'name' => self::getRole(self::USER_ROLE_MANAGER)
            ],
            [
                'id' => self::USER_ROLE_USER,
                'name' => self::getRole(self::USER_ROLE_USER)
            ]
        ];
    }

    /**
     * Get list of user referrals
     */
    public function referrals() {
        return $this->hasMany('App\Models\User', 'referrer', 'id');
    }
}

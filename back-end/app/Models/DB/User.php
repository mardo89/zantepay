<?php

namespace App\Models\DB;

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
    const USER_ROLE_SALES = 3;

    /**
     * User statuses
     */
    const USER_STATUS_INACTIVE = 0;
    const USER_STATUS_NOT_VERIFIED = 1;
    const USER_STATUS_IDENTITY_VERIFIED = 2;
    const USER_STATUS_ADDRESS_VERIFIED = 3;
    const USER_STATUS_VERIFIED = 4;
    const USER_STATUS_WITHDRAW_PENDING = 5;
    const USER_STATUS_PENDING = 6;
    const USER_STATUS_VERIFICATION_PENDING = 7;
    const USER_STATUS_CLOSED = 8;

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
                return 'In-Active';

            case self::USER_STATUS_NOT_VERIFIED:
                return 'Not Verified';

            case self::USER_STATUS_IDENTITY_VERIFIED:
                return 'Identity Verified';

            case self::USER_STATUS_ADDRESS_VERIFIED:
                return 'Address Verified';

            case self::USER_STATUS_VERIFIED:
                return 'Verified';

            case self::USER_STATUS_WITHDRAW_PENDING:
                return 'Withdraw Pending';

            case self::USER_STATUS_PENDING:
                return 'T&C Pending';

            case self::USER_STATUS_VERIFICATION_PENDING:
                return 'Documents uploaded';

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
                return 'Admin';

            case self::USER_ROLE_MANAGER:
                return 'Manager';

            case self::USER_ROLE_USER:
                return 'User';

            case self::USER_ROLE_SALES:
                return 'Sales';

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
                'id' => self::USER_ROLE_SALES,
                'name' => self::getRole(self::USER_ROLE_SALES)
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
        return $this->hasMany('App\Models\DB\User', 'referrer', 'id');
    }

    /**
     * Get user's Wallet
     */
    public function wallet() {
        return $this->hasOne('App\Models\DB\Wallet', 'user_id', 'id');
    }

    /**
     * Get user's Profile
     */
    public function profile() {
        return $this->hasOne('App\Models\DB\Profile', 'user_id', 'id');
    }

    /**
     * Get user's Verification info
     */
    public function verification() {
        return $this->hasOne('App\Models\DB\Verification', 'user_id', 'id');
    }

    /**
     * Get list of user transfer transactions
     */
    public function transferTransactions() {
        return $this->hasMany('App\Models\DB\TransferTransaction', 'user_id', 'id');
    }

    /**
     * Get list of user withdraw transactions
     */
    public function withdrawTransactions() {
        return $this->hasMany('App\Models\DB\WithdrawTransaction', 'user_id', 'id');
    }

    /**
     * Check if user is deactivated
     *
     * @return boolean
     */
    public function isDisabled() {
        return $this->status == self::USER_STATUS_INACTIVE;
    }

    /**
     * Check if user is deactivated
     *
     * @return boolean
     */
    public function isClosed() {
        return $this->status == self::USER_STATUS_CLOSED;
    }

    /**
     * Check if user is Admin
     *
     * @return boolean
     */
    public function isAdmin() {
        return $this->role == self::USER_ROLE_ADMIN;
    }

    /**
     * Automaticaly bcrypt password field
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

}

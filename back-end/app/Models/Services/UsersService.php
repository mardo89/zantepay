<?php

namespace App\Models\Services;


use App\Models\DB\User;
use Illuminate\Support\Facades\Auth;

class UsersService
{
    /**
     * @var array User Roles
     */
    public static $userRoles = [
        [
            'id' => User::USER_ROLE_ADMIN,
            'name' => 'Admin'
        ],
        [
            'id' => User::USER_ROLE_MANAGER,
            'name' => 'Manager'
        ],
        [
            'id' => User::USER_ROLE_SALES,
            'name' => 'Sales'
        ],
        [
            'id' => User::USER_ROLE_USER,
            'name' => 'User'
        ]
    ];

    /**
     * @var array User Statuses
     */
    public static $userStatuses = [
        User::USER_STATUS_INACTIVE => 'In-Active',
        User::USER_STATUS_NOT_VERIFIED => 'Not Verified',
        User::USER_STATUS_IDENTITY_VERIFIED => 'Identity Verified',
        User::USER_STATUS_ADDRESS_VERIFIED => 'Address Verified',
        User::USER_STATUS_VERIFIED => 'Verified',
        User::USER_STATUS_WITHDRAW_PENDING => 'Withdraw Pending',
        User::USER_STATUS_PENDING => 'T&C Pending',
        User::USER_STATUS_VERIFICATION_PENDING => 'Documents uploaded'
    ];

    /**
     *  Get user by ID
     *
     * @param int $userID
     *
     * @return mixed
     */
    public static function getUser($userID)
    {
        return User::find($userID);
    }


    /**
     *  Get logged user
     *
     * @param int $userID
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function getActiveUser()
    {
        return Auth::user();
    }

    /**
     *  Get user referrer
     *
     * @param User $user
     *
     * @return mixed
     */
    public static function getReferrer($user)
    {
        return self::getUser($user->referrer);
    }

    /**
     *  Get user referrals
     *
     * @param User $user
     *
     * @return mixed
     */
    public static function getReferrals($user)
    {
        return $user->referrals;
    }


    /**
     * Return user role
     *
     * @param int $userRole
     *
     * @return string
     */
    public static function getUserRole($userRole)
    {
        return self::$userRoles[$userRole]['name'] ?? '';
    }

    /**
     * Return user status
     *
     * @param int $userStatus
     *
     * @return string
     */
    public static function getUserStatus($userStatus)
    {
        return self::$userStatuses[$userStatus] ?? '';
    }

    /**
     * Return user statuses
     *
     * @return array
     */
    public static function getUserStatuses()
    {
        foreach (self::$userStatuses as $statusID => $statusName) {
            return yield ['id' => $statusID, 'name' => $statusName];
        }
    }

    /**
     * Return user home page
     *
     * @param int $userRole
     *
     * @return string
     */
    public static function getUserPage($userRole)
    {
        switch ($userRole) {
            case User::USER_ROLE_ADMIN:
                return '/admin/users';

            case User::USER_ROLE_MANAGER:
                return '/admin/users';

            case User::USER_ROLE_USER:
                return '/user/profile';

            default:
                return '/';
        }
    }

}

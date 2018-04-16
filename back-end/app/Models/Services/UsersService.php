<?php

namespace App\Models\Services;


use App\Models\DB\PasswordReset;
use App\Models\DB\Profile;
use App\Models\DB\SocialNetworkAccount;
use App\Models\DB\User;
use App\Models\DB\Wallet;
use App\Models\DB\ZantecoinTransaction;
use Illuminate\Support\Facades\Auth;

class UsersService
{
    /**
     * @var array User Roles
     */
    public static $userRoles = [
        User::USER_ROLE_ADMIN => 'Admin',
        User::USER_ROLE_MANAGER => 'Manager',
        User::USER_ROLE_SALES => 'Sales',
        User::USER_ROLE_USER => 'User'
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
     * Return user roles
     *
     * @return array
     */
    public static function getUserRoles()
    {
        $userRoles = [];

        foreach (self::$userRoles as $roleID => $roleName) {
            $userRoles[] = ['id' => $roleID, 'name' => $roleName];
        }

        return $userRoles;
    }

    /**
     * Change user role
     *
     * @param mixed $user
     * @param int $userRole
     *
     * @throws
     */
    public static function changeUserRole(User $user, $userRole)
    {
        if(array_key_exists($userRole, self::$userRoles)) {
            $user->role = $userRole;
            $user->save();
        }
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
        $userStatuses = [];

        foreach (self::$userStatuses as $statusID => $statusName) {
            $userStatuses[] = ['id' => $statusID, 'name' => $statusName];
        }

        return $userStatuses;
    }

    /**
     * Change user role
     *
     * @param mixed $user
     * @param int $userStatus
     *
     * @throws
     */
    public static function changeUserStatus(User $user, $userStatus)
    {
        if(array_key_exists($userStatus, self::$userStatuses)) {
            $user->status = $userStatus;
            $user->save();
        }
    }

    /**
     * Remove user
     *
     * @param User $user
     *
     * @throws
     */
    public static function removeUser($user)
    {
        Profile::where('user_id', $user->id)->delete();
        PasswordReset::where('email', $user->email)->delete();
        SocialNetworkAccount::where('user_id', $user->id)->delete();

        InvitesService::removeInvites($user->id);
        DebitCardsService::removeDebitCard($user->id);
        DocumentsService::removeDocuments($user->id);

        ZantecoinTransaction::where('user_id', $user->id)->delete();
        Wallet::where('user_id', $user->id)->delete();

        $user->delete();
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

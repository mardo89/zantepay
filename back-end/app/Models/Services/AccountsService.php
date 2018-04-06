<?php

namespace App\Models\Services;


use App\Models\DB\PasswordReset;
use App\Models\DB\Profile;
use App\Models\DB\SocialNetworkAccount;
use App\Models\DB\Wallet;
use App\Models\DB\ZantecoinTransaction;

class AccountsService
{

    /**
     * Change user role
     *
     * @param string $userUID
     * @param int $userRole
     *
     * @throws
     */
    public static function changeAccountRole($userUID, $userRole)
    {
        if (self::isSelf($userUID)) {
            throw new \Exception('Admin user can not update role for himself');
        }

        $user = UsersService::findUserByUid($userUID);

        if (!$user) {
            throw new \Exception('User does not exist');
        }

        $user->role = $userRole;
        $user->save();
    }

    /**
     * Remove user
     *
     * @param string $userID
     *
     * @throws
     */
    public static function removeAccount($userID)
    {
        if (self::isSelf($userID)) {
            throw new \Exception('Admin user can not delete himself');
        }

        $user = UsersService::findUserByID($userID);

        if (!$user) {
            throw new \Exception('User does not exist');
        }

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
     * Change user role
     *
     * @param string $userUID
     *
     * @return mixed
     */
    protected static function isSelf($userUID)
    {
        $loggedUser = UsersService::getActiveUser();

        return $loggedUser->uid === $userUID;
    }

}

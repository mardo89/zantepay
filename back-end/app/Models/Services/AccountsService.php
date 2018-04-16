<?php

namespace App\Models\Services;

use App\Exceptions\UserAccessException;
use App\Exceptions\UserNotFoundException;
use App\Models\DB\User;
use Illuminate\Support\Facades\Auth;

class AccountsService
{

    /**
     *  Get user by ID
     *
     * @param int $userID
     *
     * @return User
     * @throws UserNotFoundException
     */
    public static function getUser($userID)
    {

        return self::findUser(
            [
                'id' => $userID
            ]
        );

    }

    /**
     *  Get user referrer
     *
     * @param string $userReferrer
     *
     * @return User
     * @throws
     */
    public static function getReferrer($userReferrer)
    {
        return self::getUser($userReferrer);
    }

    /**
     *  Get user referrals
     *
     * @param int $userID
     *
     * @return mixed
     * @throws
     */
    public static function getReferrals($userID)
    {
        return self::getUser($userID)->referrals;
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
     *  Find user
     *
     * @param array $filter
     *
     * @return mixed
     * @throws
     */
    public static function findUser($filter)
    {
        $user = User::where($filter)->first();

        if (!self::checkExists($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     *  Find users
     *
     * @param array $filter
     *
     * @return mixed
     * @throws
     */
    public static function findUserStrict($filter)
    {
        $user = self::findUser($filter);

        if (self::checkIdentity($user->id)) {
            throw new UserAccessException();
        }

        return $user;
    }

    /**
     * Check if current user is the same as logged user
     *
     * @param int $userID
     *
     * @return boolean
     */
    protected static function checkIdentity($userID)
    {
        $activeUser = self::getActiveUser();

        return $activeUser->id === $userID;
    }

    /**
     * Check if user exist
     *
     * @param mixed $user
     *
     * @return bool
     */
    protected static function checkExists($user)
    {
        return !is_null($user);
    }


}

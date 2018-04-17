<?php

namespace App\Models\Services;


use App\Exceptions\AuthException;
use App\Models\DB\ExternalRedirect;
use App\Models\DB\Profile;
use App\Models\DB\User;
use App\Models\DB\Verification;
use App\Models\DB\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthService
{

    protected static $homePages = [
        User::USER_ROLE_ADMIN => '/admin/users',
        User::USER_ROLE_MANAGER => '/admin/users',
        User::USER_ROLE_SALES => '/admin/users',
        User::USER_ROLE_USER => '/user/wallet',
    ];
    /**
     *  Register new user
     *
     * @param string $email
     * @param string $password
     *
     * @return User
     */
    public static function registerUser($email, $password)
    {
        $uid = uniqid();

        self::createUser(
            [
                'email' => $email,
                'password' => $password,
                'uid' => $uid
            ]
        );

        ExternalRedirect::addLink(
            Session::get('externalLink'),
            $email,
            ExternalRedirect::ACTION_TYPE_REGISTRATION
        );

        MailService::sendActivateAccountEmail($email, $uid);

        return $uid;
    }

    /**
     *  Login user
     *
     * @param string $email
     * @param string $password
     *
     * @throws
     */
    public static function loginUser($email, $password)
    {
        $isAuthorized = Auth::attempt(
            [
                'email' => $email,
                'password' => $password,
            ]
        );

        if (!$isAuthorized) {
            throw new  AuthException('Login or password incorrect');
        }

        $activeUser = AccountsService::getActiveUser();

        if (optional($activeUser)->isDisabled()) {
            throw new AuthException('Your account is disabled');
        }
    }

    /**
     *  Logout user
     *
     * @throws
     */
    public static function logoutUser()
    {
        Auth::logout();
    }

    /**
     * Check password with existing hash
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public static function checkPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Create user with profile, wallet, documents
     *
     * @param array $userInfo
     *
     * @return User
     */
    protected static function createUser($userInfo)
    {
        $referrer = Session::get('referrer');

        if (!is_null($referrer)) {
            $userInfo['referrer'] = $referrer;
        }

        $user = User::create($userInfo);

        Profile::create(
            [
                'user_id' => $user['id']
            ]
        );

        Verification::create(
            [
                'user_id' => $user['id']
            ]
        );

        Wallet::create(
            [
                'user_id' => $user['id']
            ]
        );

        Session::forget('referrer');

        return $user;
    }

    /**
     * Return user home page
     *
     * @param int $userRole
     *
     * @return string
     */
    protected function getHomePage($userRole)
    {
        return self::$homePages[$userRole] ?? '/';
    }

}

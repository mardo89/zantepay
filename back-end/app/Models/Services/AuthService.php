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
     *  Login user
     *
     * @param string $email
     * @param string $password
     *
     * @return string
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
            throw new AuthException('Your email account is not confirmed yet. Check your inbox/spam folder to confirm your account.');
        }

        if (optional($activeUser)->isClosed()) {
            throw new AuthException('Your account is closed');
        }

        self::updateAuthToken($activeUser->id, $activeUser->email, $activeUser->password);

        return self::getHomePage($activeUser->role);
    }

    /**
     *  Login user with Facebook
     *
     * @return string
     * @throws
     */
    public static function loginWithFacebook()
    {
        $userID = AccountsService::registerFacebookUser();

        $isAuthorized = Auth::loginUsingId($userID);

        if (!$isAuthorized) {
            throw new AuthException('Authentification failed.');
        }

        $activeUser = AccountsService::getActiveUser();

        self::updateAuthToken($activeUser->id, $activeUser->email, $activeUser->password);

        return self::getHomePage($activeUser->role);
    }

    /**
     *  Login user with Google
     *
     * @return string
     * @throws
     */
    public static function loginWithGoogle()
    {
        $userID = AccountsService::registerGoogleUser();

        $isAuthorized = Auth::loginUsingId($userID);

        if (!$isAuthorized) {
            throw new AuthException('Authentification failed.');
        }

        $activeUser = AccountsService::getActiveUser();

        self::updateAuthToken($activeUser->id, $activeUser->email, $activeUser->password);

        return self::getHomePage($activeUser->role);
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
     * Update security token
     *
     * @param User $user
     */
    public static function updateAuthToken($userID, $userEmail, $userPassword)
    {
        Session::put('auth_token', bcrypt($userEmail . $userID . $userPassword));
    }

    /**
     * Return user home page
     *
     * @param int $userRole
     *
     * @return string
     */
    protected static function getHomePage($userRole)
    {
        return self::$homePages[$userRole] ?? '/';
    }

}

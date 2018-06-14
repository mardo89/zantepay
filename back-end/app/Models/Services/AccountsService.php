<?php

namespace App\Models\Services;

use App\Exceptions\AuthException;
use App\Exceptions\PasswordException;
use App\Exceptions\UserAccessException;
use App\Exceptions\UserNotFoundException;
use App\Models\DB\Affiliate;
use App\Models\DB\ExternalRedirect;
use App\Models\DB\SocialNetworkAccount;
use App\Models\DB\User;
use App\Models\DB\Verification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AccountsService
{
    /**
     *  Register new user
     *
     * @param string $email
     * @param string $password
     *
     * @return User
     * @throws
     */
    public static function registerUser($email, $password)
    {

        $registeredAt = Session::get('registered_at', time());

        $registrationPeriod = time() - $registeredAt;

        if ($registrationPeriod > 0 && $registrationPeriod < 300) {
            throw new AuthException('There has been a registration from your computer recently. Please try again in 5 minutes.');
        }

        $uid = uniqid();

        self::createUser(
            [
                'email' => $email,
                'password' => $password,
                'uid' => $uid
            ]
        );

        RedirectsService::trackRedirect(
            $email,
            ExternalRedirect::ACTION_TYPE_REGISTRATION
        );

        MailService::sendActivateAccountEmail($email, $uid);

        Session::put('registered_at', time());

        return $uid;
    }

    /**
     *  Register Facebook user
     *
     * @return User
     */
    public static function registerFacebookUser()
    {
        $snUser = Socialite::driver('facebook')->user();

        $snID = SocialNetworkAccount::SOCIAL_NETWORK_FACEBOOK;
        $userToken = $snUser->getId();
        $userNameParts = explode(' ', $snUser->getName());

        $snAccount = SocialNetworkAccountsService::findSocialNetworkAccount($userToken, $snID);

        if (!$snAccount) {

            $userInfo = User::where('email', $snUser->email)->first();

            if (!$userInfo) {

                // register a new User
                $userInfo = self::createUser(
                    [
                        'email' => $snUser->email,
                        'password' => uniqid(),
                        'uid' => uniqid(),
                        'status' => User::USER_STATUS_PENDING,
                        'first_name' => $userNameParts[1] ?? "",
                        'last_name' => $userNameParts[0] ?? "",
                        'avatar' => $snUser->avatar,
                    ]
                );

            }

            SocialNetworkAccountsService::createSocialNetworkAccount($snID, $userToken, $userInfo->id);

            $userID = $userInfo->id;

        } else {

            $userID = $snAccount->user->id;

        }

        return $userID;
    }

    /**
     *  Register Google user
     *
     * @return User
     */
    public static function registerGoogleUser()
    {
        $snUser = Socialite::driver('google')->user();

        $snID = SocialNetworkAccount::SOCIAL_NETWORK_GOOGLE;
        $userToken = $snUser->getId();
        $userNameParts = explode(' ', $snUser->getName());

        $snAccount = SocialNetworkAccountsService::findSocialNetworkAccount($userToken, $snID);

        if (!$snAccount) {

            $userInfo = User::where('email', $snUser->email)->first();

            if (!$userInfo) {

                // register a new User
                $userInfo = self::createUser(
                    [
                        'email' => $snUser->email,
                        'password' => uniqid(),
                        'uid' => uniqid(),
                        'status' => User::USER_STATUS_PENDING,
                        'first_name' => $userNameParts[0] ?? "",
                        'last_name' => $userNameParts[1] ?? "",
                        'avatar' => $snUser->avatar,
                    ]
                );

            }

            SocialNetworkAccountsService::createSocialNetworkAccount($snID, $userToken, $userInfo->id);

            $userID = $userInfo->id;

        } else {

            $userID = $snAccount->user->id;

        }

        return $userID;
    }

    /**
     * Remove user
     *
     * @param int $userUID
     *
     * @throws
     */
    public static function removeUser($userUID)
    {
        $user = self::getUserByID($userUID);

        WalletsService::removeWallet($user->id);
        TransactionsService::removeTransactions($user->id);
        ProfilesService::removeProfile($user->id);
        InvitesService::removeInvites($user->id);
        DebitCardsService::removeDebitCard($user->id);
        VerificationService::removeVerification($user->id);
        SocialNetworkAccountsService::removeSocialNetworkAccount($user->id);
        ResetPasswordsService::removePasswordReset($user->email);

        $user->delete();
    }


    /**
     *  Activate user's account
     *
     * @param string $userUID
     *
     * @throws
     */
    public static function activateAccount($userUID)
    {
        $user = AccountsService::getUserByID($userUID);

        if (!$user->isDisabled()) {
            throw new AuthException('Your account is already activated.');
        }

        UsersService::changeUserStatus($user, User::USER_STATUS_PENDING);
    }

    /**
     *  Accept terms and conditions
     *
     * @param bool $toNewsletters
     */
    public static function acceptTerms($toNewsletters)
    {
        $user = self::getActiveUser();

        UsersService::changeUserStatus($user, User::USER_STATUS_NOT_VERIFIED);

        MailService::sendWelcomeEmail($user->email);

        if ($toNewsletters === true) {
            RegistrationsService::joinToNewsLetter($user->email);
        }
    }

    /**
     * Close Account
     *
     * @throws
     */
    public static function closeAccount()
    {
        $user = self::getActiveUser();

        UsersService::changeUserStatus($user, User::USER_STATUS_CLOSED);

        MailService::sendCloseAccountAdminEmail($user->email);

        AuthService::logoutUser();
    }

    /**
     * Reset users password
     *
     * @param string email
     *
     * @throws
     */
    public static function resetPassword($email)
    {
        $user = self::getUserByEmail($email);

        if (!self::checkExists($user)) {
            return;
        }

        $resetInfo = ResetPasswordsService::createPasswordReset($email);

        MailService::sendResetPasswordEmail($resetInfo->email, $resetInfo->token);
    }

    /**
     * Save password after reset
     *
     * @param string $resetToken
     * @param string $password
     *
     * @throws
     */
    public static function savePassword($resetToken, $password)
    {
        $resetInfo = ResetPasswordsService::checkPasswordReset($resetToken);

        $user = self::findUser(
            [
                'email' => $resetInfo->email
            ]
        );

        UsersService::changeUserPassword($user, $password);

        ResetPasswordsService::removePasswordReset($user->email);

        MailService::sendChangePasswordEmail($user->email);

        AuthService::updateAuthToken($user->id, $user->email, $user->password);
    }

    /**
     * Save password after reset
     *
     * @param string $currentPassword
     * @param string $newPassword
     *
     * @throws
     */
    public static function changePassword($currentPassword, $newPassword)
    {
        $user = self::getActiveUser();

        if (!self::checkPassword($currentPassword, $user->password)) {
            throw new PasswordException('Current Password is wrong');
        }

        UsersService::changeUserPassword($user, $newPassword);

        AuthService::updateAuthToken($user->id, $user->email, $user->password);
    }

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
     *  Get user by UID
     *
     * @param string $userUID
     *
     * @return User
     * @throws UserNotFoundException
     */
    public static function getUserByID($userUID)
    {

        return self::findUser(
            [
                'uid' => $userUID
            ]
        );

    }

    /**
     *  Get user by Email
     *
     * @param string $userEmail
     *
     * @return User
     * @throws UserNotFoundException
     */
    public static function getUserByEmail($userEmail)
    {

        try {

            $user = self::findUser(
                [
                    'email' => $userEmail
                ]
            );

        } catch (\Exception $e) {

            return null;

        }

        return $user;
    }

    /**
     *  Get user referrer
     *
     * @param string $userReferrer
     *
     * @return User|null
     * @throws
     */
    public static function getReferrer($userReferrer)
    {
        try {

            $user = self::getUser($userReferrer);

        } catch (\Exception $e) {

            return null;

        }

        return $user;
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
     * Set referrer to Session
     *
     * @param string $refToken
     */
    public static function setReferrer($refToken)
    {
        if (is_null($refToken)) {
            return;
        }

        $user = User::where('uid', $refToken)->first();

        if (self::checkExists($user)) {
            Session::put('referrer', $user->id);
        }
    }

    /**
     * Set external link to Session
     */
    public static function setExternals()
    {
        $externalLink = $_SERVER['HTTP_REFERER'] ?? '';

        Session::put('externalLink', $externalLink);
    }

	/**
	 * Track affiliate
	 *
	 * @param string $trackId
	 */
	public static function trackAffiliate($trackId)
	{
		if (is_null($trackId)) {
			return;
		}

		Affiliate::create(
			[
				'track_id' => $trackId
			]
		);

		AffiliatesService::registerAffiliate($trackId);
	}

    /**
     * Check if current user is the same as logged user
     *
     * @param int $userID
     *
     * @return boolean
     */
    public static function checkIdentity($userID)
    {
        $activeUser = self::getActiveUser();

        return $activeUser->id === $userID;
    }

    /**
     * Get info about user's account
     *
     * @param string $userUID
     *
     * @return array
     * @throws
     */
    public static function getInfo($userUID)
    {
        $user = self::getUserByID($userUID);

        $profile = ProfilesService::getProfileInfo($user);

        $verificationStatus = VerificationService::verificationStatus($user->verification);
        $verificationPending = VerificationService::verificationInProgress($user->verification);

        $referrer = self::getReferrer($user->referrer);
        $referrerEmail = is_null($referrer) ? (is_null($user->referrer) ? '' : 'User deleted') : $referrer->email;

        $debitCardDesign = DebitCardsService::getDebitCardDesign($user->id);
        $debitCard = [
            'isWhite' => DebitCardsService::isDebitCardWhile($debitCardDesign),
            'isRed' => DebitCardsService::isDebitCardRed($debitCardDesign),
        ];

        $wallet = $user->wallet;

        $rolesList = UsersService::getUserRoles();

        $allowEdit = !self::checkIdentity($user->id);

        return [
            'user' => $user,
            'profile' => $profile,
            'verificationStatus' => $verificationStatus,
            'verificationPending' => $verificationPending,
            'referrer' => $referrerEmail,
            'debitCard' => $debitCard,
            'wallet' => $wallet,
            'userRoles' => $rolesList,
            'allowEdit' => $allowEdit
        ];
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

        ProfilesService::createProfile($user->id);

        VerificationService::createVerification($user->id);

        WalletsService::createWallet($user->id);

        Session::forget('referrer');

        return $user;
    }

    /**
     * Check password with existing hash
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    protected static function checkPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

}

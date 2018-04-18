<?php

namespace App\Models\Services;

use App\Exceptions\ResetPasswordException;
use App\Models\DB\PasswordReset;
use Illuminate\Support\Facades\DB;

class ResetPasswordsService
{
    /**
     * Create user's Password Resets
     *
     * @param string $userEmail
     *
     * @return PasswordReset
     */
    public static function createPasswordReset($userEmail)
    {
        $resetToken = uniqid();

        PasswordReset::create(
            [
                'email' => $userEmail,
                'token' => $resetToken
            ]
        );

        MailService::sendResetPasswordEmail($userEmail, $resetToken);
    }

    /**
     * Check if user can reset password
     *
     * @param string $resetToken
     *
     * @return PasswordReset
     * @throws
     */
    public static function checkPasswordReset($resetToken)
    {
        // check expiration date
        $resetInfo = self::findLastReset($resetToken);

        // Check if there is no other tokens after current
        $resetEmail = $resetInfo->email;

        $lastResetInfo = PasswordReset::where('email', $resetEmail)
            ->orderBy('created_at', 'desc')
            ->first();

        if (is_null($lastResetInfo) || $lastResetInfo->token !== $resetToken) {
            throw new ResetPasswordException();
        }

        return $resetInfo;
    }


    /**
     * Remove user's Password Resets
     *
     * @param string $userEmail
     */
    public static function removePasswordReset($userEmail)
    {
        PasswordReset::where('email', $userEmail)->delete();
    }

    /**
     * Find last user's Password Resets
     *
     * @param string $resetToken
     *
     * @return PasswordReset
     * @throws
     */
    public static function findLastReset($resetToken)
    {
        $resetInfo = PasswordReset::where('token', $resetToken)
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 15 MINUTE)'))
            ->first();

        if (!$resetInfo) {
            throw new ResetPasswordException();
        }

        return $resetInfo;
    }

}

<?php

namespace App\Models\Services;

use App\Exceptions\PasswordException;
use App\Models\DB\PasswordReset;

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
            throw new PasswordException('Error restoring password');
        }

        return $resetInfo;
    }

}

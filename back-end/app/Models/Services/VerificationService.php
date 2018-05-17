<?php

namespace App\Models\Services;


use App\Exceptions\VerificationException;
use App\Models\DB\Verification;

class VerificationService
{

    /**
     * @var array Verification Statuses
     */
    public static $verificationStatuses = [
        Verification::VERIFICATION_PENDING => 'Not Verified',
        Verification::VERIFICATION_IN_PROGRESS => 'Verification In-Progress',
        Verification::VERIFICATION_SUCCESS => 'Verified',
        Verification::VERIFICATION_FAILED => 'Verification Failed',
    ];

    /**
     * Create Verification
     *
     * @param int $userID
     */
    public static function createVerification($userID)
    {
        Verification::create(
            [
                'user_id' => $userID
            ]
        );
    }

    /**
     * Track information about verification request
     *
     * @param Verification $verification
     * @param string $sessionID
     */
    public static function trackVerificationRequest($verification, $sessionID)
    {
        $verification->session_id = $sessionID;
        $verification->status = Verification::VERIFICATION_IN_PROGRESS;
        $verification->save();
    }

    /**
     * Track information about verification response
     *
     * @param string $status
     * @param array $apiResponse
     *
     * @throws
     */
    public static function trackVerificationResponse($status, $apiResponse)
    {
        if ($status != 'success') {
            throw new VerificationException('Verification failure');
        }

        $verification = Verification::where('session_id', $apiResponse['session_id'])->first();

        if (!$verification) {
            throw new VerificationException('Unknown session id');
        }

        /**
         * check signature
         */

        $verification->response_status = $apiResponse['response_status'];
        $verification->response_code = $apiResponse['response_code'];

        if ($apiResponse['response_code'] == '9001') {
            $verification->status = Verification::VERIFICATION_SUCCESS;
        } else {
            $verification->status = Verification::VERIFICATION_FAILED;
            $verification->fail_reason = $apiResponse['fail_reason'];
        }

        $verification->save();

        if (self::verificationComplete($verification)) {

            $user = $verification->user;

            UsersService::changeUserStatus($user, User::USER_STATUS_VERIFIED);

//            BonusesService::updateBonus($user);

            MailService::sendApproveDocumentsEmail($user->email);

        } else {
            /**
             * Send email about fail
             */
        }
    }

    /**
     * Check if documents verification is complete
     *
     * @param Verification $verification
     *
     * @return boolean
     */
    public static function verificationComplete($verification)
    {
        return $verification->status == Verification::VERIFICATION_SUCCESS;
    }

    /**
     * Check if user need verification
     *
     * @param Verification $verification
     *
     * @return boolean
     */
    public static function verificationPending($verification)
    {
        $isPending = $verification->status == Verification::VERIFICATION_PENDING;
        $isFailed = $verification->status == Verification::VERIFICATION_FAILED;

        return ($isPending || $isFailed);
    }

    /**
     * Get user's verification status
     *
     * @param int $verificationStatus
     *
     * @return string
     */
    protected static function getVerificationStatus($verificationStatus)
    {
        return self::$verificationStatuses[$verificationStatus] ?? '';
    }

}

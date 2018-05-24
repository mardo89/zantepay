<?php

namespace App\Models\Services;


use App\Exceptions\VerificationException;
use App\Models\DB\User;
use App\Models\DB\Verification;

class VerificationService
{

    /**
     * @var array Verification Statuses
     */
    public static $verificationStatuses = [
        Verification::VERIFICATION_PENDING => 'Not verified',
        Verification::VERIFICATION_IN_PROGRESS => 'Verification in progress',
        Verification::VERIFICATION_SUCCESS => 'Verified',
        Verification::VERIFICATION_FAILED => 'Verification failed',
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
     * Remove user's Verification
     *
     * @param int $userID
     */
    public static function removeVerification($userID)
    {
        Verification::where('user_id', $userID)->delete();
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
        $verification->response_status = '';
        $verification->response_code = '';
        $verification->fail_reason = '';
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
        try {

            if ($status != 'success') {
                throw new VerificationException('Verification failure');
            }

            $verification = Verification::where('session_id', $apiResponse['session_id'])->first();

            if (!$verification) {
                throw new VerificationException('Unknown session id');
            }

            $verification->response_status = $apiResponse['response_status'];
            $verification->response_code = $apiResponse['response_code'];

            if ($apiResponse['response_code'] == '9001') {
                $verification->status = Verification::VERIFICATION_SUCCESS;
            } else {
                $verification->status = Verification::VERIFICATION_FAILED;
                $verification->fail_reason = $apiResponse['fail_reason'];
            }

            $verification->save();

            $user = $verification->user;

            if (self::verificationComplete($verification)) {

                UsersService::changeUserStatus($user, User::USER_STATUS_VERIFIED);

                MailService::sendAccountApprovedEmail($user->email);

            } else {

                MailService::sendAccountNotApprovedEmail($user->email);

            }


        } catch (\Exception $e) {

            MailService::sendAccountVerificationAdminEmail($status, $apiResponse, $e->getMessage());

            throw new VerificationException('Verification failure');

        }
    }

    /**
     * Track information about verification response
     *
     * @param User $user
     *
     * @return string
     * @throws
     */
    public static function resetVerification($user)
    {
        $verification = $user->verification;

        $verification->session_id = '';
        $verification->status = Verification::VERIFICATION_PENDING;
        $verification->response_status = '';
        $verification->response_code = '';
        $verification->fail_reason = '';
        $verification->save();

        MailService::sendAccountNotApprovedEmail($user->email);

        return self::getVerificationStatus($verification->status);
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
     * Check if user need verification
     *
     * @param Verification $verification
     *
     * @return boolean
     */
    public static function verificationInProgress($verification)
    {
        return $verification->status == Verification::VERIFICATION_IN_PROGRESS;
    }

    /**
     * Get verification status
     *
     * @param Verification $verification
     *
     * @return boolean
     */
    public static function verificationStatus($verification)
    {
        $statusName = self::getVerificationStatus($verification->status);

        if ($verification->status === Verification::VERIFICATION_FAILED) {
            $statusName .= ' - ' . $verification->fail_reason;
        }

        return $statusName;
    }

    /**
     * Get list of verification statuses
     */
    public static function getVerificationStatuses()
    {
        $verificationStatusesList = [];

        foreach (self::$verificationStatuses as $statusID => $statusName) {
            $verificationStatusesList[] = [
                'id' => $statusID,
                'name' => $statusName,
            ];
        }

        return $verificationStatusesList;
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

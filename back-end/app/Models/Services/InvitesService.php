<?php

namespace App\Models\Services;


use App\Models\DB\Invite;
use App\Models\DB\User;

class InvitesService
{
    /**
     * @var array User Statuses
     */
    public static $invitationStatuses = [
        Invite::INVITATION_STATUS_PENDING => 'Invitation pending',
        Invite::INVITATION_STATUS_VERIFYING => 'Verification not finished',
        Invite::INVITATION_STATUS_COMPLETE => 'Signed up!'
    ];

    /**
     *  Get invited users list
     *
     * @param User $user
     *
     * @return array
     */
    public static function getInvitedUsers($user)
    {
        $invitedUsers = [];

        $invites = self::getInvites($user);

        foreach ($invites as $invite) {
            $invitedUsers[$invite->email] = [
                'name' => $invite->email,
                'email' => $invite->email,
                'avatar' => '/images/avatar.png',
                'status' => self::getInvitationStatus(Invite::INVITATION_STATUS_PENDING),
                'bonus' => '',
                'commission' => ''
            ];
        }

        $referrals = $user->referrals;

        foreach ($referrals as $referral) {
            $hidePos = strrpos($referral->email, "@");

            if ($hidePos <= 2) {
                $replacement = str_repeat('*', $hidePos);

                $hiddenEmail = substr_replace($referral->email, $replacement, 0, $hidePos);
            } elseif ($hidePos <= 5) {
                $replacement = str_repeat('*', $hidePos - 1);

                $hiddenEmail = substr_replace($referral->email, $replacement, 1, $hidePos - 1);
            } else {
                $replacement = str_repeat('*', $hidePos - 2);

                $hiddenEmail = substr_replace($referral->email, $replacement, 3, $hidePos - 2);
            }

            $userName = ($referral->first_name != '' && $referral->last_name != '')
                ? $referral->first_name . ' ' . $referral->last_name
                : $hiddenEmail;


            $inviteStatus = Invite::INVITATION_STATUS_VERIFYING;

            if (VerificationService::verificationComplete($referral->verification)) {
                $inviteStatus = Invite::INVITATION_STATUS_COMPLETE;
            }

            $invitedUsers[$referral->email] = [
                'name' => $userName,
                'email' => $referral->email,
                'avatar' => !is_null($referral->avatar) ? $referral->avatar : '/images/avatar.png',
                'status' => self::getInvitationStatus($inviteStatus),
                'bonus' => BonusesService::getReferralBonus($referral),
                'commission' => BonusesService::getCommissionBonus($referral)
            ];
        }

        return $invitedUsers;
    }

    /**
     *  Get user's invites
     *
     * @param User $user
     *
     * @return array
     */
    public static function getInvites($user)
    {
        return Invite::where('user_id', $user->id)->get();
    }

    /**
     * Remove user's Invites
     *
     * @param int $userID
     */
    public static function removeInvites($userID)
    {
        Invite::where('user_id', $userID)->delete();
    }

    /**
     * Return user status
     *
     * @param int $invitationStatus
     *
     * @return string
     */
    public static function getInvitationStatus($invitationStatus)
    {
        return self::$invitationStatuses[$invitationStatus] ?? '';
    }

    /**
     * Create invite
     *
     * @param User $user
     * @param string $inviteEmail
     *
     * @return array
     */
    public static function createInvite($user, $inviteEmail) {
        $invite = Invite::where('user_id', $user->id)->where('email', $inviteEmail)->first();

        if (!$invite) {
            $invite = Invite::create(
                [
                    'user_id' => $user->id,
                    'email' => $inviteEmail
                ]
            );
        }

        MailService::sendInviteFriendEmail($inviteEmail, $user->uid);

        return [
            'email' => $invite['email'],
            'status' => self::getInvitationStatus($invite['status'])
        ];
    }
}

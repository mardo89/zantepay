<?php

namespace App\Models\Services;


use App\Models\DB\Invite;
use App\Models\DB\User;
use App\Models\DB\Wallet;

class InvitesService
{
    /**
     * @var array User Statuses
     */
    public static $invitationStatuses = [
        [
            'id' => Invite::INVITATION_STATUS_PENDING,
            'name' => 'Invitation pending'
        ],
        [
            'id' => Invite::INVITATION_STATUS_VERIFYING,
            'name' => 'Verification not finished'
        ],
        [
            'id' => Invite::INVITATION_STATUS_COMPLETE,
            'name' => 'Signed up!'
        ],
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

        $referrals = UsersService::getReferrals($user);

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

            $wallet = $referral->wallet;

            $inviteStatus = Invite::INVITATION_STATUS_VERIFYING;
            $referralBonus = DebitCardsService::hasDebitCard($referral) ? Wallet::REFERRAL_BONUS : 0;
            $bonusStatus = '(locked - account is not verified)';

            if (DocumentsService::verificationComplete($referral)) {
                $inviteStatus = Invite::INVITATION_STATUS_COMPLETE;
                $bonusStatus = '';
            }

            $invitedUsers[$referral->email] = [
                'name' => $userName,
                'email' => $referral->email,
                'avatar' => !is_null($referral->avatar) ? $referral->avatar : '/images/avatar.png',
                'status' => self::getInvitationStatus($inviteStatus),
                'bonus' => $referralBonus > 0 ? $referralBonus . ' ' . $bonusStatus : '',
                'commission' => $wallet->commission_bonus > 0 ? $wallet->commission_bonus : ''
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
     * Return user status
     *
     * @param int $invitationStatus
     *
     * @return string
     */
    public static function getInvitationStatus($invitationStatus) {
        return self::$invitationStatuses[$invitationStatus]['name'] ?? '';
    }

}

<?php

namespace App\Models\Services;


use App\Models\DB\SocialNetworkAccount;

class SocialNetworkAccountsService
{
    /**
     * Create Social network account
     *
     * @param string $snID
     * @param string $userToken
     * @param int $userID
     *
     * @param int $userID
     */
    public static function createSocialNetworkAccount($snID, $userToken, $userID)
    {
        SocialNetworkAccount::create(
            [
                'social_network_id' => $snID,
                'user_token' => $userToken,
                'user_id' => $userID
            ]
        );
    }

    /**
     * Remove user's Social network accounts
     *
     * @param int $userID
     */
    public static function removeSocialNetworkAccount($userID)
    {
        SocialNetworkAccount::where('user_id', $userID)->delete();
    }

    /**
     * Find user's Social network account
     *
     * @param string $userToken
     * @param int $snID
     *
     * @return SocialNetworkAccount|null
     */
    public static function findSocialNetworkAccount($userToken, $snID)
    {
        return SocialNetworkAccount::where('user_token', $userToken)
            ->where('social_network_id', $snID)
            ->first();
    }

}

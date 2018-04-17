<?php

namespace App\Models\Services;

use App\Models\DB\Wallet;

class WalletsService
{
    /**
     * Create Wallet
     *
     * @param int $userID
     */
    public static function createWallet($userID)
    {
        Wallet::create(
            [
                'user_id' => $userID
            ]
        );
    }

    /**
     * Remove user's Wallet
     *
     * @param int $userID
     */
    public static function removeWallet($userID)
    {
        Wallet::where('user_id', $userID)->delete();
    }

}

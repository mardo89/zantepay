<?php

namespace App\Models\Services;

use App\Models\DB\DebitCard;

class DebitCardsService
{

    /**
     * Get user's Debit Card
     *
     * @param User $user
     *
     * @return DebitCard
     */
    public static function getDebitCard($user)
    {
        return DebitCard::where('user_id', $user->id)->first();
    }

    /**
     * Check if user pre-order Debit Card
     *
     * @param User $user
     *
     * @return boolean
     */
    public static function checkDebitCard($user)
    {
        return !is_null(self::getDebitCard($user));
    }

}

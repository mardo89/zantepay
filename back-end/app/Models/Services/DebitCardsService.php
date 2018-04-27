<?php

namespace App\Models\Services;

use App\Models\DB\DebitCard;

class DebitCardsService
{

    /**
     * Get user's Debit Card
     *
     * @param int $userID
     *
     * @return DebitCard
     */
    public static function getDebitCard($userID)
    {
        return DebitCard::where('user_id', $userID)->first();
    }

    /**
     * Get user's Debit Card design
     *
     * @param int $userID
     *
     * @return DebitCard
     */
    public static function getDebitCardDesign($userID)
    {
        $debitCard = self::getDebitCard($userID);

        return optional($debitCard)->design ?? '';
    }

    /**
     * Check if user's Debit Card has white design
     *
     * @param string $debitCardDesign
     *
     * @return boolean
     */
    public static function isDebitCardWhile($debitCardDesign)
    {
        return $debitCardDesign == DebitCard::DESIGN_WHITE;
    }

    /**
     * Check if user's Debit Card has red design
     *
     * @param string $debitCardDesign
     *
     * @return boolean
     */
    public static function isDebitCardRed($debitCardDesign)
    {
        return $debitCardDesign == DebitCard::DESIGN_RED;
    }


    /**
     * Check if user pre-order Debit Card
     *
     * @param int $userID
     *
     * @return boolean
     */
    public static function checkDebitCard($userID)
    {
        return !is_null(self::getDebitCard($userID));
    }

    /**
     * Remove user's Debit Card
     *
     * @param int $userID
     */
    public static function removeDebitCard($userID)
    {
        DebitCard::where('user_id', $userID)->delete();
    }

}

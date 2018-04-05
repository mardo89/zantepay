<?php

namespace App\Models\Services;


use App\Models\DB\DebitCard;
use App\Models\DB\Wallet;

class BonusesService
{

    /**
     * Check user bonus
     *
     * @param User $user
     *
     * @return mixed
     */
    public static function updateBonus($user)
    {
        $documentsVerified = DocumentsService::verificationComplete($user);
        $hasDebitCard = DebitCardsService::hasDebitCard($user);

        if (!$documentsVerified || !$hasDebitCard) {
            return;
        }

        // user bonus
        $wallet = $user->wallet;
        $wallet->debit_card_bonus = Wallet::DEBIT_CARD_BONUS;
        $wallet->save();

        // referrer bonus
        $referrer = UsersService::getReferrer($user);

        if (!$referrer) {
            return;
        }

        $wallet = $referrer->wallet;
        $wallet->referral_bonus += Wallet::REFERRAL_BONUS;
        $wallet->save();
    }

}

<?php

namespace App\Models\Services;


use App\Models\DB\Wallet;
use App\Models\Wallet\RateCalculator;

class BonusesService
{

    /**
     * Check user bonus
     *
     * @param User $user
     *
     * @return mixed
     * @throws
     */
    public static function updateBonus($user)
    {
        $documentsVerified = DocumentsService::verificationComplete($user);
        $hasDebitCard = DebitCardsService::checkDebitCard($user);

        if (!$documentsVerified || !$hasDebitCard) {
            return;
        }

        // user bonus
        $bonusAmount = Wallet::DEBIT_CARD_BONUS;

        $wallet = $user->wallet;
        $wallet->debit_card_bonus = $bonusAmount;
        $wallet->save();

        TransactionsService::createBonusTransaction($user->id, $bonusAmount);

        // referrer bonus
        $referrer = AccountsService::getReferrer($user->referrer);

        if (!$referrer) {
            return;
        }

        $bonusAmount = Wallet::REFERRAL_BONUS;

        $wallet = $referrer->wallet;
        $wallet->referral_bonus += $bonusAmount;
        $wallet->save();

        TransactionsService::createBonusTransaction($referrer->id, $bonusAmount);
    }

    /**
     * Calculate referral bonus
     *
     * @param User $referral
     *
     * @return string
     */
    public static function getReferralBonus($referral)
    {
        $hasDebitCard = DebitCardsService::checkDebitCard($referral);
        $documentsVerified = DocumentsService::verificationComplete($referral);

        $referralBonus = $hasDebitCard ? Wallet::REFERRAL_BONUS : 0;
        $bonusStatus = $documentsVerified ? '(locked - account is not verified)' : '';

        return $referralBonus > 0 ? $referralBonus . ' ' . $bonusStatus : '';
    }

    /**
     * Calculate commission bonus
     *
     * @param User $referral
     *
     * @return string
     */
    public static function getCommissionBonus($referral)
    {
        $contributionsList = PaymentsService::getEthPayments($referral);

        $commissionBonus = $contributionsList->sum('amount') * Wallet::COMMISSION_BONUS;

        return $commissionBonus > 0 ? RateCalculator::weiToEth($commissionBonus) : '';
    }

}

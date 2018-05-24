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
        $documentsVerified = VerificationService::verificationComplete($user->verification);
        $hasDebitCard = DebitCardsService::checkDebitCard($user->id);

        if (!$documentsVerified || !$hasDebitCard) {
            return;
        }

        // user bonus
        $bonusAmount = Wallet::DEBIT_CARD_BONUS;

        WalletsService::updateDebitCardBonus($user->wallet, $bonusAmount);

        TransactionsService::createBonusTransaction($user->id, $bonusAmount);

        // referrer bonus
        $referrer = AccountsService::getReferrer($user->referrer);

        if (!$referrer) {
            return;
        }

        $bonusAmount = Wallet::REFERRAL_BONUS;

        WalletsService::updateReferralBonus($referrer->wallet, $bonusAmount);

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
        $hasDebitCard = DebitCardsService::checkDebitCard($referral->id);
        $documentsVerified = VerificationService::verificationComplete($referral->verification);

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

<?php

namespace App\Models\Services;

use App\Models\DB\ZantecoinTransaction;
use App\Models\Wallet\CurrencyFormatter;
use App\Models\Wallet\RateCalculator;

class TransactionsService
{
    /**
     * Return all transaction types
     *
     * @return array
     */
    public static function getTransactionTypes() {
        return [
            ZantecoinTransaction::TRANSACTION_ETH_TO_ZNX,
            ZantecoinTransaction::TRANSACTION_COMMISSION_TO_ZNX,
            ZantecoinTransaction::TRANSACTION_ADD_ICO_ZNX,
            ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX,
            ZantecoinTransaction::TRANSACTION_ADD_BONUS_ZNX,
        ];
    }


    /**
     * Return ICO transaction types
     *
     * @return array
     */
    public static function getIcoTransactionTypes() {
        return [
            ZantecoinTransaction::TRANSACTION_ETH_TO_ZNX,
            ZantecoinTransaction::TRANSACTION_COMMISSION_TO_ZNX,
            ZantecoinTransaction::TRANSACTION_ADD_ICO_ZNX,
        ];
    }

    /**
     * Return Marketing transaction types
     *
     * @return array
     */
    public static function getMarketingTransactionTypes() {
        return [
            ZantecoinTransaction::TRANSACTION_ADD_BONUS_ZNX
        ];
    }

    /**
     * Return Foundation transaction types
     *
     * @return array
     */
    public static function getCompanyTransactionTypes() {
        return [
            ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX
        ];
    }

    /**
     * Create transaction when user receive bonus
     *
     * @param int $userID
     * @param int $amount
     */
    public static function createBonusTransaction($userID, $amount) {

        self::createZnxTransaction(
            [
                'user_id' => $userID,
                'amount' => $amount,
                'ico_part' => (new IcoService())->getActivePart()->getID(),
                'contribution_id' => 0,
                'transaction_type' => ZantecoinTransaction::TRANSACTION_ADD_BONUS_ZNX
            ]
        );

    }

    /**
     * Create transaction when user receive bonus
     *
     * @param int $userID
     * @param int $amount
     */
    public static function createAddIcoZnxTransaction($userID, $amount) {

        self::createZnxTransaction(
            [
                'user_id' => $userID,
                'amount' => $amount,
                'ico_part' => (new IcoService())->getActivePart()->getID(),
                'contribution_id' => 0,
                'transaction_type' => ZantecoinTransaction::TRANSACTION_ADD_ICO_ZNX
            ]
        );

    }

    /**
     * Create transaction when user receive bonus
     *
     * @param int $userID
     * @param int $amount
     */
    public static function createAddFoundationZnxTransaction($userID, $amount) {

        self::createZnxTransaction(
            [
                'user_id' => $userID,
                'amount' => $amount,
                'ico_part' => (new IcoService())->getActivePart()->getID(),
                'contribution_id' => 0,
                'transaction_type' => ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX
            ]
        );

    }

    /**
     * Create Transaction
     *
     * @param array $transactionData
     */
    protected static function createZnxTransaction($transactionData)
    {
        ZantecoinTransaction::create(
            [
                'user_id' => $transactionData['user_id'],
                'amount' => $transactionData['amount'],
                'ico_part' => $transactionData['ico_part'],
                'contribution_id' => $transactionData['contribution_id'],
                'transaction_type' => $transactionData['transaction_type']
            ]
        );
    }

    /**
     * Remove user's ZNX Transactions
     *
     * @param int $userID
     */
    public static function removeTransactions($userID)
    {
        ZantecoinTransaction::where('user_id', $userID)->delete();
    }

    /**
     * Get list of all user transactions
     *
     * @param User $user
     *
     * @return array
     */
    public static function getUserTransactions($user)
    {
        $userTransactions = [];

        $contributionTransactions = $user->wallet->contributions ?? [];
        $transferTransactions = $user->transferTransactions ?? [];
        $withdrawTransactions = $user->withdrawTransactions ?? [];

        foreach ($contributionTransactions as $contributionTransaction) {
            $ethAmount = RateCalculator::weiToEth($contributionTransaction->amount);

            $userTransactions[] = [
                'date' => date('d.m.Y', $contributionTransaction->time_stamp),
                'time' => date('H:i:s', $contributionTransaction->time_stamp),
                'address' => $contributionTransaction->proxy,
                'amount' => (new CurrencyFormatter($ethAmount))->ethFormat()->withSuffix('ETH')->get(),
                'type' => 'Buy',
                'status' => 'SUCCESS'
            ];
        }

        foreach ($transferTransactions as $transferTransaction) {
            $ethAmount = $transferTransaction->eth_amount;

            $userTransactions[] = [
                'date' => date('d.m.Y', strtotime($transferTransaction->created_at)),
                'time' => date('H:i:s', strtotime($transferTransaction->created_at)),
                'address' => '',
                'amount' => (new CurrencyFormatter($ethAmount))->ethFormat()->withSuffix('ETH')->get(),
                'type' => 'Transfer',
                'status' => 'SUCCESS'
            ];
        }

        foreach ($withdrawTransactions as $withdrawTransaction) {
            $ethAmount = $withdrawTransaction->amount;

            $userTransactions[] = [
                'date' => date('d.m.Y', strtotime($withdrawTransaction->created_at)),
                'time' => date('H:i:s', strtotime($withdrawTransaction->created_at)),
                'address' => $withdrawTransaction->wallet_address,
                'amount' => (new CurrencyFormatter($ethAmount))->ethFormat()->withSuffix('ETH')->get(),
                'type' => 'Withdraw',
                'status' => $withdrawTransaction->status
            ];
        }

        return $userTransactions;
    }

}

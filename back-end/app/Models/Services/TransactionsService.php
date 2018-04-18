<?php

namespace App\Models\Services;

use App\Models\DB\ZantecoinTransaction;

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
     * Return Foundation transaction types
     *
     * @return array
     */
    public static function getFoundationTransactionTypes() {
        return [
            ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX
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
     * Create transaction when user receive bonus
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
     * Remove user's ZNX Transactions
     *
     * @param int $userID
     */
    public static function removeTransactions($userID)
    {
        ZantecoinTransaction::where('user_id', $userID)->delete();
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

}

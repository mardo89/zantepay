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
            ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX
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
     * Remove user's ZNX Transactions
     *
     * @param int $userID
     */
    public static function removeTransactions($userID)
    {
        ZantecoinTransaction::where('user_id', $userID)->delete();
    }

}

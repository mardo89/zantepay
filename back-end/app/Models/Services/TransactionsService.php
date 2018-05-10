<?php

namespace App\Models\Services;

use App\Exceptions\TransactionException;
use App\Models\DB\TransferTransaction;
use App\Models\DB\User;
use App\Models\DB\WithdrawTransaction;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Wallet\CurrencyFormatter;
use App\Models\Wallet\Ico;
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
     * Create transaction when admin add ZNX to user from ICO pool
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
     * Create transaction when admin add ZNX to user from Foundation pool
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
     * Create transaction when user transfer commission bonus to ZNX
     *
     * @param User $user
     * @param int $amount
     *
     * @return array
     * @throws
     */
    public static function createTransferZnxTransaction($user, $amount) {

        $userWallet = $user->wallet;

        if ($userWallet->commission_bonus < $amount) {
            throw new TransactionException('Not enough ETH to transfer');
        }

        // Create transaction
        $transferTransaction = TransferTransaction::create(
            [
                'user_id' => $user->id,
                'eth_amount' => $amount,
            ]
        );

        // Convert ETH to ZNX
        $ico = new Ico();

        $znxAmountParts = RateCalculator::ethToZnx($amount, time(), $ico);

        $znxAmount = 0;

        foreach ($znxAmountParts as $znxAmountPart) {
            self::createZnxTransaction(
                [
                    'user_id' => $user->id,
                    'amount' => $znxAmountPart['amount'],
                    'ico_part' => $znxAmountPart['icoPart'],
                    'contribution_id' => $transferTransaction->id,
                    'transaction_type' => ZantecoinTransaction::TRANSACTION_COMMISSION_TO_ZNX
                ]
            );

            $znxAmount += $znxAmountPart['amount'];
        }

        // Update transaction
        $transferTransaction->znx_amount = $znxAmount;
        $transferTransaction->save();

        // Update Wallet
        WalletsService::transferCommissionBonus($userWallet, $amount, $znxAmount);

        MailService::sendTokenAddEmail(
            $user->email,
            (new CurrencyFormatter($znxAmount))->znxFormat()->withSuffix('ZNX')->get()
        );

        return [
            'balance' => (new CurrencyFormatter($znxAmount))->znxFormat()->get(),
            'total' => (new CurrencyFormatter($userWallet->znx_amount))->znxFormat()->withSuffix('ZNX tokens')->get()
        ];
    }

    /**
     * Create transaction when user withdraw commission bonus
     *
     * @param User $user
     * @param string $address
     *
     * @throws
     */
    public static function createWithdrawEthTransaction($user, $address) {

        $userWallet = $user->wallet;

        if ($userWallet->commission_bonus == 0) {
            throw new TransactionException('Not enough ETH to withdraw');
        }

        // Create transaction
        WithdrawTransaction::create(
            [
                'user_id' => $user->id,
                'amount' => $userWallet->commission_bonus,
                'wallet_address' => $address
            ]
        );

        WalletsService::zeroCommissionBonus($userWallet);

        UsersService::changeUserStatus($user, User::USER_STATUS_WITHDRAW_PENDING);
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

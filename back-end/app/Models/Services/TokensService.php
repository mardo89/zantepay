<?php

namespace App\Models\Services;


use App\Exceptions\GrantTokensException;
use App\Models\DB\GrantCoinsTransaction;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Search\Transactions;
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\Grant;

class TokensService
{
    /**
     * @var array Transaction Statuses
     */
    public static $transactionStatuses = [
        GrantCoinsTransaction::STATUS_IN_PROGRESS => 'In-Progress',
        GrantCoinsTransaction::STATUS_COMPLETE => 'Success',
        GrantCoinsTransaction::STATUS_FAILED => 'Failed'
    ];

    /**
     * Grant ICO Tokens
     *
     * @param string $address
     * @param int $amount
     * @param string $uid
     *
     * @throws \Exception
     */
    public static function grantIcoTokens($address, $amount, $uid)
    {
    	$user = AccountsService::getUserByID($uid);

    	$userGrantAddress = $user->profile->eth_wallet;
    	$userZnxAmount = $user->wallet->znx_amount;

	    if (!$address) {
		    throw new GrantTokensException('Proxy Address can not be empty.');
	    }

    	if (!$address || $address != $userGrantAddress) {
		    throw new GrantTokensException('Incorrect Proxy Address.');
	    }

	    if ($amount != $userZnxAmount) {
		    throw new GrantTokensException('Incorrect Amount.');
	    }

	    $pool = (new Grant())->icoPool();

    	if ($pool->reachLimit($amount)) {
		    throw new GrantTokensException('Pool limit was reached.');
	    }

        self::grantTokens($address, $amount, GrantCoinsTransaction::GRANT_ICO_TOKENS);
    }

    /**
     * Grant Marketing Tokens
     *
     * @param string $address
     * @param int $amount
     *
     * @throws \Exception
     */
    public static function grantMarketingTokens($address, $amount)
    {
	    if (!$address) {
		    throw new GrantTokensException('Beneficiary Address can not be empty.');
	    }

	    $pool = (new Grant())->marketingPool();

	    if ($pool->reachLimit($amount)) {
		    throw new GrantTokensException('Pool limit was reached.');
	    }

        self::grantTokens($address, $amount, GrantCoinsTransaction::GRANT_MARKETING_TOKENS);
    }

    /**
     * Grant Company Tokens
     *
     * @param string $address
     * @param int $amount
     *
     * @throws \Exception
     */
    public static function grantCompanyTokens($address, $amount)
    {
	    if (!$address) {
		    throw new GrantTokensException('Beneficiary Address can not be empty.');
	    }

	    $pool = (new Grant())->companyPool();

	    if ($pool->reachLimit($amount)) {
		    throw new GrantTokensException('Pool limit was reached.');
	    }

        self::grantTokens($address, $amount, GrantCoinsTransaction::GRANT_COMPANY_TOKENS);
    }


    /**
     * Grant Tokens
     *
     * @param string $address
     * @param int $amount
     * @param string $type
     *
     * @throws \Exception
     */
    protected static function grantTokens($address, $amount, $type)
    {
        $transaction = GrantCoinsTransaction::create(
            [
                'address' => $address,
                'amount' => $amount,
                'type' => $type,
            ]
        );

        try {

            $operationID = EtheriumApi::getCoinsOID($type, $amount, $address);

            $transaction->operation_id = $operationID;
            $transaction->save();

            $transactionStatus = EtheriumApi::checkCoinsStatus($operationID);

            switch ($transactionStatus) {
                case 'success':
                    $transaction->status = GrantCoinsTransaction::STATUS_COMPLETE;
                    break;

                case 'failure':
                    $transaction->status = GrantCoinsTransaction::STATUS_FAILED;
                    break;

                default:
                    $transaction->status = GrantCoinsTransaction::STATUS_IN_PROGRESS;
            }

            $transaction->save();

        } catch (\Exception $e) {

            $transaction->status = GrantCoinsTransaction::STATUS_FAILED;
            $transaction->save();

            throw new \Exception('Error granting tokens');
        }

    }

    /**
     * Return transaction status
     *
     * @param int $transactionStatus
     *
     * @return string
     */
    public static function getTransactionStatus($transactionStatus)
    {
        return self::$transactionStatuses[$transactionStatus] ?? '';
    }

    /**
     * Return balance of Granting Tokens
     *
     * @return array
     */
    public static function getGrantBalance()
    {
        $grant = new Grant();

        $foundationGranted = Transactions::searchTransactionsAmount(
            [
                ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX
            ]
        );

        $companyBalance = $grant->companyPool()->getLimit() - $foundationGranted;
        $marketingBalance = $grant->marketingPool()->getLimit();

        return [
            'marketing_balance' => $marketingBalance,
            'company_balance' => $companyBalance,
        ];
    }

}

<?php

namespace App\Models\Services;



use App\Models\DB\GrantCoinsTransaction;
use App\Models\Wallet\EtheriumApi;

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
     *
     * @throws \Exception
     */
    public static function grantIcoTokens($address, $amount)
    {
        /**
         * @todo Add checking of limits before grant
         * @todo Add checking if amount is correct
         */

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
        /**
         * @todo Add checking of limits before grant
         * @todo Add checking if amount is correct
         */

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
        /**
         * @todo Add checking of limits before grant
         * @todo Add checking if amount is correct
         */

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

}

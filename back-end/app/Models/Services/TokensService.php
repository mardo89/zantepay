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
		GrantCoinsTransaction::STATUS_IN_PROGRESS => 'Not issued',
		GrantCoinsTransaction::STATUS_COMPLETE => 'Issued',
//		GrantCoinsTransaction::STATUS_FAILED => 'Failed'
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

		try {

			$transaction = GrantCoinsTransaction::create(
				[
					'address' => $address,
					'amount' => $amount,
					'type' => $type,
					'status' => GrantCoinsTransaction::STATUS_COMPLETE
				]
			);


		} catch (\Exception $e) {

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
	 * Return available transaction statuses
	 *
	 * @return array
	 */
	public static function getTransactionStatuses()
	{
		$transactionStatuses = [];

		foreach (self::$transactionStatuses as $statusID => $statusName) {
			$transactionStatuses[] = ['id' => $statusID, 'name' => $statusName];
		}

		return $transactionStatuses;
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

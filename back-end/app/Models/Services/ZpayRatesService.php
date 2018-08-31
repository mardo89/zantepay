<?php

namespace App\Models\Services;

use App\Models\DB\EthAddressAction;
use App\Models\DB\User;
use App\Models\DB\Wallet;
use App\Models\DB\ZpayRate;
use App\Models\Wallet\Currency;
use App\Models\Wallet\CurrencyFormatter;

class ZpayRatesService
{

    /**
     * ETH Rate
     *
     */
    public static function ethRate()
    {
    	return ZpayRate::where('currency_type', ZpayRate::CURRENCY_TYPE_ETH)->first();
    }

	/**
	 * USD Rate
	 *
	 */
	public static function usdRate()
	{
		return ZpayRate::where('currency_type', ZpayRate::CURRENCY_TYPE_USD)->first();
	}

	/**
	 * EURO Rate
	 *
	 */
	public static function euroRate()
	{
		return ZpayRate::where('currency_type', ZpayRate::CURRENCY_TYPE_EURO)->first();
	}

	/**
	 * Update ETH Rate
	 *
	 * @param float $rate
	 *
	 * @return void
	 */
	public static function updateEthRate($rate)
	{

		$currentEthRate = self::ethRate();

		self::updateRate($currentEthRate, $rate, ZpayRate::CURRENCY_TYPE_ETH);

	}

	/**
	 * Update USD Rate
	 *
	 * @param float $rate
	 *
	 * @return void
	 */
	public static function updateUsdRate($rate)
	{

		$currentUsdRate = self::usdRate();

		self::updateRate($currentUsdRate, $rate, ZpayRate::CURRENCY_TYPE_USD);

	}

	/**
	 * Update Euro Rate
	 *
	 * @param float $rate
	 *
	 * @return void
	 */
	public static function updateEuroRate($rate)
	{

		$currentEuroRate = self::euroRate();

		self::updateRate($currentEuroRate, $rate, ZpayRate::CURRENCY_TYPE_EURO);

	}

	/**
	 * Update USD Rate
	 *
	 * @param $rate
	 * @param float $rateValue
	 * @param integer $currencyType
	 *
	 * @return void
	 */
	protected static function updateRate($rate, $rateValue, $currencyType)
	{

		if (is_null($rate)) {
			ZpayRate::create(
				[
					'currency_type' => $currencyType,
					'rate' => $rateValue
				]
			);
		} else {
			$rate->rate = $rateValue;
			$rate->save();
		}

	}

}

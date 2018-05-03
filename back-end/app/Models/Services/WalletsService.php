<?php

namespace App\Models\Services;

use App\Models\DB\Wallet;
use App\Models\Wallet\Currency;

class WalletsService
{
    /**
     * @var array Available Currencies
     */
    public static $currencies = [
        Currency::CURRENCY_TYPE_BTC => 'BTC',
        Currency::CURRENCY_TYPE_ETH => 'ETH',
        Currency::CURRENCY_TYPE_ZNX => 'ZNX'
    ];

    /**
     * Create Wallet
     *
     * @param int $userID
     */
    public static function createWallet($userID)
    {
        Wallet::create(
            [
                'user_id' => $userID
            ]
        );
    }

    /**
     * Remove user's Wallet
     *
     * @param int $userID
     */
    public static function removeWallet($userID)
    {
        Wallet::where('user_id', $userID)->delete();
    }

    /**
     * Get currency
     *
     * @param int $currency
     *
     * @return string
     */
    public static function getCurrency($currency)
    {
        return self::$currencies[$currency] ?? '';

    }

    /**
     * Get currencies list
     *
     * @return array
     */
    public static function getCurrencies()
    {
        $currencies = [];

        foreach (self::$currencies as $currencyID => $currencyName) {
            $currencies[] = ['id' => $currencyID, 'name' => $currencyName];
        }

        return $currencies;
    }

    /**
     * Add ZNX from ICO pull
     *
     * @param string $userUID
     * @param int $amount
     *
     * @return int
     * @throws
     */
    public static function addIcoZnx($userUID, $amount)
    {
        $user = AccountsService::getUserByID($userUID);

        TransactionsService::createAddIcoZnxTransaction($user->id, $amount);

        return self::updateZnxAmount($user->wallet, $amount);
    }

    /**
     * Add ZNX from ICO pull
     *
     * @param string $userUID
     * @param int $amount
     *
     * @return int
     * @throws
     */
    public static function addFoundationZnx($userUID, $amount)
    {
        $user = AccountsService::getUserByID($userUID);

        TransactionsService::createAddFoundationZnxTransaction($user->id, $amount);

        return self::updateZnxAmount($user->wallet, $amount);
    }

    /**
     * Update ZNX amount
     *
     * @param Wallet $wallet
     * @param int $amount
     *
     * @return int
     */
    protected static function updateZnxAmount($wallet, $amount)
    {
        $wallet->znx_amount += $amount;
        $wallet->save();

        return $wallet->znx_amount;
    }

}

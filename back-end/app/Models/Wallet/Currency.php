<?php

namespace App\Models\Wallet;


class Currency
{
    /**
     * Currency types
     */
    const CURRENCY_TYPE_BTC = 0;
    const CURRENCY_TYPE_ETH = 1;
    const CURRENCY_TYPE_ZNX = 2;

    /**
     * Get currency
     *
     * @param int $currency
     *
     * @return string
     */
    public static function getCurrency($currency)
    {
        switch ($currency) {
            case self::CURRENCY_TYPE_BTC:
                return 'BTC';

            case self::CURRENCY_TYPE_ETH:
                return 'ETH';

            case self::CURRENCY_TYPE_ZNX:
                return 'ZNX';

            default:
                return '';
        }
    }

    /**
     * Get list of available currencies
     *
     * @return array
     */
    public static function getCurrencyList() {
        return [
            [
                'id' => self::CURRENCY_TYPE_BTC,
                'name' => self::getCurrency(self::CURRENCY_TYPE_BTC)
            ],
            [
                'id' => self::CURRENCY_TYPE_ETH,
                'name' => self::getCurrency(self::CURRENCY_TYPE_ETH)
            ],
            [
                'id' => self::CURRENCY_TYPE_ZNX,
                'name' => self::getCurrency(self::CURRENCY_TYPE_ZNX)
            ],
        ];
    }

}

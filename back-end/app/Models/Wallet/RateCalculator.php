<?php

namespace App\Models\Wallet;


use App\Models\DB\ZantecoinTransaction;

class RateCalculator
{
    /**
     * ZNX coins limits for Etherium
     */
    const ETH_LIMIT_ONE = 30000000;
    const ETH_LIMIT_TWO = 70000000;
    const ETH_LIMIT_THREE = 200000000;
    const ETH_LIMIT_FOUR = 300000000;


    /**
     * Convert ETH to ZNX
     *
     * @param float $amount
     * @param int $totalZnxCoins
     *
     * @return float
     */
    public static function ethToZnx($amount, $totalZnxCoins = null)
    {
        if (is_null($totalZnxCoins)) {
            $totalZnxCoins = ZantecoinTransaction::all()->sum('amount');
        }

        $rate = self::getEthRate($totalZnxCoins);

        if ($rate['balance'] == 0) {
            return 0;
        }

        $znxAmount = floor($amount / $rate['rate']);

        if ($znxAmount <= $rate['balance']) {
            return $znxAmount;
        }

        $leftEthAmount = $amount - ($rate['balance'] * $rate['rate']);

        return $rate['balance'] + self::ethToZnx($leftEthAmount, $totalZnxCoins + $rate['balance']);
    }


    /**
     * Convert ETH to ZNX
     *
     * @param float $amount
     * @param int $totalZnxCoins
     *
     * @return float
     */
    public static function znxToEth($amount, $totalZnxCoins = null)
    {
        if (is_null($totalZnxCoins)) {
            $totalZnxCoins = ZantecoinTransaction::all()->sum('amount');
        }

        $rate = self::getEthRate($totalZnxCoins);

        return $amount * $rate['rate'];
    }

    /**
     * Get ETH rate
     *
     * @param int $totalZnxCoins
     *
     * @return array
     */
    public static function getEthRate($totalZnxCoins)
    {
        $rate = [
            'rate' => 0,
            'balance' => 0
        ];

        if (self::isPartOne($totalZnxCoins)) {
            $rate = [
                'rate' => 0.00007,
                'balance' => self::ETH_LIMIT_ONE - $totalZnxCoins
            ];
        }

        if (self::isPartTwo($totalZnxCoins)) {
            $rate = [
                'rate' => 0.00014,
                'balance' => self::ETH_LIMIT_ONE + self::ETH_LIMIT_TWO - $totalZnxCoins
            ];
        }

        if (self::isPartThree($totalZnxCoins)) {
            $rate = [
                'rate' => 0.000196,
                'balance' => self::ETH_LIMIT_ONE + self::ETH_LIMIT_TWO + self::ETH_LIMIT_THREE - $totalZnxCoins
            ];
        }

        if (self::isPartFour($totalZnxCoins)) {
            $rate = [
                'rate' => 0.00035,
                'balance' => self::ETH_LIMIT_ONE + self::ETH_LIMIT_TWO + self::ETH_LIMIT_THREE + self::ETH_LIMIT_FOUR - $totalZnxCoins
            ];
        }

        return $rate;
    }

    /**
     * Check if current part is First Part
     *
     * @param int $totalZnxCoins
     *
     * @return bool
     */
    protected static function isPartOne($totalZnxCoins)
    {
        return $totalZnxCoins < self::ETH_LIMIT_ONE;
    }

    /**
     * Check if current part is Second Part
     *
     * @param int $totalZnxCoins
     *
     * @return bool
     */
    protected static function isPartTwo($totalZnxCoins)
    {
        return (
            $totalZnxCoins >= self::ETH_LIMIT_ONE
            && $totalZnxCoins < (self::ETH_LIMIT_ONE + self::ETH_LIMIT_TWO)
        );
    }

    /**
     * Check if current part is Third Part
     *
     * @param int $totalZnxCoins
     *
     * @return bool
     */
    protected static function isPartThree($totalZnxCoins)
    {
        return (
            $totalZnxCoins >= (self::ETH_LIMIT_ONE + self::ETH_LIMIT_TWO)
            && $totalZnxCoins < (self::ETH_LIMIT_ONE + self::ETH_LIMIT_TWO + self::ETH_LIMIT_THREE)
        );
    }

    /**
     * Check if current part is Fourth Part
     *
     * @param int $totalZnxCoins
     *
     * @return bool
     */
    protected static function isPartFour($totalZnxCoins)
    {
        return (
            $totalZnxCoins >= (self::ETH_LIMIT_ONE + self::ETH_LIMIT_TWO + self::ETH_LIMIT_THREE)
            && $totalZnxCoins < (self::ETH_LIMIT_ONE + self::ETH_LIMIT_TWO + self::ETH_LIMIT_THREE + self::ETH_LIMIT_FOUR)
        );
    }

}

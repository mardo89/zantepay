<?php

namespace App\Models\Wallet;


use App\Models\DB\ZantecoinTransaction;

class RateCalculator
{
    /**
     * ZNX coins limits for Etherium
     */
    const ETH_LIMIT_ONE = 30000000;
    const ETH_LIMIT_TWO = 100000000;
    const ETH_LIMIT_THREE = 300000000;
    const ETH_LIMIT_FOUR = 600000000;


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

        if ($rate['balance'] == 0) {
            return 0;
        }

        if ($amount <= $rate['balance']) {
            return $amount * $rate['rate'];
        }

        $partEthAmount = $rate['balance'] * $rate['rate'];
        $leftZnxAmount = $amount - $rate['balance'];

        return $partEthAmount  + self::znxToEth($leftZnxAmount, $totalZnxCoins + $rate['balance']);
    }

    /**
     * Convert ETH to Wei
     *
     * @param float $amount
     *
     * @return float
     */
    public static function ethToWei($amount)
    {
        return $amount * 1000000000000000000;
    }

    /**
     * Convert Wei to ETH
     *
     * @param float $amount
     *
     * @return float
     */
    public static function weiToEth($amount)
    {
        return $amount / 1000000000000000000;
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
        if (self::isPartOne($totalZnxCoins)) {
            return [
                'rate' => 0.00007,
                'balance' => self::ETH_LIMIT_ONE - $totalZnxCoins
            ];
        }

        if (self::isPartTwo($totalZnxCoins)) {
            return [
                'rate' => 0.00014,
                'balance' => self::ETH_LIMIT_TWO - $totalZnxCoins
            ];
        }

        if (self::isPartThree($totalZnxCoins)) {
            return [
                'rate' => 0.000196,
                'balance' => self::ETH_LIMIT_THREE - $totalZnxCoins
            ];
        }

        if (self::isPartFour($totalZnxCoins)) {
            return [
                'rate' => 0.00035,
                'balance' => self::ETH_LIMIT_FOUR - $totalZnxCoins
            ];
        }

        return [
            'rate' => 0,
            'balance' => 0
        ];
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
        return $totalZnxCoins >= self::ETH_LIMIT_ONE && $totalZnxCoins < self::ETH_LIMIT_TWO;
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
        return $totalZnxCoins >= self::ETH_LIMIT_TWO && $totalZnxCoins < self::ETH_LIMIT_THREE;
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
        return $totalZnxCoins >= self::ETH_LIMIT_THREE && $totalZnxCoins < self::ETH_LIMIT_FOUR;
    }

}

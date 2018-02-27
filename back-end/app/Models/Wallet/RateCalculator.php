<?php

namespace App\Models\Wallet;


use App\Models\DB\ZantecoinTransaction;

class RateCalculator
{
    /**
     * Convert ETH to ZNX
     *
     * @param float $ethAmount
     * @param string $operationDate
     * @param Ico $ico
     *
     * @return array
     */
    public static function ethToZnx($ethAmount, $operationDate, $ico)
    {
        $icoPart = $ico->getPart($operationDate);

        if (!$icoPart) {
            return [];
        }

        $rate = $icoPart->getEthRate();
        $balance = $icoPart->getBalance();

        $availableEthAmount = $rate * $balance;

        $znx = [];

        if ($availableEthAmount < $ethAmount) {
            $icoPart->increaseAmount($balance);

            $znx[] = [
                'amount' => $balance,
                'rate' => $rate,
                'icoPart' => $icoPart->getID()
            ];

            return array_merge(
                $znx,
                self::ethToZnx($ethAmount - $availableEthAmount, $operationDate, $ico)
            );
        }

        $znxAmount = floor($ethAmount / $rate);

        $icoPart->increaseAmount($znxAmount);

        $znx[] = [
            'amount' => $znxAmount,
            'rate' => $rate,
            'icoPart' => $icoPart->getID()
        ];

        return $znx;
    }

    /**
     * Convert ETH to ZNX
     *
     * @param int $znxAmount
     * @param string $operationDate
     * @param Ico $ico
     *
     * @return array
     */
    public static function znxToEth($znxAmount, $operationDate, $ico)
    {
        $icoPart = $ico->getPart($operationDate);

        if (!$icoPart) {
            return [];
        }

        $rate = $icoPart->getEthRate();
        $balance = $icoPart->getBalance();

        $eth = [];

        if ($balance < $znxAmount) {
            $icoPart->increaseAmount($balance);

            $eth[] = [
                'amount' => $balance * $rate,
                'rate' => $rate,
                'icoPart' => $icoPart->getID()
            ];

            return array_merge(
                $eth,
                self::znxToEth($znxAmount - $balance, $operationDate, $ico)
            );

        }

        $ethAmount = $znxAmount * $rate;

        $icoPart->increaseAmount($znxAmount);

        $eth[] = [
            'amount' => $ethAmount,
            'rate' => $rate,
            'icoPart' => $icoPart->getID()
        ];

        return $eth;
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

}

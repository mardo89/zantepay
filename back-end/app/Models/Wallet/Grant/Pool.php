<?php

namespace App\Models\Wallet\Grant;


class Pool
{
    /**
     * @var int Marketing current amount of coins
     */
    protected $znxAmount;

    /**
     * @var int Marketing coins limit
     */
    protected $znxLimit;


    public function __construct()
    {
        $this->init();
    }


    /**
     * Get ZNX limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->znxLimit;
    }

    /**
     * Check Marketing Coins limit
     *
     * @param int $amount
     *
     * @return boolean
     */
    public function reachLimit($amount)
    {
        return ($this->znxAmount + $amount) <= $this->znxLimit;
    }

    /**
     * Get ZNX balance
     *
     * @return int
     */
    public function getBalance()
    {
        return $this->znxLimit - $this->znxAmount;
    }

}

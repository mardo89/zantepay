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
     * Check if limit reached
     *
     * @param int $amount
     *
     * @return boolean
     */
    public function reachLimit($amount)
    {
        return $amount > $this->getBalance();
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

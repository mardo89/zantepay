<?php

namespace App\Models\Wallet;



class Marketing
{
    /**
     * @var int Marketing coins limit
     */
    protected $znxLimit = 600000000;

    /**
     * @var int Marketing current amount of coins
     */
    protected $znxAmount;


    public function __construct()
    {
        /**
         * @todo add initialization of current amount of coins
         */
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

}

<?php

namespace App\Models\Wallet\ICO;


abstract class IcoPart
{
    protected $icoStartDate;
    protected $icoEndDate;
    protected $icoZnxLimit;
    protected $icoZnxAmount;

    public function __construct()
    {
        $this->init();
    }

    /**
     * Init params
     *
     * @return mixed
     */
    abstract protected function init();

    /**
     * Check if Part is active part
     *
     * @return bool
     */
    public function isActive() {
        $currentDate = time();

        $checkDate = strtotime($this->icoStartDate) >= $currentDate && strtotime($this->icoEndDate) < $currentDate;
        $checkAmount = $this->icoZnxAmount < $this->icoZnxLimit;

        return $checkDate && $checkAmount;
    }

    /**
     * Check if Part is active part
     *
     * @return int
     */
    public function getLimit() {
        return $this->icoZnxLimit;
    }

    /**
     * Get ZNX balance
     *
     * @return int
     */
    public function getBalance() {
        return $this->icoZnxLimit - $this->icoZnxAmount;
    }

    /**
     * Get ZNX rate
     *
     * @return int
     */
    public function getRate() {
        return $this->icoZnxAmount / $this->icoZnxLimit;
    }

    /**
     * Increase ZNX amount
     *
     * @param int $amount
     */
    public function increaseAmount($amount) {
        $this->icoZnxAmount += $amount;
    }
}

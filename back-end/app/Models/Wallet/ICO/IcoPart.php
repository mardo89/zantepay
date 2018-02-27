<?php

namespace App\Models\Wallet\ICO;

use App\Models\DB\Contribution;
use App\Models\DB\ZantecoinTransaction;

class IcoPart
{
    /**
     * @var string ICO part ID
     */
    protected $id;

    /**
     * @var string ICO part name
     */
    protected $name;

    /**
     * @var string ICO part start date
     */
    protected $icoStartDate;

    /**
     * @var string ICO part end date
     */
    protected $icoEndDate;

    /**
     * @var int ICO coins limit
     */
    protected $icoZnxLimit;

    /**
     * @var int ICO current coins amount
     */
    protected $icoZnxAmount;

    /**
     * @var float ICO etherium rate
     */
    protected $ethZnxRate;

    /**
     * IcoPart constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Init params
     *
     * @return mixed
     */
    protected function init()
    {
        $this->icoZnxAmount = ZantecoinTransaction::where('ico_part', $this->getID())
            ->get()
            ->sum('amount');
    }

    /**
     * Get part ID
     *
     * @return string
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Get part Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get ZNX limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->icoZnxLimit;
    }

    /**
     * Get ZNX Rate
     *
     * @return int
     */
    public function getEthRate()
    {
        return $this->ethZnxRate;
    }

    /**
     * Get ZNX balance
     *
     * @return int
     */
    public function getBalance()
    {
        return $this->icoZnxLimit - $this->icoZnxAmount;
    }

    /**
     * Get ZNX rate
     *
     * @return int
     */
    public function getRelativeBalance()
    {
        return $this->icoZnxAmount / $this->icoZnxLimit;
    }

    /**
     * Check if Part is active part
     *
     * @param int $operationDate
     *
     * @return bool
     */
    public function isActive($operationDate)
    {
//        $checkDate = strtotime($this->icoStartDate) >= $currentDate && strtotime($this->icoEndDate) < $currentDate;
        $checkDate = $operationDate < strtotime($this->icoEndDate);
        $checkAmount = $this->icoZnxAmount < $this->icoZnxLimit;

        return $checkDate && $checkAmount;
    }

    /**
     * Increase ZNX amount
     *
     * @param int $amount
     */
    public function increaseAmount($amount)
    {
        $this->icoZnxAmount += $amount;
    }
}

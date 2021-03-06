<?php

namespace App\Models\Wallet\ICO;

use App\Models\DB\Contribution;
use App\Models\DB\EthRate;
use App\Models\DB\UsdRate;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Services\TransactionsService;

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
     * @var float ICO euro rate
     */
    protected $euroZnxRate;

	/**
	 * @var float ICO usd rate
	 */
	protected $usdZnxRate;

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
            ->whereIn('transaction_type', TransactionsService::getIcoTransactionTypes())
            ->get()
            ->sum('amount');

        $ethEuroRate = optional(EthRate::where('currency_type', EthRate::CURRENCY_TYPE_EURO)->first())->rate ?? 0;
	    $usdEuroRate = optional(UsdRate::where('currency_type', EthRate::CURRENCY_TYPE_EURO)->first())->rate ?? 0;

        $this->ethZnxRate = $this->euroZnxRate / $ethEuroRate;
	    $this->usdZnxRate = $this->euroZnxRate * $usdEuroRate;
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
     * Get ETH to ZNX Rate
     *
     * @return int
     */
    public function getEthRate()
    {
        return $this->ethZnxRate;
    }

    /**
     * Get EURO to ZNX Rate
     *
     * @return int
     */
    public function getEuroRate()
    {
        return $this->euroZnxRate;
    }

	/**
	 * Get USD to ZNX Rate
	 *
	 * @return int
	 */
	public function getUsdRate()
	{
		return $this->usdZnxRate;
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
     * Get ZNX amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->icoZnxAmount;
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
     * Get start date
     *
     * @return string
     */
    public function getStartDate()
    {
        return $this->icoStartDate;
    }

    /**
     * Get end date
     *
     * @return string
     */
    public function getEndDate()
    {
        return $this->icoEndDate;
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
        $checkDate = $operationDate < strtotime($this->icoEndDate);
        $checkAmount = $this->icoZnxAmount < $this->icoZnxLimit;

        return $checkDate && $checkAmount;
    }

    /**
     * Check if Part is finished
     *
     * @param int $operationDate
     *
     * @return bool
     */
    public function isFinished($operationDate)
    {
        $checkDate = $operationDate > strtotime($this->icoEndDate);
        $checkAmount = $this->icoZnxAmount > $this->icoZnxLimit;

        return $checkDate || $checkAmount;
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

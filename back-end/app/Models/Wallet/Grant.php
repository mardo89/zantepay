<?php

namespace App\Models\Wallet;


use App\Models\Wallet\Grant\CompanyPool;
use App\Models\Wallet\Grant\IcoPool;
use App\Models\Wallet\Grant\MarketingPool;

class Grant
{
    protected $icoPool;
    protected $marketingPool;
    protected $companyPool;

    public function __construct()
    {
        $this->icoPool = new IcoPool();
        $this->marketingPool = new MarketingPool();
        $this->companyPool = new CompanyPool();
    }

    /**
     * Get ICO pool
     *
     * @return mixed
     */
    public function icoPool() {
        return $this->icoPool;
    }

    /**
     * Get Marketing pool
     *
     * @return mixed
     */
    public function marketingPool() {
        return $this->marketingPool;
    }

    /**
     * Get Company pool
     *
     * @return mixed
     */
    public function companyPool() {
        return $this->companyPool;
    }

}

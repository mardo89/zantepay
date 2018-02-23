<?php

namespace App\Models\Wallet\ICO;


use App\Models\DB\ZantecoinTransaction;

class IcoPartFour extends IcoPart
{
    protected $icoStartDate = '15-06-2018 19:00:00';
    protected $icoEndDate = '15-07-2018 19:00:00';
    protected $icoZnxLimit = 300000000;
    protected $icoZnxAmount = 0;

    protected function init() {
        $this->icoZnxAmount = ZantecoinTransaction::where('transaction_date', '>=', $this->icoStartDate)
            ->get()
            ->sum('amount');
    }
}

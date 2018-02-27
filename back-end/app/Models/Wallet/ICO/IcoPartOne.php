<?php

namespace App\Models\Wallet\ICO;


class IcoPartOne extends IcoPart
{
    protected $id = 'ICO_PART_ONE';
    protected $name = 'PRE ICO';

    protected $icoStartDate = '15-03-2018 19:00:00';
    protected $icoEndDate = '15-04-2018 19:00:00';
    protected $icoZnxLimit = 30000000;
    protected $icoZnxAmount = 0;
    protected $ethZnxRate = 0.00007;
}

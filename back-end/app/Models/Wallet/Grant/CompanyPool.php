<?php

namespace App\Models\Wallet\Grant;

use App\Models\DB\GrantCoinsTransaction;

class CompanyPool extends Pool
{
    /**
     * @var int Marketing coins limit
     */
    protected $znxLimit = 800000000;

    /**
     * Init params
     *
     * @return mixed
     */
    protected function init()
    {
        $this->znxAmount = GrantCoinsTransaction::where('type', GrantCoinsTransaction::GRANT_COMPANY_TOKENS)
            ->get()
            ->sum('amount');
    }

}

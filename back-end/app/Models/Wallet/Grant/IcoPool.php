<?php

namespace App\Models\Wallet\Grant;

use App\Models\DB\GrantCoinsTransaction;

class IcoPool extends Pool
{
    /**
     * @var int Marketing coins limit
     */
    protected $znxLimit = 600000000;

    /**
     * Init params
     *
     * @return mixed
     */
    protected function init()
    {
        $this->znxAmount = GrantCoinsTransaction::where('type', GrantCoinsTransaction::GRANT_ICO_TOKENS)
            ->get()
            ->sum('amount');
    }

}

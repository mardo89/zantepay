<?php

namespace App\Models\Services;


use App\Models\DB\Contribution;

class PaymentsService
{

    /**
     * Get user ETH payments
     *
     * @param User $user
     *
     * @return mixed
     */
    public static function getEthPayments($user)
    {
        return Contribution::where('proxy', $user->wallet->eth_wallet)->get();
    }


}

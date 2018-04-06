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

    /**
     * Search ETH payments
     *
     * @param string $startDate
     * @param string $endDate
     *
     * @return mixed
     */
    public static function searchEthPayments($startDate, $endDate)
    {
        return $weiReceived = Contribution::where('time_stamp', '>=', strtotime($startDate))
            ->where('time_stamp', '<', strtotime($endDate))
            ->get();
    }

}

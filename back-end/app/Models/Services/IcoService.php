<?php

namespace App\Models\Services;


use App\Models\DB\Contribution;
use App\Models\Wallet\CurrencyFormatter;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;

class IcoService
{

    /**
     * Get information about ICO process
     *
     *
     * @return array
     */
    public static function getInfo()
    {
        $ico = new Ico();

        $currentDate = time();

        $icoInfo = [];

        foreach ($ico->getParts() as $icoPart) {
            $startDate = $icoPart->getStartDate();
            $endDate = $icoPart->getEndDate();

            $weiReceived = PaymentsService::searchEthPayments($startDate, $endDate)->sum('amount');

            $icoName = $icoPart->getName();

            if (strtotime($icoPart->getStartDate()) < $currentDate) {
                $icoName .= ' (started ' . $startDate . ')';
            } else {
                $icoName .= ' (starts ' . $startDate . ')';
            }

            if ($icoPart->getID() === $ico->getActivePart()->getID()) {
                $icoName .= ' - current';
            }

            $icoInfo[] = [
                'name' => $icoName,
                'limit' => (new CurrencyFormatter($icoPart->getLimit()))->numberFormat()->get(),
                'balance' => (new CurrencyFormatter($icoPart->getBalance()))->numberFormat()->get(),
                'eth' => (new CurrencyFormatter(RateCalculator::weiToEth($weiReceived)))->ethFormat()->get()
            ];
        }

        return $icoInfo;
    }

}

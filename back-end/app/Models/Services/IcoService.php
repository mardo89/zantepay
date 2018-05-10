<?php

namespace App\Models\Services;


use App\Models\DB\Contribution;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Search\Transactions;
use App\Models\Wallet\CurrencyFormatter;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;

class IcoService
{
    /**
     * @var ICO object
     */
    protected $ico;

    public function __construct()
    {
        $this->ico = new Ico();
    }

    /**
     * Get information about ICO process for Main page
     *
     * @return array
     */
    public function getInfo()
    {
        $activePart = $this->ico->getActivePart();
        $previousPart = $this->ico->getPreviousPart();
        $lastPart = $this->ico->getIcoPartFour();

        $prevPartAmount = Transactions::searchTransactionsAmount(
            [
                ZantecoinTransaction::TRANSACTION_ETH_TO_ZNX,
                ZantecoinTransaction::TRANSACTION_ADD_ICO_ZNX,
                ZantecoinTransaction::TRANSACTION_COMMISSION_TO_ZNX,
                ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX
            ]
        );

        $icoPartName = optional($activePart)->getName() ?? '';
        $icoPartLimit = optional($activePart)->getLimit() + $prevPartAmount ?? $prevPartAmount;

        $icoPartEndDate = optional($lastPart)->getEndDate() ?? '';

        $icoPartEthRate = optional($activePart)->getEthRate() ?? 0;
        $icoPartEuroRate = optional($activePart)->getEuroRate() ?? 0;
        $icoPartZnxRate = RateCalculator::toZnx(1, $icoPartEthRate);

        $icoPartAmount = optional($activePart)->getAmount() + $prevPartAmount ?? $prevPartAmount;
        $icoPartRelativeBalance = $icoPartLimit > 0 ? $icoPartAmount / $icoPartLimit : 0;

        $ethLimit = RateCalculator::fromZnx($icoPartLimit, $icoPartEthRate);
        $ethAmount = RateCalculator::fromZnx($icoPartAmount, $icoPartEthRate);

        $showProgress = !is_null($previousPart);

        return [
            'name' => $icoPartName,
            'showProgress' => $showProgress,
            'endDate' => date('Y/m/d H:i:s', strtotime($icoPartEndDate)),
            'znxLimit' => number_format($icoPartLimit, 0, ',', '.'),
            'znxAmount' => number_format($icoPartAmount, 0, ',', '.'),
            'prevAmount' => number_format($prevPartAmount, 0, ',', '.'),
            'ethLimit' => number_format($ethLimit, 0, ',', '.'),
            'ethAmount' => number_format($ethAmount, 0, ',', '.'),
            'znxRate' => (new CurrencyFormatter($icoPartZnxRate))->znxFormat()->get(),
            'ethRate' => (new CurrencyFormatter($icoPartEthRate))->ethFormat()->get(),
            'euroRate' => (new CurrencyFormatter($icoPartEuroRate))->ethFormat()->get(),
            'relativeBalance' => [
                'value' => $icoPartRelativeBalance,
                'percent' => number_format($icoPartRelativeBalance * 100, 2),
                'progressClass' => $icoPartRelativeBalance > 50 ? 'is-left' : 'is-right'
            ]
        ];
    }


    /**
     * Get information about ICO process for Admin page
     *
     * @return array
     */
    public function getAdminInfo()
    {
        $icoInfo = [];

        foreach ($this->ico->getParts() as $icoPart) {
            $startDate = $icoPart->getStartDate();
            $endDate = $icoPart->getEndDate();

            $weiReceived = PaymentsService::searchEthPayments($startDate, $endDate)->sum('amount');

            $icoName = $icoPart->getName();

            if (strtotime($icoPart->getStartDate()) < time()) {
                $icoName .= ' (started ' . $startDate . ')';
            } else {
                $icoName .= ' (starts ' . $startDate . ')';
            }

            if ($icoPart->getID() === $this->ico->getActivePart()->getID()) {
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

    /**
     * Get active ICO part
     *
     * @return int
     */
    public function getActivePart()
    {
        return $this->ico->getActivePart();
    }

}

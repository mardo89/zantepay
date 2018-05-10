<?php

namespace App\Models\Services;


use App\Exceptions\EtheriumException;
use App\Models\DB\EthAddressAction;
use App\Models\Wallet\CurrencyFormatter;
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;

class EtheriumService
{

    /**
     * Create ETH address
     *
     * @param User $user
     *
     * @return string
     * @throws
     */
    public static function createAddress($user)
    {
        $lastAction = EthAddressAction::where('user_id', $user->id)->get()->last();

        if (optional($lastAction)->status === EthAddressAction::STATUS_IN_PROGRESS || optional($lastAction)->status === EthAddressAction::STATUS_COMPLETE) {
            throw new EtheriumException('Operation in-progress or Etherium address already exists.');
        }

        $ethAddressAction = EthAddressAction::create(
            [
                'user_id' => $user->id,
            ]
        );

        try {
            $operationID = EtheriumApi::getAddressOID($user->uid);

            $ethAddressAction->operation_id = $operationID;
            $ethAddressAction->save();

            $address = EtheriumApi::createAddress($operationID);

            $ethAddressAction->status = EthAddressAction::STATUS_COMPLETE;
            $ethAddressAction->save();

            WalletsService::updateEtheriumAddress($user->wallet, $address);

        } catch (\Exception $e) {

            $ethAddressAction->status = EthAddressAction::STATUS_FAILED;
            $ethAddressAction->error_message = $e->getMessage();
            $ethAddressAction->save();

            throw new EtheriumException('Error creating Wallet Addresss');
        }

        return $address;
    }

    /**
     * Calculate exchange amount
     *
     * @param $znxAmount
     * @param $ethAmount
     *
     * @return sting
     */
    public static function exchangeCalculator($znxAmount, $ethAmount) {

        $ico = new Ico();

        if (!is_null($znxAmount)) {
            $ethAmountParts = RateCalculator::znxToEth($znxAmount, time(), $ico);

            $ethAmount = 0;

            foreach ($ethAmountParts as $ethAmountPart) {
                $ethAmount += $ethAmountPart['amount'];
            }

            $balance = (new CurrencyFormatter($ethAmount))->ethFormat()->get();

        } else {
            $znxAmountParts = RateCalculator::ethToZnx($ethAmount, time(), $ico);

            $znxAmount = 0;

            foreach ($znxAmountParts as $znxAmountPart) {
                $znxAmount += $znxAmountPart['amount'];
            }

            $balance = (new CurrencyFormatter($znxAmount))->znxFormat()->get();
        }

        return $balance;
    }
}

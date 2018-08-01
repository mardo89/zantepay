<?php

namespace App\Models\Services;

use App\Models\DB\EthAddressAction;
use App\Models\DB\User;
use App\Models\DB\Wallet;
use App\Models\Wallet\Currency;
use App\Models\Wallet\CurrencyFormatter;

class WalletsService
{
    /**
     * @var array Available Currencies
     */
    public static $currencies = [
        Currency::CURRENCY_TYPE_BTC => 'BTC',
        Currency::CURRENCY_TYPE_ETH => 'ETH',
        Currency::CURRENCY_TYPE_ZNX => 'ZNX'
    ];

    /**
     * Create Wallet
     *
     * @param int $userID
     */
    public static function createWallet($userID)
    {
        Wallet::create(
            [
                'user_id' => $userID
            ]
        );
    }

    /**
     * Remove user's Wallet
     *
     * @param int $userID
     */
    public static function removeWallet($userID)
    {
        Wallet::where('user_id', $userID)->delete();
    }

    /**
     * Get currency
     *
     * @param int $currency
     *
     * @return string
     */
    public static function getCurrency($currency)
    {
        return self::$currencies[$currency] ?? '';

    }

    /**
     * Get currencies list
     *
     * @return array
     */
    public static function getCurrencies()
    {
        $currencies = [];

        foreach (self::$currencies as $currencyID => $currencyName) {
            $currencies[] = ['id' => $currencyID, 'name' => $currencyName];
        }

        return $currencies;
    }

    /**
     * Add ZNX from ICO pull
     *
     * @param string $userUID
     * @param int $amount
     *
     * @return int
     * @throws
     */
    public static function addIcoZnx($userUID, $amount)
    {
        $user = AccountsService::getUserByID($userUID);

        TransactionsService::createAddIcoZnxTransaction($user->id, $amount);

        return self::updateZnxAmount($user->wallet, $amount);
    }

    /**
     * Add ZNX from ICO pull
     *
     * @param string $userUID
     * @param int $amount
     *
     * @return int
     * @throws
     */
    public static function addFoundationZnx($userUID, $amount)
    {
        $user = AccountsService::getUserByID($userUID);

        TransactionsService::createAddFoundationZnxTransaction($user->id, $amount);

        return self::updateZnxAmount($user->wallet, $amount);
    }

    /**
     * Get info about user's wallet
     *
     * @param User $user
     *
     * @return array
     */
    public static function getInfo($user) {
        $wallet = $user->wallet;

        $icoInfo = (new IcoService())->getInfo();

        $ethRate = $icoInfo['ethRate'];
	    $euroRate = $icoInfo['euroRate'];
	    $usdRate = $icoInfo['usdRate'];
        $endDate = $icoInfo['endDate'];
        $icoPartName = $icoInfo['name'];

        $userTransactions = TransactionsService::getUserTransactions($user);

        $ethAddressAction = EthAddressAction::where('user_id', $user->id)->get()->last();

        $availableZnxAmount = (new CurrencyFormatter($wallet->znx_amount))->znxFormat()->withSuffix('ZNX tokens')->get();

        return [
            'wallet' => $wallet,
            'availableAmount' => $availableZnxAmount,
            'gettingAddress' => optional($ethAddressAction)->status === EthAddressAction::STATUS_IN_PROGRESS,
            'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
            'ico' => [
                'eth_rate' => (new CurrencyFormatter($ethRate))->ethFormat()->get(),
	            'euro_rate' => $euroRate,
	            'usd_rate' => $usdRate,
                'end_date' => $endDate ? date('Y/m/d H:i:s', strtotime($endDate)) : '',
                'part_name' => $icoPartName
            ],
            'transactions' => $userTransactions,
            'showWelcome' => $user->status == User::USER_STATUS_PENDING
        ];
    }

    /**
     * Update ETH address
     *
     * @param User $user
     * @param string $address
     */
    public static function updateEtheriumAddress($wallet, $address)
    {
        $wallet->eth_wallet = $address;
        $wallet->save();
    }

    /**
     * Update Debit Card bonus
     *
     * @param Wallet $wallet
     * @param int $amount
     */
    public static function updateDebitCardBonus($wallet, $amount)
    {
        $wallet->debit_card_bonus = $amount;
        $wallet->save();
    }

    /**
     * Update Referral Bonus
     *
     * @param Wallet $wallet
     * @param int $amount
     */
    public static function updateReferralBonus($wallet, $amount)
    {
        $wallet->referral_bonus += $amount;
        $wallet->save();
    }

    /**
     * Update Commission Bonus
     *
     * @param Wallet $wallet
     * @param int $amount
     */
    public static function updateCommissionBonus($wallet, $amount)
    {
        $wallet->commission_bonus += $amount;
        $wallet->save();
    }

    /**
     * Set Commission Bonus to 0
     *
     * @param Wallet $wallet
     */
    public static function zeroCommissionBonus($wallet)
    {
        $wallet->commission_bonus = 0;
        $wallet->save();
    }

    /**
     * Transfer Commission Bonus to ZNX
     *
     * @param Wallet $wallet
     * @param float $commissionAmount
     * @param int $znxAmount
     */
    public static function transferCommissionBonus($wallet, $commissionAmount, $znxAmount)
    {
        self::updateCommissionBonus($wallet, -$commissionAmount);

        self::updateZnxAmount($wallet, $znxAmount);
    }

    /**
     * Update ZNX amount
     *
     * @param Wallet $wallet
     * @param int $amount
     *
     * @return int
     */
    protected static function updateZnxAmount($wallet, $amount)
    {
        $wallet->znx_amount += $amount;
        $wallet->save();

        return $wallet->znx_amount;
    }

}

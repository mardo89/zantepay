<?php

namespace App\Console\Commands;

use App\Models\DB\EthRate;
use Illuminate\Console\Command;

class UpdateEthRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eth_rate:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ETH rate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rateJson = file_get_contents('https://api.coinmarketcap.com/v1/ticker/ethereum/?convert=EUR');

        if ($rateJson !== false) {
            $rateArr = json_decode($rateJson);

            $rateObj = array_pop($rateArr);

            if (!is_null($rateObj) && isset($rateObj->price_eur)) {
                $currentRate = EthRate::where('currency_type', EthRate::CURRENCY_TYPE_EURO)->first();

                if (is_null($currentRate)) {
                    EthRate::create(
                        [
                            'currency_type' => EthRate::CURRENCY_TYPE_EURO,
                            'rate' => $rateObj->price_eur
                        ]
                    );
                } else {
                    if ($currentRate->rate != $rateObj->price_eur) {
                        $currentRate->rate = $rateObj->price_eur;
                        $currentRate->save();
                    }
                }
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\DB\EthRate;
use App\Models\DB\UsdRate;
use Illuminate\Console\Command;

class UpdateUsdRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usd_rate:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update USD rate';

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
        $rateJson = file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q=EUR_USD&compact=y');

        if ($rateJson !== false) {

            $rateObj = json_decode($rateJson);

            if (!is_null($rateObj) && isset($rateObj->EUR_USD) && isset($rateObj->EUR_USD->val)) {
                $currentRate = UsdRate::where('currency_type', UsdRate::CURRENCY_TYPE_EURO)->first();
                $newRate = $rateObj->EUR_USD->val;

                if (is_null($currentRate)) {
                    UsdRate::create(
                        [
                            'currency_type' => UsdRate::CURRENCY_TYPE_EURO,
                            'rate' => $newRate
                        ]
                    );
                } else {
                    if ($currentRate->rate != $newRate) {
                        $currentRate->rate = $newRate;
                        $currentRate->save();
                    }
                }
            }
        }
    }
}

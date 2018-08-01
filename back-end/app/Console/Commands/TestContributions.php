<?php

namespace App\Console\Commands;

use App\Models\DB\Contribution;
use App\Models\DB\ContributionAction;
use App\Models\DB\User;
use App\Models\DB\Wallet;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Services\MailService;
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestContributions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contributions:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test contributions';

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
        DB::beginTransaction();

        try {
            $ico = new Ico();


            $contributions = EtheriumApi::getContributions(0, 5487620);

            if (count($contributions['contributions']) > 0) {

                // add contributions to the DB
                foreach ($contributions['contributions'] as $contribution) {

                    $ethAmount = RateCalculator::weiToEth($contribution->amount);

                    $znxAmountParts = RateCalculator::ethToZnx($ethAmount, $contribution->timestamp, $ico);

                    $userWallet = Wallet::where('eth_wallet', $contribution->proxy)->first();

                    if (!is_null($userWallet)) {

                        // Apply Commission bonus
                        $user = $userWallet->user;

                        if (!is_null($user->referrer)) {
                            $userReferrer = User::find($user->referrer);

                            $referrerWallet = $userReferrer->wallet;

                        }

                    }
                }

            }

        } catch (\Exception $e) {

            DB::rollback();

            $this->error($e->getMessage());

        }

        DB::commit();
    }
}

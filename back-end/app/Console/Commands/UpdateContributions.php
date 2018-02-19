<?php

namespace App\Console\Commands;

use App\Mail\SystemAlert;
use App\Models\DB\Contribution;
use App\Models\DB\ContributionAction;
use App\Models\DB\Wallet;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Wallet\Currency;
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\RateCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;



class UpdateContributions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contributions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read contributions from API and add them to the DB';

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
            $totalZNX = ZantecoinTransaction::all()->sum('amount');

            $continuationToken = 0;

            $lastContributionAction = ContributionAction::all()->last();

            if (!is_null($lastContributionAction)) {
                $continuationToken = $lastContributionAction->continuation_token;
            }

            $contributions = EtheriumApi::getContributions($continuationToken);

            if (count($contributions['contributions']) > 0) {

                // add contributions to the DB
                foreach ($contributions['contributions'] as $contribution) {
                    Contribution::create(
                        [
                            'operation_id' => $contribution->operationId,
                            'proxy' => $contribution->proxy,
                            'amount' => $contribution->amount
                        ]
                    );

                    $ethAmount = $contribution->amount / 10000000000000000000;

                    $znxAmount = RateCalculator::ethToZnx($ethAmount, $totalZNX);

                    $userWallet = Wallet::where('eth_wallet', $contribution->proxy)->first();

                    if (!is_null($userWallet)) {

                        ZantecoinTransaction::create(
                            [
                                'user_id' => $userWallet->user->id,
                                'amount' => $znxAmount,
                                'currency' => Currency::CURRENCY_TYPE_ETH
                            ]
                        );

                        $totalZNX += $znxAmount;
                    }
                }

                // Save Continuation Token
                ContributionAction::create(
                    [
                        'action_type' => ContributionAction::ACTION_TYPE_UPDATE,
                        'continuation_token' => $contributions['continuation_token']
                    ]
                );

            }

        } catch (\Exception $e) {

            DB::rollback();

            $errorMessage = $e->getMessage();

            Mail::send(new SystemAlert('Update Contributions Error', $errorMessage));

        }

        DB::commit();
    }
}

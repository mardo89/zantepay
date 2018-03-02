<?php

namespace App\Console\Commands;

use App\Mail\SystemAlert;
use App\Models\DB\Contribution;
use App\Models\DB\ContributionAction;
use App\Models\DB\Wallet;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\Ico;
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
            $ico = new Ico();

            $lastContributionOperation = ContributionAction::all()->last();

            $continuationToken = optional($lastContributionOperation)->continuation_token ?? null;

            $contributions = EtheriumApi::getContributions($continuationToken);

            if (count($contributions['contributions']) > 0) {

                // add contributions to the DB
                foreach ($contributions['contributions'] as $contribution) {
                    $contribution = Contribution::create(
                        [
                            'operation_id' => $contribution->operationId,
                            'proxy' => $contribution->proxy,
                            'amount' => $contribution->amount,
                            'time_stamp' => $contribution->timeStamp
                        ]
                    );

                    $ethAmount = RateCalculator::weiToEth($contribution->amount);

                    $znxAmountParts = RateCalculator::ethToZnx($ethAmount, $contribution->timestamp, $ico);

                    $userWallet = Wallet::where('eth_wallet', $contribution->proxy)->first();

                    if (!is_null($userWallet)) {

                        foreach ($znxAmountParts as $znxAmountPart) {
                            ZantecoinTransaction::create(
                                [
                                    'user_id' => $userWallet->user->id,
                                    'amount' => $znxAmountPart['amount'],
                                    'ico_part' => $znxAmountPart['icoPart'],
                                    'contribution_id' => $contribution->id,
                                    'transaction_type' => ZantecoinTransaction::TRANSACTION_ETH_TO_ZNX
                                ]
                            );

                        }

                    }
                }

                // Save information about contribution operation
                ContributionAction::create(
                    [
                        'contributions_found' => count($contributions['contributions']),
                        'action_type' => ContributionAction::ACTION_TYPE_UPDATE,
                        'continuation_token' => $contributions['continuation_token'],
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

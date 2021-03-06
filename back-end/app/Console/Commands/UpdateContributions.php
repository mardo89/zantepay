<?php

namespace App\Console\Commands;

use App\Models\DB\Contribution;
use App\Models\DB\ContributionAction;
use App\Models\DB\User;
use App\Models\DB\Wallet;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Services\AffiliatesService;
use App\Models\Services\MailService;
use App\Models\Wallet\CurrencyFormatter;
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
                    $contributionInfo = Contribution::create(
                        [
                            'operation_id' => $contribution->operationId,
                            'proxy' => $contribution->proxy,
                            'amount' => $contribution->amount,
                            'time_stamp' => $contribution->timeStamp
                        ]
                    );

                    $ethAmount = RateCalculator::weiToEth($contributionInfo->amount);

                    $znxAmountParts = RateCalculator::ethToZnx($ethAmount, $contributionInfo->time_stamp, $ico);

                    $userWallet = Wallet::where('eth_wallet', $contributionInfo->proxy)->first();

                    if (!is_null($userWallet)) {
                        $user = $userWallet->user;

                        $totalZnx = 0;

                        // Create Transactions
                        foreach ($znxAmountParts as $znxAmountPart) {
                            ZantecoinTransaction::create(
                                [
                                    'user_id' => $user->id,
                                    'amount' => $znxAmountPart['amount'],
                                    'ico_part' => $znxAmountPart['icoPart'],
                                    'contribution_id' => $contributionInfo->id,
                                    'transaction_type' => ZantecoinTransaction::TRANSACTION_ETH_TO_ZNX
                                ]
                            );

                            $totalZnx += $znxAmountPart['amount'];
                        }

                        MailService::sendTokenSaleEmail(
                            $user->email,
                            (new CurrencyFormatter($totalZnx))->znxFormat()->withSuffix('ZNX')->get(),
                            (new CurrencyFormatter($ethAmount))->ethFormat()->withSuffix('ETH')->get()
                        );

                        // Apply Commission bonus
                        if (!is_null($user->referrer)) {
                            $userReferrer = User::find($user->referrer);

                            $referrerWallet = $userReferrer->wallet;

                            $referrerWallet->commission_bonus += Wallet::COMMISSION_BONUS * $ethAmount;
                            $referrerWallet->save();
                        }

	                    // Apply Affiliate bonus
	                    if (!is_null($user->affiliate)) {
                        	AffiliatesService::trackCPA($user->affiliate, $ethAmount);
	                    }

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

        } catch (\Exception $e) {

            DB::rollback();

            MailService::sendSystemAlertEmail('Update Contributions Error', $e->getMessage());

        }

        DB::commit();
    }
}

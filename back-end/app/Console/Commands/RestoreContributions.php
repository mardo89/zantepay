<?php

namespace App\Console\Commands;

use App\Models\DB\Contribution;
use App\Models\DB\ContributionAction;
use App\Models\DB\Wallet;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Services\MailService;
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RestoreContributions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contributions:restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore contributions DB using API';

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

            $this->removeZnxTransactions();

            $this->removeContributions();

            $this->restoreContributions();

            $this->checkContributions();

        } catch (\Exception $e) {

            DB::rollback();

            MailService::sendSystemAlertEmail('Update Contributions Error', $e->getMessage());

        }

        DB::commit();
    }

    /**
     * Remove ZNX transactions
     */
    protected function removeZnxTransactions()
    {
        $this->info('Remove Zantecoin transactions ...');

        ZantecoinTransaction::where('transaction_type', ZantecoinTransaction::TRANSACTION_ETH_TO_ZNX)->delete();

        $this->info('done!');
        $this->info('');
        $this->info('');
    }

    /**
     * Remove contributions
     */
    protected function removeContributions()
    {
        $this->info('Remove Contributions ...');

        Contribution::truncate();

        $this->info('done!');
        $this->info('');
        $this->info('');
    }

    /**
     * Restore contributions
     */
    protected function restoreContributions()
    {
        $this->info('Restore Contributions ...');

        $ico = new Ico();

        $lastContributionOperation = ContributionAction::all()->last();

        $continuationToken = optional($lastContributionOperation)->continuation_token ?? 0;

        $contributions = EtheriumApi::getContributions(null, $continuationToken);

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
                    'action_type' => ContributionAction::ACTION_TYPE_RESTORE,
                    'continuation_token' => $continuationToken,
                ]
            );

        }

        $this->info('done!');
        $this->info('');
        $this->info('');
    }

    /**
     * Check contributions
     */
    protected function checkContributions()
    {
        $this->info('Check Contributions ...');

        $lastContributionOperation = ContributionAction::all()->last();

        $continuationToken = optional($lastContributionOperation)->continuation_token ?? 0;

        $apiContributions = EtheriumApi::getContributions(0, $continuationToken);

        $wallets = Wallet::all();

        $dbContributions = [];

        foreach (Contribution::all() as $contribution) {
            $dbContributions[$contribution->proxy] = $contribution->amount;
        }


        foreach ($apiContributions['contributions'] as $contribution) {
            $userWallet = $wallets->where('eth_wallet', $contribution->proxy)->first();

            if (!$userWallet) {
                continue;
            }

            $isContributionCorrect = isset($dbContributions[$contribution->proxy]) && $dbContributions[$contribution->proxy] == $contribution->amount;

            if (!$isContributionCorrect) {
                $message = $userWallet->user->id . ' | ' . date('d-m-Y H:i:s', $contribution->timeStamp) . ' | ' . $contribution->amount;

                $this->info($message);
            }
        }

        $this->info('done!');
    }
}

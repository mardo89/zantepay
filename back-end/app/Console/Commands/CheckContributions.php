<?php

namespace App\Console\Commands;

use App\Mail\SystemAlert;
use App\Models\DB\Contribution;
use App\Models\DB\ContributionAction;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Wallet\EtheriumApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;



class CheckContributions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contributions:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check contributions state. Truncate contribution table if state is incorrect';

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
        $continuationToken = 0;

        DB::beginTransaction();

        try {
            $lastContributionAction = ContributionAction::all()->last();

            if (!is_null($lastContributionAction)) {
                $continuationToken = $lastContributionAction->continuation_token;
            }

            $allContributions = EtheriumApi::getContributions(0, $continuationToken);

            $apiContributions = [];

            foreach ($allContributions['contributions'] as $contribution) {
                if (!isset($apiContributions[$contribution->proxy])) {
                    $apiContributions[$contribution->proxy] = 0;
                }

                $apiContributions[$contribution->proxy] += $contribution->amount;
            }

            $dbContributions = Contribution::groupBy('proxy')
                ->get(
                    [
                        'proxy', DB::Raw("SUM(amount) AS total_amount")
                    ]
                )
                ->toArray();

            $isContributionsCorrect = true;

            foreach ($dbContributions as $dbContribution) {
                $proxy = $dbContribution['proxy'];
                $totalAmount = $dbContribution['total_amount'];

                if (!isset($apiContributions[$proxy]) || $apiContributions[$proxy] != $totalAmount) {
                    $isContributionsCorrect = false;
                }
            }

            if (!$isContributionsCorrect) {

                ZantecoinTransaction::truncate();

                Contribution::truncate();

                ContributionAction::create(
                    [
                        'action_type' => ContributionAction::ACTION_TYPE_CORRECT,
                        'continuation_token' => 0
                    ]
                );

            }

        } catch (\Exception $e) {

            DB::rollback();

            $errorMessage = $e->getMessage();

            Mail::send(new SystemAlert('Check Contributions Error', $errorMessage));

        }

        DB::commit();

    }
}

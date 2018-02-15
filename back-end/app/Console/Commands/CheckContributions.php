<?php

namespace App\Console\Commands;

use App\Models\DB\Contribution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
    protected $description = 'Check contributions status. Rebuild contributions table if necessary';

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
            $allContributions = EtheriumApi::getContributions();

            $apiContributions = [];

            foreach ($allContributions as $contribution) {
                if (!isset($apiContributions[$contribution->proxy])) {
                    $apiContributions[$contribution->proxy] = 0;
                }

                $apiContributions[$contribution->proxy] += $contribution->amount;
            }

            $dbContributions = Contribution::groupBy('proxy')->get(['proxy', DB::Raw("SUM('amount') AS totlal_amount")]);

            $isContributionsCorrect = true;

            foreach ($dbContributions as $dbContribution) {
                if (!isset($apiContributions[$dbContribution->proxy]) || $apiContributions[$dbContribution->proxy] != $dbContribution->total_amount) {
                    $isContributionsCorrect = false;
                }
            }


        } catch (\Exception $e) {

            DB::rollback();

        }

        DB::commit();

    }
}

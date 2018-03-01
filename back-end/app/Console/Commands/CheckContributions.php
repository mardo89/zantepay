<?php

namespace App\Console\Commands;

use App\Mail\CheckContributions as CheckContributionMail;
use App\Models\DB\Contribution;
use App\Models\DB\ContributionAction;
use App\Models\DB\Wallet;
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
        DB::beginTransaction();

        try {
            $lastContributionOperation = ContributionAction::all()->last();

            $continuationToken = optional($lastContributionOperation)->continuation_token ?? 0;

            $apiContributions = EtheriumApi::getContributions(0, $continuationToken);

            $wallets =  Wallet::all();

            $dbContributions = [];

            foreach (Contribution::all() as $contribution) {
                $dbContributions[$contribution->proxy] = $contribution->amount;
            }

            $incorrectContributions = [];

            foreach ($apiContributions['contributions'] as $contribution) {
                $userWallet = $wallets->where('eth_wallet', $contribution->proxy)->first();

                if (!$userWallet) {
                    continue;
                }

                $isContributionCorrect = isset($dbContributions[$contribution->proxy]) && $dbContributions[$contribution->proxy] == $contribution->amount;

                if (!$isContributionCorrect) {
                    $incorrectContributions[] = [
                        'user_id' => $userWallet->user->id,
                        'proxy' => $contribution->proxy,
                        'date' => date('d-m-Y H:i:s', $contribution->timeStamp),
                        'api_amount' => $contribution->amount,
                        'db_amount' => isset($dbContributions[$contribution->proxy]) ? $dbContributions[$contribution->proxy] : 'NULL',
                    ];
                }
            }

            if (count($incorrectContributions) > 0) {
                Mail::send(new CheckContributionMail($incorrectContributions));
            }

        } catch (\Exception $e) {

            DB::rollback();

            $errorMessage = $e->getMessage();

            Mail::send(new SystemAlert('Check Contributions Error', $errorMessage));

        }

        DB::commit();

    }
}

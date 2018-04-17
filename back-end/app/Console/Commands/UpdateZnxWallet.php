<?php

namespace App\Console\Commands;

use App\Models\DB\User;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Services\MailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class UpdateZnxWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update ZNX amount';

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
            $znxTransactions = ZantecoinTransaction::all();

            $znxAmount = [];

            // create list of user ZNX amounts
            foreach ($znxTransactions as $transaction) {

                if (!isset($znxAmount[$transaction->user_id])) {
                    $znxAmount[$transaction->user_id] = 0;
                }

                $znxAmount[$transaction->user_id] += $transaction->amount;
            }

            foreach (User::with('wallet')->get() as $user) {
                $wallet = $user->wallet;

                if (!$wallet) {
                    continue;
                }

                $transactionsAmount = $znxAmount[$user->id] ?? 0;

                // correct ZNX amount
                if ($wallet->znx_amount != $transactionsAmount) {

                    $wallet->znx_amount = $transactionsAmount;

                    $wallet->save();
                }
            }

        } catch (\Exception $e) {

            DB::rollback();

            MailService::sendSystemAlertEmail('Update ZNX Wallet Error', $e->getMessage());

        }

        DB::commit();
    }
}

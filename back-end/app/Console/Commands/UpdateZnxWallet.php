<?php

namespace App\Console\Commands;

use App\Mail\SystemAlert;
use App\Models\DB\User;
use App\Models\DB\ZantecoinTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


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

            // check ZNX amount in the wallets
            foreach ($znxAmount as $userID => $amount) {
                $user = User::find($userID);

                if (is_null($user)) {
                    continue;
                }

                $userWallet = $user->wallet;

                if (is_null($userWallet)) {
                    continue;
                }

                // correct ZNX amount
                if ($userWallet->znx_amount != $amount) {

                    $userWallet->znx_amount = $amount;

                    $userWallet->save();

                }
            }

        } catch (\Exception $e) {

            DB::rollback();

            $errorMessage = $e->getMessage();

            Mail::send(new SystemAlert('Update ZNX Wallet Error', $errorMessage));

        }

        DB::commit();
    }
}

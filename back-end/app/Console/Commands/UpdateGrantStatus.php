<?php

namespace App\Console\Commands;

use App\Models\DB\GrantCoinsTransaction;
use App\Models\Services\EtheriumService;
use App\Models\Wallet\EtheriumApi;
use Illuminate\Console\Command;


class UpdateGrantStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grant_tokens_status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status for in progress transactions';

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

        try {

        	$transactions = GrantCoinsTransaction::whereNotNull('operation_id')->where('status', GrantCoinsTransaction::STATUS_IN_PROGRESS)->get();

            foreach ($transactions as $transaction) {

	            $transactionStatus = EtheriumApi::checkCoinsStatus($transaction->operation_id);

	            switch ($transactionStatus) {
		            case 'success':
			            $transaction->status = GrantCoinsTransaction::STATUS_COMPLETE;
			            break;

		            case 'failure':
			            $transaction->status = GrantCoinsTransaction::STATUS_FAILED;
			            break;

		            default:
			            $transaction->status = GrantCoinsTransaction::STATUS_IN_PROGRESS;
	            }

	            $transaction->save();

                sleep(5);

            }

        } catch (\Exception $e) {

            $this->error('================= Error =================');
            $this->error($e->getMessage());

        }

    }
}

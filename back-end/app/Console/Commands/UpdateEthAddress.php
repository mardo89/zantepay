<?php

namespace App\Console\Commands;

use App\Models\DB\EthAddressAction;
use App\Models\DB\User;
use App\Models\DB\Wallet;
use App\Models\Services\EtheriumService;
use Illuminate\Console\Command;


class UpdateEthAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eth_address:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new ETH addresses for users who has such address';

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

            $wallets = Wallet::whereNotNull('eth_wallet')->get();

            foreach ($wallets as $wallet) {

                $user = $wallet->user;

                $this->info($user->email);

                // delete previous operation
                EthAddressAction::where('user_id', $user->id)->delete();

                // create new address
                EtheriumService::createAddress($user);

                sleep(10);

            }

        } catch (\Exception $e) {

            $this->error('================= Error =================');
            $this->error($e->getMessage());

        }

    }
}

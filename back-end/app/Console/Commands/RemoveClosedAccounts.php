<?php

namespace App\Console\Commands;

use App\Models\DB\User;
use App\Models\Services\AccountsService;
use App\Models\Services\MailService;
use Illuminate\Console\Command;

class RemoveClosedAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:remove-closed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove closed account.';

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
        $usersToDelete = User::where('status', User::USER_STATUS_CLOSED)->get();

        foreach ($usersToDelete as $user) {

	        try {

		        AccountsService::removeUser($user->uid);

	        } catch (\Exception $e) {

		        MailService::sendSystemAlertEmail('Error while deleting users.', $e->getMessage());

	        }
        }

    }
}

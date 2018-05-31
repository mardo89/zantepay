<?php

namespace App\Console\Commands;

use App\Models\DB\Verification;
use App\Models\Services\VerificationService;
use Illuminate\Console\Command;

class ResetVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset verification with status In-progress';

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
        $verifications = Verification::where('status', Verification::VERIFICATION_IN_PROGRESS)
            ->where('updated_at', '<' , date('Y-m-d H:i:s', strtotime('-6 hour')) )
            ->get();

        foreach ($verifications as $verification) {
            VerificationService::resetVerification($verification->user);
        }

    }
}

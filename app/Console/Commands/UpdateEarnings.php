<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Account;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UpdateEarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:earnings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update earnings for accounts with active trades';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        try {
            $accounts = Account::where('trade', true)->get();

            foreach ($accounts as $account) {
                $earningsIncrement = 0;

                switch ($account->account_stage) {
                    case 'bronze':
                        $earningsIncrement = 0.20;
                        break;
                    case 'silver':
                        $earningsIncrement = 0.40;
                        break;
                    case 'gold':
                        $earningsIncrement = 0.70;
                        break;
                    case 'premium':
                        $earningsIncrement = 1.00;
                        break;
                }

                // Calculate time elapsed since the last update
                $lastUpdate = $account->updated_at ?? now(); // Use updated_at or current time if not available
                $timeElapsed = now()->diffInMinutes($lastUpdate);

                // Update earnings based on the percentage and time elapsed
                $earningsIncrease =     $earningsIncrement * $timeElapsed;

                // Update the earnings
                $account->increment('earning', $earningsIncrease);
            }

            $this->info('Earnings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update earnings', ['error' => $e->getMessage(), 'trace' => $e->getTrace()]);
            $this->error('Failed to update earnings. Check the logs for more information.');
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Account;
use App\Models\Plan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Notifications\EarningsUpdated;

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



    private function checkOverdueTrades()
    {
        $accounts = Account::where('trade', true)->get();

        foreach ($accounts as $account) {
            // Check if trade_changed_at is set and overdue
            if ($account->trade_changed_at !== null) {
                $plan = Plan::where('plan', $account->account_stage)->first();

                if ($plan && $plan->duration !== null) {
                    $dueDate = Carbon::parse($account->trade_changed_at)->addDays($plan->duration);

                    if (now()->gte($dueDate)) {
                        // Turn off the trade
                        $account->update(['trade' => false]);
                        $this->info("Trade turned off for Account ID: {$account->id}");
                    }
                }
            }
        }
    }

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // Call the checkOverdueTrades method with the specified due times
        $this->checkOverdueTrades();

        try {
            $accounts = Account::where('trade', true)->get();

            foreach ($accounts as $account) {
                // Get the plan for the current account
                $plan = Plan::where('plan', $account->account_stage)->first();

                if ($plan) {
                    // Calculate time elapsed since the last update
                    $lastUpdate = $account->updated_at ?? now();
                    $timeElapsed = now()->diffInMinutes($lastUpdate);

                    // Update earnings based on the plan's percent and time elapsed
                    $earningsIncrease = $plan->percent * $timeElapsed / 100;

                    // Update the earnings
                    $account->increment('earning', $earningsIncrease);


                    // Send the earnings updated notification to the user
                    $account->user->notify(new EarningsUpdated($account->earning));
                }
            }

            $this->info('Earnings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update earnings', ['error' => $e->getMessage(), 'trace' => $e->getTrace()]);
            $this->error('Failed to update earnings. Check the logs for more information.');
        }
    }
}

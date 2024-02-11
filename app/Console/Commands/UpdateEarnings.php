<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Account;
use App\Models\Plan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Notifications\EarningsUpdated;
use App\Models\Earning;

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


    private function generateRandomValue($value)
    {
        // Calculate the range based on the given value and a 10% variance
        $min = max(1, $value - (0.1 * $value));
        $max = $value + (0.1 * $value);

        // Generate a random value within the calculated range
        $randomValue = rand($min, $max);

        // Ensure the random value is never 0
        return max(1, $randomValue);
    }

    public function saveEarning($amount, $balance, $user_id)
    {
        // Create a new instance of the Earning model
        $earning = new Earning();

        // Set the values for the attributes
        $earning->amount = $amount;
        $earning->balance = $balance;
        $earning->user_id = $user_id;

        // Save the earning to the database
        $earning->save();

        // Optionally, you can return the saved earning
        // return $earning;
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
                    $earningsIncrease = $this->generateRandomValue($plan->percent / 100 * $account->balance);

                    //save to earning table
                    $this->saveEarning($earningsIncrease, $account->earning + $earningsIncrease, $account->user_id);

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

<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Tests\TestCase;

class UpdateEarningsTest extends TestCase
{
    use RefreshDatabase, InteractsWithDatabase;

    /** @test */
    public function it_updates_earnings_based_on_plan_percent()
    {
        // Arrange
        $account = Account::factory()->create([
            'trade' => true,
            'account_stage' => 'bronze', // Replace with your desired account_stage
            'updated_at' => now()->subMinutes(60), // Simulate an account updated 60 minutes ago
        ]);

        Plan::factory()->create([
            'plan' => 'bronze', // Make sure it matches the account_stage
            'percent' => 0.30,
            'duration' => 30,
        ]);

        // Act
        $this->artisan('update:earnings');

        // Assert
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'earning' => 18.0, // 0.30 * 60
        ]);
    }

    /** @test */
    public function it_turns_off_overdue_trades()
    {
        // Arrange
        $account = Account::factory()->create([
            'trade' => true,
            'account_stage' => 'bronze', // Replace with your desired account_stage
            'trade_changed_at' => now()->subDays(45), // Simulate trade_changed_at 45 days ago
        ]);

        Plan::factory()->create([
            'plan' => 'bronze', // Make sure it matches the account_stage
            'duration' => 30,
        ]);

        // Act
        $this->artisan('update:earnings');

        // Assert
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'trade' => false,
        ]);
    }

    // Add more test cases for different scenarios as needed
}

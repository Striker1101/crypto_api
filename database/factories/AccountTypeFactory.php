<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountType>
 */
class AccountTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'spreads' => $this->faker->randomFloat(2, 0.1, 5),
            'leverage' => $this->faker->randomElement(['1:100', '1:200', '1:500', '1:1000']),
            'scalping' => $this->faker->boolean(),
            'negative_balance_protection' => $this->faker->boolean(),
            'stop_out' => $this->faker->randomElement(['20%', '30%', '50%']),
            'swap_free' => $this->faker->boolean(),
            'minimum_trade_volume' => $this->faker->randomFloat(2, 0.01, 1),
            'hedging_allowed' => $this->faker->boolean(),
            'daily_signals' => $this->faker->boolean(),
            'video_tutorials' => $this->faker->boolean(),
            'dedicated_account_manager' => $this->faker->boolean(),
            'daily_market_review' => $this->faker->boolean(),
            'financial_plan' => $this->faker->boolean(),
            'risk_management_plan' => $this->faker->boolean(),
        ];
    }
}

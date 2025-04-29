<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Automatically create a user
            'balance' => $this->faker->randomFloat(2, 100, 10000),
            'earning' => $this->faker->randomFloat(2, 50, 5000),
            'bonus' => $this->faker->randomFloat(2, 5, 500),
            'trade' => $this->faker->boolean,
            'account_type_id' => AccountType::factory(), // Correct field name (account_type_id)
            'account_stage' => $this->faker->randomElement(['beginner', 'bronze', 'silver', 'gold', 'premium']),
            'trade_changed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

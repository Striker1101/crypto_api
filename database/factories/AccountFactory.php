<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first(); // Get a random existing user

        return [
            'user_id' => $user->id,
            'balance' => $this->faker->randomFloat(2, 100, 10000),
            'earning' => $this->faker->randomFloat(2, 100, 10000),
            'bonus' => $this->faker->randomFloat(2, 100, 10000),
            'account_type' => $this->faker->randomElement(['trading', 'margin']),
            'account_stage' => $this->faker->randomElement(['bronze', 'silver', 'gold', 'premium']),
        ];
    }
}



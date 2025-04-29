<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'amount' => $this->faker->randomFloat(2, 100, 10000), // Random amount between 100 and 10000
            'support' => $this->faker->numberBetween(1, 5), // Random support count between 1 and 5
            'agent' => $this->faker->numberBetween(1, 5), // Random agent count between 1 and 5
            'type' => $this->faker->randomElement(['basic', 'premium', 'enterprise']), // Random plan type
            'percent' => $this->faker->numberBetween(1, 20), // Random percent between 1 and 20
            'duration' => $this->faker->numberBetween(30, 365), // Random duration between 30 and 365 days
            'owner_referral_id' => User::inRandomOrder()->value('id'), // Assign a random user as owner_referral
        ];
    }
}

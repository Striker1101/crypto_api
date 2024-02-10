<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Investment;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Investment>
 */
class InvestmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Investment::class;
    public function definition(): array
    {
        $user = User::inRandomOrder()->first(); // Get a random existing user

        return [
            'user_id' => $user->id,
            'amount' => $this->faker->randomFloat(2, 0, 100000),
            'plan' => $this->faker->randomElement(['beginner', 'bronze', 'silver', 'gold', 'premium']), // Randomly selects a plan
            'duration' => $this->faker->numberBetween(1, 12), // Random number between 1 and 12
        ];
    }
}

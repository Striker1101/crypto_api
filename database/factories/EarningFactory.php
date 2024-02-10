<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Earning;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Earning>
 */
class EarningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Earning::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first(); // Get a random existing user

        return [
            'user_id' => $user->id,
            'amount' => $this->faker->randomFloat(2, 0, 100000), // Generates a random float number with 2 decimal places
            'balance' => $this->faker->randomFloat(2, 0, 100000),
        ];
    }
}

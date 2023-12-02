<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class DepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Deposit::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'wallet_address' => $this->faker->unique()->uuid,
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'currency' => $this->faker->currencyCode,
            'status' => $this->faker->randomElement(['pending', 'completed']),
        ];
    }
}

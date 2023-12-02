<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Withdraw>
 */
class WithdrawFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Withdraw::class;

     public function definition()
     {
         return [
             'user_id' => \App\Models\User::factory(),
             'withdrawal_type' => $this->faker->randomElement(['crypto', 'bank_transfer']),
             'status' => $this->faker->boolean,
             'amount' => $this->faker->randomFloat(2, 50, 500),
             'name' => $this->faker->name,
             'currency' => $this->faker->currencyCode,
             'destination' => $this->faker->unique()->safeEmail, // Example using email as a placeholder
             'created_at' => now(),
             'updated_at' => now(),
         ];
     }
}

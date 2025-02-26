<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Wallet::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'address' => $this->faker->unique()->uuid(),
            'balance' => $this->faker->randomFloat(8, 0, 1000),
            'currency' => 'USD',
            'user_id' => User::factory(),
        ];
    }
}

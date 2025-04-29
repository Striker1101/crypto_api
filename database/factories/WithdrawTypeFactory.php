<?php

namespace Database\Factories;

use App\Models\WithdrawType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WithdrawType>
 */
class WithdrawTypeFactory extends Factory
{
    protected $model = WithdrawType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'image' => $this->faker->imageUrl(200, 200, 'business'),
            'symbol' => strtoupper($this->faker->currencyCode()),
            'currency' => $this->faker->currencyCode(),
            'type' => $this->faker->randomElement(['bank_transfer', 'crypto', 'others']), // match your table enum
            'min_limit' => $this->faker->randomFloat(2, 10, 100),
            'max_limit' => $this->faker->randomFloat(2, 500, 10000),
            'owner_referral_id' => User::inRandomOrder()->value('id'), // must be user id not uuid
            'details' => null, // you can fill it later if needed
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\DepositType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepositTypeFactory extends Factory
{
    protected $model = DepositType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'image' => $this->faker->imageUrl(200, 200, 'business'),
            'symbol' => strtoupper($this->faker->currencyCode()),
            'currency' => $this->faker->currencyCode(),
            'type' => $this->faker->randomElement(['bank_transfer', 'crypto', 'mobile', 'card', 'paypal', 'others']),
            'min_limit' => $this->faker->randomFloat(2, 10, 100),
            'max_limit' => $this->faker->randomFloat(2, 500, 10000),
            'owner_referral_id' => User::inRandomOrder()->value('id'), // correct reference to user ID
            'details' => [
                'note' => $this->faker->sentence(),
                'info' => $this->faker->text(50),
            ],
        ];
    }
}

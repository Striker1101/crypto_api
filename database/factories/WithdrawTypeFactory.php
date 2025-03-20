<?php

namespace Database\Factories;

use App\Models\DepositType;
use Illuminate\Database\Eloquent\Factories\Factory;

class WithdrawTypeFactory extends Factory
{
    protected $model = DepositType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'image' => $this->faker->imageUrl(200, 200, 'business'),
            'symbol' => strtoupper($this->faker->currencyCode),
            'currency' => $this->faker->currencyCode,
            'type' => $this->faker->randomElement(['bank', 'crypto', 'mobile']),
            'min_limit' => $this->faker->numberBetween(10, 100),
            'max_limit' => $this->faker->numberBetween(500, 10000),
            'owner_referral_id' => $this->faker->uuid,
        ];
    }
}

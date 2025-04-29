<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['stock', 'cryptocurrency', 'commodity']),
            'image_url' => $this->faker->imageUrl(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'owner_referral_id' => User::inRandomOrder()->value('id'), // Random user as referral, nullable by chance
        ];
    }
}

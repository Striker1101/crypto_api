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
        $user = User::inRandomOrder()->first(); // Get a random existing user

        return [
            'user_id' => $user->id,
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement(['stock', 'cryptocurrency', 'commodity']),
            'image_url' => $this->faker->imageUrl(),
            'image_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}


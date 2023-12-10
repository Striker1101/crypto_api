<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Address;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => $this->faker->boolean(70) ? now() : null,
            // 70% chance of being verified
            'password' => bcrypt('password'),
            // use Hash::make for production
            'phone_number' => $this->faker->phoneNumber,
            'active' => $this->faker->boolean,
            'street' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'zip_code' => $this->faker->postcode,
            'type' => $this->faker->randomElement(['user', 'admin']),
            'image_url' => $this->faker->imageUrl(),
            // Example image URL
            'image_id' => $this->faker->numberBetween(1, 100),
            // Example image ID
            'remember_token' => Str::random(10),
        ];
    }
}

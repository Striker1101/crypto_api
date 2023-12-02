<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KYC_Info>
 */
class KYCInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KycInfo::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'ssn' => $this->faker->unique()->numerify('#########'),
            // Add other KYC-related fields as needed
        ];
    }
}

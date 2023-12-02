<?php

namespace Database\Factories;

use App\Models\DebitCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DebitCard>
 */
class DebitCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DebitCard::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'card_number' => $this->faker->creditCardNumber,
            'expiration_date' => $this->faker->creditCardExpirationDate,
            'cvv' => $this->faker->randomNumber(3),
        ];
    }

}

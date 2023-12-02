<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'content' => $this->faker->sentence,
            'read' => $this->faker->boolean,
        ];
    }
}

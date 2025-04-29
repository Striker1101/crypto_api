<?php

namespace Database\Factories;

use App\Models\Deposit;
use App\Models\User;
use App\Models\DepositType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class DepositFactory extends Factory
{
    protected $model = Deposit::class;

    public function definition(): array
    {
        $depositType = DepositType::inRandomOrder()->first(); // fetch the deposit type first

        $details = null;
        if ($depositType)
        {
            if ($depositType->type === 'crypto')
            {
                $details = [
                    'network' => $this->faker->word(),
                    'wallet' => $this->faker->bothify('???###???###')
                ];
            } elseif ($depositType->type === 'bank_transfer')
            {
                $details = [
                    'bank_name' => $this->faker->company(),
                    'account_number' => $this->faker->bankAccountNumber(),
                    'account_name' => $this->faker->name(),
                    'routing_number' => $this->faker->swiftBicNumber()
                ];
            }
        }

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'deposit_type_id' => $depositType ? $depositType->id : null,
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'currency' => $this->faker->currencyCode(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'rejected', 'processing', 'upgrade']),
            'added' => $this->faker->boolean(),
            'image_url' => $this->faker->imageUrl(300, 300, 'business'),
            'owner_referral_id' => User::inRandomOrder()->value('id'),
            'details' => $details,
        ];
    }
}

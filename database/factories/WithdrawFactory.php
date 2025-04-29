<?php

namespace Database\Factories;

use App\Models\Withdraw;
use App\Models\User;
use App\Models\WithdrawType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Withdraw>
 */
class WithdrawFactory extends Factory
{
    protected $model = Withdraw::class;

    public function definition(): array
    {
        $withdrawType = WithdrawType::inRandomOrder()->first(); // get random withdraw type

        $details = null;
        if ($withdrawType)
        {
            if ($withdrawType->type === 'crypto')
            {
                $details = [
                    'network' => $this->faker->word(),
                    'wallet_address' => $this->faker->uuid(),
                ];
            } elseif ($withdrawType->type === 'bank_transfer')
            {
                $details = [
                    'bank_name' => $this->faker->company(),
                    'account_number' => $this->faker->bankAccountNumber(),
                    'account_name' => $this->faker->name(),
                    'routing_number' => $this->faker->swiftBicNumber(),
                ];
            } else
            {
                $details = [
                    'description' => $this->faker->sentence(),
                ];
            }
        }

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'withdrawal_type_id' => $withdrawType ? $withdrawType->id : null,
            'status' => $this->faker->randomElement(['pending', 'completed', 'rejected', 'processing', 'upgrade']),
            'added' => $this->faker->boolean(),
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'owner_referral_id' => User::inRandomOrder()->value('id'),
            'details' => $details,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

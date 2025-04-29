<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DepositType;

class DepositTypeSeeder extends Seeder
{
    public function run(): void
    {
        $depositTypes = [
            [
                'name' => 'Bank Transfer',
                'image' => 'bank.png',
                'symbol' => 'NGN',
                'currency' => 'Naira',
                'type' => 'manual',
                'min_limit' => 1000,
                'max_limit' => 1000000,
                'owner_referral_id' => null,
            ],
            [
                'name' => 'USDT TRC20',
                'image' => 'usdt-trc20.png',
                'symbol' => 'USDT',
                'currency' => 'USD',
                'type' => 'automatic',
                'min_limit' => 10,
                'max_limit' => 5000,
                'owner_referral_id' => null,
            ],
            [
                'name' => 'Bitcoin',
                'image' => 'btc.png',
                'symbol' => 'BTC',
                'currency' => 'BTC',
                'type' => 'automatic',
                'min_limit' => 0.001,
                'max_limit' => 2,
                'owner_referral_id' => null,
            ],
            [
                'name' => 'PayPal',
                'image' => 'paypal.png',
                'symbol' => 'USD',
                'currency' => 'USD',
                'type' => 'manual',
                'min_limit' => 5,
                'max_limit' => 1000,
                'owner_referral_id' => null,
            ],
            [
                'name' => 'Stripe Card',
                'image' => 'stripe.png',
                'symbol' => 'USD',
                'currency' => 'USD',
                'type' => 'automatic',
                'min_limit' => 10,
                'max_limit' => 2000,
                'owner_referral_id' => null,
            ],
        ];

        foreach ($depositTypes as $type)
        {
            DepositType::create($type);
        }
    }
}

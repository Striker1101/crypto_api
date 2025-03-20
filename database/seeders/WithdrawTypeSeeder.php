<?php

namespace Database\Seeders;

use App\Models\WithdrawType;
use Illuminate\Database\Seeder;

class WithdrawTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WithdrawType::create([
            'name' => 'Bank Transfer',
            'image' => null,
            'symbol' => '$',
            'currency' => 'USD',
            'type' => 'bank_transfer',
            'min_limit' => 100,
            'max_limit' => 5000,
            'owner_referral_id' => null,
        ]);

        WithdrawType::create([
            'name' => 'Crypto Withdrawal',
            'image' => null,
            'symbol' => 'BTC',
            'currency' => 'BTC',
            'type' => 'crypto',
            'min_limit' => 50,
            'max_limit' => 10000,
            'owner_referral_id' => null,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\WithdrawType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WithdrawTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        WithdrawType::create([
            'name' => 'Bank Transfer',
            'account_name' => 'John Doe',
            'account_number' => '1234567890',
            'bank_name' => 'Chase Bank',
            'routing_number' => '987654321',
            'code' => 'ABC123',
            'type' => 'bank',
            'min_limit' => 100,
            'max_limit' => 5000,
        ]);

        WithdrawType::create([
            'name' => 'Crypto Withdrawal',
            'wallet_address' => '0x1234abcd5678efgh',
            'type' => 'crypto',
            'min_limit' => 50,
            'max_limit' => 10000,
        ]);
    }
}

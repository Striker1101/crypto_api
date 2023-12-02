<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Account::factory(30)->create();
        // \App\Models\Asset::factory(10)->create();
        \App\Models\DebitCard::factory(10)->create();
        \App\Models\Deposit::factory(10)->create();
        \App\Models\KYCInfo::factory(10)->create();
        \App\Models\Notification::factory(10)->create();
        \App\Models\Withdraw::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

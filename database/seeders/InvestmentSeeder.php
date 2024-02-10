<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Investment;

class InvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve all user IDs
        $userIds = User::pluck('id')->toArray();

        // Seed data for the Investment model
        for ($i = 0; $i < 10; $i++) {
            Investment::create([
                'user_id' => $userIds[array_rand($userIds)], // Random user ID
                'amount' => rand(500, 1000), // Random amount between 500 and 1000
                'plan' => ['beginner', 'bronze', 'silver', 'gold', 'premium'][array_rand(['beginner', 'bronze', 'silver', 'gold', 'premium'])], // Random plan
                'duration' => rand(1, 12), // Random duration between 1 and 12
            ]);
        }
    }
}

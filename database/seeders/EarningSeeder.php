<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Earning;
use App\Models\User;
class EarningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Retrieve all user IDs
       $userIds = User::pluck('id')->toArray();

       // Seed data for the Earning model
       for ($i = 0; $i < 10; $i++) {
           Earning::create([
               'user_id' => $userIds[array_rand($userIds)], // Random user ID
               'amount' => rand(50, 200), // Random amount between 50 and 200
               'balance' => rand(100, 500), // Random balance between 100 and 500
           ]);
       }

    }
}

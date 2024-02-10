<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $plans = [
            ['plan' => 'beginner', 'percent' => 1, 'duration' => 30, 'max_account'=>50000],
            ['plan' => 'bronze', 'percent' => 3, 'duration' => 30, 'max_account'=> 100000],
            ['plan' => 'silver', 'percent' => 5, 'duration' => 30, 'max_account'=> 700000],
            ['plan' => 'gold', 'percent' => 8, 'duration' => 60], 'max_account'=> 1000000,
            ['plan' => 'premium', 'percent' => 10, 'duration' => 90, 'max_account'=> 20000000],
        ];

        // Insert data into the 'plans' table
        DB::table('plans')->insert($plans);
    }
}

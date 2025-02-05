<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpReasonsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $reasonStatuses = ['Approved', 'Rejected', 'Pending'];

        foreach (range(1, 10) as $index) {
            DB::connection('twp')->table('reasons')->insert([
                'reason_status' => $faker->randomElement($reasonStatuses),
                'title' => $faker->sentence(3),
                'letter_body' => $faker->paragraph(3),
                'active_flag' => $faker->boolean(90), // 90% chance of being active
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

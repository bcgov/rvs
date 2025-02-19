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
            $title = $faker->unique()->sentence(3);

            DB::connection('twp')->table('reasons')->updateOrInsert(
                ['title' => $title],
                [
                    'reason_status' => $faker->randomElement($reasonStatuses),
                    'letter_body' => $faker->paragraph(3),
                    'active_flag' => $faker->boolean(90), // 90% chance of being active
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

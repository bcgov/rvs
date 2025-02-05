<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class YeafProgramYearsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $currentYear = date('Y');

        for ($i = 0; $i < 5; $i++) {
            $yearStart = $currentYear + $i;
            $yearEnd = $yearStart + 1;

            DB::connection('yeaf')->table('program_years')->insert([
                'year_start' => (string)$yearStart,
                'year_end' => (string)$yearEnd,
                'grant_amount' => $faker->randomFloat(2, 1000, 5000),
                'max_years_allowed' => $faker->numberBetween(1, 5),
                'min_age' => $faker->numberBetween(16, 18),
                'max_age' => $faker->numberBetween(24, 30),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

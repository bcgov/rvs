<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebBursaryPeriodsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $startDate = now()->subYears(2)->startOfQuarter();

        for ($i = 0; $i < 8; $i++) {
            $periodStartDate = $startDate->copy()->addQuarters($i);
            $periodEndDate = $periodStartDate->copy()->addQuarters(1)->subDay();

            $exists = DB::connection('neb')
                ->table('bursary_periods')
                ->where('bursary_period_start_date', $periodStartDate)
                ->exists();

            if (!$exists) {
                DB::connection('neb')->table('bursary_periods')->insert([
                    'bursary_period_start_date' => $periodStartDate,
                    'bursary_period_end_date' => $periodEndDate,
                    'awarded' => $faker->boolean,
                    'default_award' => $faker->randomFloat(2, 1000, 5000),
                    'period_budget' => $faker->randomFloat(2, 100000, 1000000),
                    'rn_budget' => $faker->numberBetween(50, 500),
                    'public_sector_budget' => $faker->numberBetween(50, 500),
                    'budget_allocation_type' => $faker->randomElement(['Equal', 'Proportional', 'Custom']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

}

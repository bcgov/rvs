<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebPotentialRestrictionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $bursaryPeriodIds = DB::connection('neb')->table('bursary_periods')->pluck('id')->toArray();
        $studentSins = DB::connection('neb')->table('students')->pluck('sin')->toArray();

        foreach (range(1, 20) as $index) {
            $sin = $faker->randomElement($studentSins);
            $bursaryPeriodId = $faker->randomElement($bursaryPeriodIds);

            DB::connection('neb')->table('el_potential_restrictions')->updateOrInsert(
                ['sin' => $sin, 'bursary_period_id' => $bursaryPeriodId],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

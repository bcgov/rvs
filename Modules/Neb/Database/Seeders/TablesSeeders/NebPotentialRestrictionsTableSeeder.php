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
            DB::connection('neb')->table('el_potential_restrictions')->insert([
                'sin' => $faker->randomElement($studentSins),
                'bursary_period_id' => $faker->randomElement($bursaryPeriodIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

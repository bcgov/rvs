<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebElSinPySsdsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $sins = DB::connection('neb')->table('el_potentials')->pluck('sin')->toArray();

        if (empty($sins)) {
            $sins = DB::connection('neb')->table('students')->pluck('sin')->toArray();
        }

        foreach (range(1, 30) as $index) {
            $sin = $faker->randomElement($sins);

            DB::connection('neb')->table('el_sin_py_ssds')->updateOrInsert(
                ['sin' => $sin],
                [
                    'max_program_year' => $faker->randomElement(['1', '2', '3', '4', '5']),
                    'max_study_start_date' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

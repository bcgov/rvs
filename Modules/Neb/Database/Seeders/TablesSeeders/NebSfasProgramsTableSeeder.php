<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebSfasProgramsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $programCodes = DB::connection('neb')->table('programs')->pluck('program_code')->toArray();

        $nurseTypes = ['RN', 'LPN', 'NP'];
        $degreeLevels = ['Diploma', 'Bachelor', 'Master', 'Doctorate'];

        foreach (range(1, 20) as $index) {
            DB::connection('neb')->table('sfas_programs')->insert([
                'neb_program_code' => $faker->randomElement($programCodes),
                'sfas_program_code' => $faker->unique()->bothify('??##'),
                'area_of_study' => $faker->words(3, true),
                'degree_level' => $faker->randomElement($degreeLevels),
                'nurse_type' => $faker->randomElement($nurseTypes),
                'eligible' => $faker->boolean(80), // 80% chance of being eligible
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebProgramsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $programs = [
            'RN' => 'Registered Nurse',
            'LPN' => 'Licensed Practical Nurse',
            'NP' => 'Nurse Practitioner',
            'PSN' => 'Psychiatric Nurse',
            'MID' => 'Midwife',
            'HCA' => 'Health Care Assistant',
            'CNA' => 'Certified Nursing Assistant',
            'RNA' => 'Registered Nursing Assistant',
        ];

        foreach ($programs as $code => $description) {
            DB::connection('neb')->table('programs')->updateOrInsert(
                ['program_code' => $code],
                [
                    'program_description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Add some additional random programs
        for ($i = 1; $i <= 5; $i++) {
            $code = $faker->unique()->bothify('??#');
            DB::connection('neb')->table('programs')->updateOrInsert(
                ['program_code' => $code],
                [
                    'program_description' => $faker->sentence(3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

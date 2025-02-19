<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YeafProgramsTableSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ['program_code' => 'A', 'program_description' => 'Arts and Humanities'],
            ['program_code' => 'B', 'program_description' => 'Business and Economics'],
            ['program_code' => 'E', 'program_description' => 'Engineering and Technology'],
            ['program_code' => 'H', 'program_description' => 'Health Sciences'],
            ['program_code' => 'L', 'program_description' => 'Law and Legal Studies'],
            ['program_code' => 'N', 'program_description' => 'Natural Sciences'],
            ['program_code' => 'S', 'program_description' => 'Social Sciences'],
            ['program_code' => 'T', 'program_description' => 'Trades and Apprenticeships'],
        ];

        foreach ($programs as $program) {
            DB::connection('yeaf')->table('programs')->updateOrInsert(
                ['program_code' => $program['program_code']],
                [
                    'program_description' => $program['program_description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

        }
    }
}

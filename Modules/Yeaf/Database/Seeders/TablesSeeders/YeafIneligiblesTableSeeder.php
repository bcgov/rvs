<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class YeafIneligiblesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $ineligibles = [
            ['01', 'Age Requirement Not Met', 'A'],
            ['02', 'Incomplete Application', 'B'],
            ['03', 'Insufficient Academic Progress', 'C'],
            ['04', 'Financial Criteria Not Met', 'D'],
            ['05', 'Residency Requirement Not Met', 'A'],
            ['06', 'Program Not Eligible', 'B'],
            ['07', 'Previous Funding Limit Reached', 'C'],
            ['08', 'Disciplinary Action', 'D'],
            ['09', 'Fraudulent Information', 'E'],
            ['10', 'Missed Deadline', 'B'],
        ];

        foreach ($ineligibles as $ineligible) {
            DB::connection('yeaf')->table('ineligibles')->insert([
                'code_id' => $ineligible[0],
                'description' => $ineligible[1],
                'active_flag' => $faker->boolean(90), // 90% chance of being active
                'code_type' => $ineligible[2],
                'paragraph_text' => $faker->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

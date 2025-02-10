<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssSanctionTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $sanctionTypes = [
            1 => ['Warning', 'WARN'],
            2 => ['Probation', 'PROB'],
            3 => ['Suspension', 'SUSP'],
            4 => ['Expulsion', 'EXPL'],
            5 => ['Fine', 'FINE'],
            6 => ['Restitution', 'REST'],
            7 => ['Community Service', 'COMS'],
            8 => ['Loss of Privileges', 'PRIV'],
            9 => ['Mandatory Training', 'TRAI'],
            10 => ['Reprimand', 'REPR'],
        ];

        foreach ($sanctionTypes as $code => $details) {
            DB::connection('vss')->table('sanction_types')->updateOrInsert(
                ['sanction_code' => $code],
                [
                    'description' => $details[0],
                    'short_description' => $details[1],
                    'disabled' => $faker->boolean(10), // 10% chance of being disabled
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Add some additional random sanction types
        for ($i = 11; $i <= 13; $i++) {
            $description = $faker->words(3, true);
            DB::connection('vss')->table('sanction_types')->updateOrInsert(
                ['sanction_code' => $i],
                [
                    'description' => $description,
                    'short_description' => strtoupper(substr($description, 0, 4)),
                    'disabled' => $faker->boolean(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

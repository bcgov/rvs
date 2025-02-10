<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebRestrictionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $predefinedRestrictions = [
            'ACAD_PROB' => 'Academic Probation',
            'FIN_HOLD' => 'Financial Hold',
            'INCOM_DOC' => 'Incomplete Documentation',
            'INEL_PROG' => 'Ineligible Program',
            'OVER_AWARD' => 'Over Award',
            'PREV_DEFAULT' => 'Previous Default',
            'SUSP_FRAUD' => 'Suspected Fraud',
        ];

        foreach ($predefinedRestrictions as $code => $description) {
            DB::connection('neb')->table('restrictions')->updateOrInsert(
                ['restriction_code' => $code],
                [
                    'restriction_description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Add some additional random restrictions
        for ($i = 1; $i <= 3; $i++) {
            $restrictionCode = $faker->unique()->bothify('REST_##??');
            DB::connection('neb')->table('restrictions')->updateOrInsert(
                ['restriction_code' => $restrictionCode],
                [
                    'restriction_description' => $faker->sentence(4),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

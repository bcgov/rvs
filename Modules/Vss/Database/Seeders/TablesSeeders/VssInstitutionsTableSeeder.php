<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssInstitutionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $institutions = [
            'UNBC' => 'University of British Columbia',
            'SFUV' => 'Simon Fraser University',
            'UVic' => 'University of Victoria',
            'BCIT' => 'British Columbia Institute of Technology',
            'VCCO' => 'Vancouver Community College',
        ];

        $locationCodes = ['BC', 'AB', 'ON', 'QC', 'NS'];
        $typeCodes = ['U', 'C', 'T', 'P']; // University, College, Technical Institute, Private

        foreach ($institutions as $code => $name) {
            DB::connection('vss')->table('institutions')->updateOrInsert(
                ['institution_code' => $code],
                [
                    'institution_name' => $name,
                    'institution_location_code' => $faker->randomElement($locationCodes),
                    'institution_type_code' => $faker->randomElement($typeCodes),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Add some additional random institutions
        for ($i = 1; $i <= 5; $i++) {
            $code = $faker->unique()->lexify('???');
            DB::connection('vss')->table('institutions')->updateOrInsert(
                ['institution_code' => $code],
                [
                    'institution_name' => $faker->company . ' University',
                    'institution_location_code' => $faker->randomElement($locationCodes),
                    'institution_type_code' => $faker->randomElement($typeCodes),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssCaseNatureOffencesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $incidentIds = DB::connection('vss')->table('incidents')->pluck('incident_id')->toArray();
        $natureCodes = DB::connection('vss')->table('nature_offences')->pluck('nature_code')->toArray();

        foreach ($incidentIds as $incidentId) {
            $numberOfOffences = $faker->numberBetween(1, 3);
            $selectedOffences = $faker->randomElements($natureCodes, $numberOfOffences);

            foreach ($selectedOffences as $natureCode) {
                DB::connection('vss')->table('case_nature_offences')->updateOrInsert(
                    [
                        'incident_id' => $incidentId,
                        'nature_code' => $natureCode,
                    ],
                );
            }
        }
    }
}

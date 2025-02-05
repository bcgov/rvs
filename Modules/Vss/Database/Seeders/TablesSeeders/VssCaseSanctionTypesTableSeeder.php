<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssCaseSanctionTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $incidentIds = DB::connection('vss')->table('incidents')->pluck('incident_id')->toArray();

        $sanctionCodes = DB::connection('vss')->table('sanction_types')->pluck('sanction_code')->toArray();

        foreach ($incidentIds as $incidentId) {
            $numberOfSanctions = $faker->numberBetween(1, 3);
            $selectedSanctions = $faker->randomElements($sanctionCodes, $numberOfSanctions);

            foreach ($selectedSanctions as $sanctionCode) {
                DB::connection('vss')->table('case_sanction_types')->insert([
                    'incident_id' => $incidentId,
                    'sanction_code' => $sanctionCode,
                ]);
            }
        }
    }
}

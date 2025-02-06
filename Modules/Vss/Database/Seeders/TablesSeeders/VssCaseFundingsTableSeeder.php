<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssCaseFundingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $incidentIds = DB::connection('vss')->table('incidents')->pluck('incident_id')->toArray();
        $fundingTypes = DB::connection('vss')->table('funding_types')->pluck('funding_type')->toArray();

        foreach (range(1, 20) as $index) {
            $incidentId = $faker->randomElement($incidentIds);
            $fundingType = $faker->randomElement($fundingTypes);
            $applicationNumber = $faker->optional()->randomFloat(2, 1000, 9999);
            $createdAt = $faker->dateTimeThisYear();
            $fundEntryDate = $faker->dateTimeBetween('-1 year', 'now');

            DB::connection('vss')->table('case_fundings')->updateOrInsert(
                [
                    'incident_id' => $incidentId,
                    'funding_type' => $fundingType,
                    'application_number' => $applicationNumber,
                ],
                [
                    'over_award' => $faker->randomFloat(2, 0, 5000),
                    'prevented_funding' => $faker->randomFloat(2, 0, 10000),
                    'fund_entry_date' => $fundEntryDate,
                    'created_at' => $createdAt,
                    'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
                    'deleted_by_user_id' => $faker->optional(0.1)->uuid,
                    'deleted_at' => $faker->optional(0.1)->dateTimeBetween($createdAt, 'now'),
                ]
            );
        }
    }
}

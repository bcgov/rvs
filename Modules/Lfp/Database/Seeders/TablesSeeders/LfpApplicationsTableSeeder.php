<?php

namespace Modules\Lfp\Database\Seeders\TablesSeeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LfpApplicationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $lfpIds = DB::connection('lfp')->table('lfps')->pluck('id')->toArray();

        foreach (range(1, 10) as $index) {
            $lfpId = $faker->randomElement($lfpIds);
            $applicationNumber = $faker->unique()->numerify('APP-#####');

            // Insert or update the application
            DB::connection('lfp')->table('applications')->updateOrInsert(
                ['application_number' => $applicationNumber],
                [
                    'lfp_id' => $lfpId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Get the id of the inserted/updated application
            $applicationId = DB::connection('lfp')->table('applications')
                ->where('application_number', $applicationNumber)
                ->value('id');

            // Update the lfp with the application_id
            DB::connection('lfp')->table('lfps')
                ->where('id', $lfpId)
                ->update(['application_id' => $applicationId]);
        }
    }
}

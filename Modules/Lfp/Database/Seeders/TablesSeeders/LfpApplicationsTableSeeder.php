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

            DB::connection('lfp')->table('applications')->insert([
                'lfp_id' => $lfpId,
                'application_number' => $faker->unique()->numerify('APP-#####'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::connection('lfp')->table('lfps')
                ->where('id', $lfpId)
                ->update(['application_id' => DB::connection('lfp')->getPdo()->lastInsertId()]);
        }
    }
}

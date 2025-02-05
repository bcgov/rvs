<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpApplicationReasonsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $applicationIds = DB::connection('twp')->table('applications')->pluck('id')->toArray();
        $reasonIds = DB::connection('twp')->table('reasons')->pluck('id')->toArray();

        if (empty($applicationIds) || empty($reasonIds)) {
            return;
        }

        foreach ($applicationIds as $applicationId) {
            $numberOfReasons = $faker->numberBetween(1, 3);
            $selectedReasons = $faker->randomElements($reasonIds, $numberOfReasons);

            foreach ($selectedReasons as $reasonId) {
                DB::connection('twp')->table('application_reasons')->insert([
                    'application_id' => $applicationId,
                    'reason_id' => $reasonId,
                ]);
            }
        }
    }
}

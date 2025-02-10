<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebNebsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $existingApplicationIds = DB::connection('neb')->table('nebs')->pluck('application_id')->toArray();
        $availableApplicationIds = DB::connection('neb')->table('applications')
            ->whereNotIn('id', $existingApplicationIds)
            ->pluck('id')
            ->toArray();
        $bursaryPeriodIds = DB::connection('neb')->table('bursary_periods')->pluck('id')->toArray();

        if (empty($availableApplicationIds)) {
            return;
        }

        $numberOfRecords = min(50, count($availableApplicationIds));

        foreach (range(1, $numberOfRecords) as $index) {
            $studyStartDate = $faker->dateTimeBetween('-1 year', '+1 year');
            $studyEndDate = $faker->dateTimeBetween($studyStartDate, '+1 year');

            $applicationId = array_pop($availableApplicationIds);

            DB::connection('neb')->table('nebs')->updateOrInsert(
                ['application_id' => $applicationId],
                [
                    'bursary_period_id' => $faker->randomElement($bursaryPeriodIds),
                    'program_code' => $faker->bothify('???###'),
                    'inst_code' => $faker->bothify('??##'),
                    'study_start_date' => $studyStartDate,
                    'study_end_date' => $studyEndDate,
                    'sfas_program_code' => $faker->bothify('??##'),
                    'award_amount' => $faker->randomFloat(2, 1000, 10000),
                    'declined_removed_reason' => $faker->optional()->sentence(3),
                    'sfas_award_id' => $faker->unique()->numberBetween(10000, 99999),
                    'unmet_need' => $faker->randomFloat(2, 0, 5000),
                    'weeks_of_study' => $faker->numberBetween(12, 52),
                    'weekly_unmet_need' => $faker->randomFloat(2, 0, 500),
                    'assessed_need_amount' => $faker->randomFloat(2, 1000, 20000),
                    'nurse_type' => $faker->randomElement(['RN', 'LPN', 'NP']),
                    'sector' => $faker->randomElement(['Public', 'Private']),
                    'valid_institution' => $faker->boolean,
                    'restriction' => $faker->boolean,
                    'awarded_in_prior_year' => $faker->boolean,
                    'withdrawal' => $faker->boolean,
                    'neb_ineligible_reason' => $faker->optional()->sentence(3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

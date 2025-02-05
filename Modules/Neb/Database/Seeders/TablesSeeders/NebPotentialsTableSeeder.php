<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebPotentialsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $bursaryPeriodIds = DB::connection('neb')->table('bursary_periods')->pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            $studyStartDate = $faker->dateTimeBetween('-1 year', '+1 year');
            $studyEndDate = $faker->dateTimeBetween($studyStartDate, '+1 year');

            DB::connection('neb')->table('el_potentials')->insert([
                'application_number' => $faker->unique()->numberBetween(1000000, 9999999),
                'sin' => $faker->unique()->numberBetween(100000000, 999999999),
                'postal_code' => $faker->postcode,
                'birth_date' => $faker->date(),
                'first_name' => $faker->firstName,
                'middle_name' => $faker->optional()->firstName,
                'last_name' => $faker->lastName,
                'assessed_need_amount' => $faker->randomFloat(2, 1000, 20000),
                'total_unmet_need' => $faker->randomFloat(2, 0, 10000),
                'weeks_of_study' => $faker->numberBetween(12, 52),
                'weekly_unmet_need' => $faker->randomFloat(2, 0, 500),
                'program_year' => $faker->numberBetween(1, 4),
                'street_address1' => $faker->streetAddress,
                'street_address2' => $faker->optional()->secondaryAddress,
                'city' => $faker->city,
                'province' => $faker->state,
                'gender' => $faker->randomElement(['M', 'F', 'O']),
                'phone_number' => $faker->numerify('##########'),
                'study_start_date' => $studyStartDate,
                'study_end_date' => $studyEndDate,
                'institution_name' => $faker->company,
                'program_code' => $faker->bothify('??##'),
                'inst_code' => $faker->bothify('??##'),
                'area_of_study' => $faker->jobTitle,
                'degree_level' => $faker->randomElement(['Bachelor', 'Master', 'Doctorate']),
                'receive_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'bursary_period_id' => $faker->randomElement($bursaryPeriodIds),
                'month_overlap' => $faker->boolean,
                'num_day_overlap' => $faker->numberBetween(0, 30),
                'valid_institution' => $faker->boolean,
                'restriction' => $faker->boolean,
                'awarded_in_prior_year' => $faker->boolean,
                'withdrawal' => $faker->boolean,
                'nurse_type' => $faker->randomElement(['RN', 'LPN', 'NP']),
                'sector' => $faker->randomElement(['Public', 'Private']),
                'eligibility' => $faker->randomElement(['Eligible', 'Ineligible']),
                'neb_ineligible_reason' => $faker->optional()->sentence,
                'rank_by_unmet_need' => $faker->unique()->numberBetween(1, 50),
                'rank_by_nurse_type' => $faker->numberBetween(1, 50),
                'rank_by_sector' => $faker->numberBetween(1, 50),
                'award_or_deny' => $faker->randomElement(['Award', 'Deny']),
                'neb_deny_reason' => $faker->optional()->sentence,
                'award_amount' => $faker->optional()->randomFloat(2, 1000, 10000),
                'sfas_award_id' => $faker->optional()->numberBetween(1000, 9999),
                'supplier_no' => $faker->optional()->numberBetween(10000, 99999),
                'created_by' => $faker->name,
                'finalized' => $faker->boolean,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => $faker->optional(0.1)->dateTimeThisYear(),
            ]);
        }
    }
}

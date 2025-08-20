<?php

namespace Modules\Plsc\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PlscApplicationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentIds = DB::connection('plsc')->table('students')->pluck('id')->toArray();
        $institutionIds = DB::connection('plsc')->table('institutions')->pluck('id')->toArray();

        foreach (range(1, 20) as $index) {
            $ssd = $faker->dateTimeBetween('-1 year', '+1 year');
            $sed = $faker->dateTimeBetween($ssd, '+2 years');
            $appIdx = $faker->unique()->numberBetween(100000, 999999);
            $individualIdx = $faker->unique()->numberBetween(1000000, 9999999);

            DB::connection('plsc')->table('applications')->updateOrInsert(
                ['app_idx' => $appIdx, 'individual_idx' => $individualIdx],
                [
                    'student_id' => $faker->randomElement($studentIds),
                    'institution_id' => $faker->randomElement($institutionIds),
                    'application_year' => $faker->year,
                    'receive_date' => $faker->dateTimeBetween('-1 year', 'now'),
                    'ssd' => $ssd,
                    'sed' => $sed,
                    'program_of_study' => $faker->sentence(3),
                    'credential' => $faker->randomElement(['Bachelor', 'Master', 'Diploma', 'Certificate']),
                    'parent_last_name' => $faker->lastName,
                    'parent_first_name' => $faker->firstName,
                    'parent_employee_id' => $faker->bothify('EMP######'),
                    'parent_department_id' => $faker->bothify('DEPT###'),
                    'parent_address' => $faker->streetAddress,
                    'parent_city' => substr($faker->city, 0, 40),
                    'parent_province' => $faker->randomElement([
                        'Alberta', 'British Columbia', 'Manitoba', 'New Brunswick',
                        'Newfoundland and Labrador', 'Nova Scotia', 'Ontario',
                        'Prince Edward Island', 'Quebec', 'Saskatchewan'
                    ]),
                    'parent_postal_code' => substr($faker->postcode, 0, 7),
                    'parent_phone_number' => substr($faker->phoneNumber, 0, 15),
                    'parent_email' => $faker->safeEmail,
                    'parent_ministry' => $faker->company,
                    'parent_branch' => $faker->randomElement([
                        'Policy and Planning', 'Operations Division', 'Finance Branch',
                        'Human Resources', 'Communications', 'Legal Services',
                        'Strategic Planning', 'Program Delivery', 'Corporate Services'
                    ]),
                    'parent_job_title' => substr($faker->jobTitle, 0, 40),
                    'parent_three_years_in_gov' => $faker->boolean(80),
                    'high_school_average' => $faker->numberBetween(60, 100) . '%',
                    'post_secondary_average' => $faker->optional()->numberBetween(60, 100) . '%',
                    'seven_fifty_word_essay' => $faker->boolean(90),
                    'high_school_transcript' => $faker->boolean(95),
                    'post_secondary_transcript' => $faker->boolean(70),
                    'student_reference_letter' => $faker->boolean(85),
                    'communication_skills' => $faker->boolean(80),
                    'enrollment_confirmed' => $faker->boolean(75),
                    'forward_to_committee' => $faker->boolean(60),
                    'comment' => $faker->optional()->paragraph,
                    'status_code' => $faker->randomElement(['PEND', 'APPR', 'DENY']),
                    'other_org' => $faker->optional()->company,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => $faker->optional(0.1)->dateTimeThisYear(),
                ]
            );
        }
    }
}

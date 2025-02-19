<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpProgramsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentIds = DB::connection('twp')->table('students')->pluck('id')->toArray();
        $applicationIds = DB::connection('twp')->table('applications')->pluck('id')->toArray();
        $institutionIds = DB::connection('twp')->table('institutions')->pluck('id')->toArray();

        foreach (range(1, 20) as $index) {
            $startDate = $faker->dateTimeBetween('-1 year', '+1 year');
            $endDate = $faker->dateTimeBetween($startDate, '+4 years');
            $studentId = $faker->randomElement($studentIds);
            $applicationId = $faker->randomElement($applicationIds);

            DB::connection('twp')->table('programs')->updateOrInsert(
                [
                    'student_id' => $studentId,
                    'application_id' => $applicationId,
                ],
                [
                    'yeaf_institution_id' => $faker->optional()->numberBetween(1, 1000),
                    'yeaf_program_year_id' => $faker->optional()->numberBetween(1, 5),
                    'yeaf_study_start_date' => $startDate,
                    'yeaf_study_end_date' => $endDate,
                    'study_field' => $faker->randomElement(['Science', 'Arts', 'Engineering', 'Medicine', 'Business']),
                    'institution_name' => $faker->company . ' University',
                    'study_period_start_date' => $startDate,
                    'credential' => $faker->randomElement(['Bachelor', 'Master', 'Diploma', 'Certificate']),
                    'program_length' => $faker->numberBetween(1, 4),
                    'program_length_type' => $faker->randomElement(['Years', 'Semesters']),
                    'total_estimated_cost' => $faker->randomFloat(2, 5000, 50000),
                    'student_status' => $faker->randomElement(['Full-time', 'Part-time']),
                    'comments' => $faker->optional()->paragraph,
                    'institution_twp_id' => $faker->optional()->randomElement($institutionIds),
                    'credential_type' => $faker->randomElement(['Undergraduate', 'Graduate', 'Vocational']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

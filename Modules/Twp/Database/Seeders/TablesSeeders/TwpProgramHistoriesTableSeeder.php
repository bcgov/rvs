<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpProgramHistoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentIds = DB::connection('twp')->table('students')->pluck('id')->toArray();
        $programIds = DB::connection('twp')->table('programs')->pluck('id')->toArray();
        $applicationIds = DB::connection('twp')->table('applications')->pluck('id')->toArray();
        $institutionIds = DB::connection('twp')->table('institutions')->pluck('id')->toArray();

        foreach (range(1, 20) as $index) {
            $startDate = $faker->dateTimeBetween('-2 years', 'now');
            $endDate = $faker->dateTimeBetween($startDate, '+4 years');
            $studentId = $faker->randomElement($studentIds);
            $programTwpId = $faker->randomElement($programIds);
            $applicationId = $faker->randomElement($applicationIds);

            DB::connection('twp')->table('program_histories')->updateOrInsert(
                [
                    'student_id' => $studentId,
                    'program_twp_id' => $programTwpId,
                    'application_id' => $applicationId,
                ],
                [
                    'yeaf_institution_id' => $faker->optional()->numberBetween(1, 1000),
                    'yeaf_program_year_id' => $faker->optional()->numberBetween(1, 5),
                    'yeaf_study_start_date' => $startDate,
                    'yeaf_study_end_date' => $endDate,
                    'study_field' => $faker->randomElement(['Science', 'Arts', 'Engineering', 'Medicine', 'Business']),
                    'institution_name' => $faker->company . ' University',
                    'institution_twp_id' => $faker->optional()->randomElement($institutionIds),
                    'credential' => $faker->randomElement(['Bachelor', 'Master', 'Diploma', 'Certificate']),
                    'credential_type' => $faker->randomElement(['Undergraduate', 'Graduate', 'Vocational']),
                    'study_period_start_date' => $startDate,
                    'program_length' => $faker->numberBetween(1, 4),
                    'program_length_type' => $faker->randomElement(['Years', 'Semesters']),
                    'total_estimated_cost' => $faker->randomFloat(2, 5000, 50000),
                    'student_status' => $faker->randomElement(['Full-time', 'Part-time']),
                    'comments' => $faker->optional()->paragraph,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

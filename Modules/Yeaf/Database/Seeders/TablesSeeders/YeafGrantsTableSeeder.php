<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class YeafGrantsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $institutionIds = DB::connection('yeaf')->table('institutions')->pluck('institution_id')->toArray();
        $studentIds = DB::connection('yeaf')->table('students')->pluck('student_id')->toArray();
        $programYearIds = DB::connection('yeaf')->table('program_years')->pluck('program_year_id')->toArray();
        $programCodes = DB::connection('yeaf')->table('programs')->pluck('program_code')->toArray();
        $batchNumbers = DB::connection('yeaf')->table('batches')->pluck('batch_number')->toArray();

        foreach (range(1, 20) as $index) {
            $createdAt = $faker->dateTimeThisYear();
            $studyStartDate = $faker->dateTimeBetween($createdAt, '+6 months');
            $studyEndDate = $faker->dateTimeBetween($studyStartDate, Carbon::parse($studyStartDate)->addYears(2));

            $grant = [
                'grant_id' => $faker->unique()->numberBetween(100000, 999999),
                'institution_id' => $faker->randomElement($institutionIds),
                'student_id' => $faker->randomElement($studentIds),
                'program_year_id' => $faker->randomElement($programYearIds),
                'program_code' => $faker->randomElement($programCodes),
                'cheque_batch_number' => $faker->optional()->randomElement($batchNumbers),
                'officer_user_id' => $faker->uuid,
                'creator_user_id' => $faker->uuid,
                'update_user_id' => $faker->optional()->uuid,
                'application_number' => $faker->unique()->numberBetween(1000000, 9999999),
                'age' => $faker->numberBetween(18, 60),
                'eligible_need' => $faker->randomFloat(2, 0, 10000),
                'total_award' => $faker->randomFloat(2, 0, 5000),
                'unmet_need' => $faker->randomFloat(2, 0, 2000),
                'total_bcsl_award' => $faker->randomFloat(2, 0, 3000),
                'total_yeaf_award' => $faker->randomFloat(2, 0, 2000),
                'total_yeaf_award_remit' => $faker->randomFloat(2, 0, 1000),
                'overaward' => $faker->randomFloat(2, 0, 500),
                'overaward_calc' => $faker->randomFloat(2, 0, 500),
                'overaward_deducted_amount' => $faker->randomFloat(2, 0, 250),
                'reason_for_ineligibility' => $faker->optional()->sentence,
                'program_name' => $faker->words(3, true),
                'program_other_description' => $faker->optional()->sentence,
                'status_code' => $faker->randomElement(['APPR', 'PEND', 'DENY']),
                'date_issued_month' => substr($faker->monthName, 0, 2),
                'date_issued_year' => $studyStartDate->format('Y'),
                'application_type' => $faker->randomElement(['New', 'Renewal']),
                'letter_text' => $faker->optional()->paragraph,
                'custom_pending_reason' => $faker->optional()->sentence,
                'custom_denial_reason' => $faker->optional()->sentence,
                'study_period_completion' => $faker->boolean,
                'confirmation_bcsl_remission' => $faker->boolean,
                'reassess' => $faker->boolean,
                'overaward_cleared' => $faker->boolean,
                'withdrawal' => $faker->boolean,
                'study_start_date' => $studyStartDate,
                'study_end_date' => $studyEndDate,
                'bcsl_remission' => $faker->optional()->dateTimeBetween($studyEndDate, Carbon::parse($studyEndDate)->addYear()),
                'letter_date' => $faker->optional()->dateTimeBetween($createdAt, 'now'),
                'cheque_issue_date' => $faker->optional()->dateTimeBetween($createdAt, 'now'),
                'withdrawal_date' => $faker->optional()->dateTimeBetween($studyStartDate, $studyEndDate),
                'status_date' => $faker->dateTimeBetween($createdAt, 'now'),
                'last_letter_produced_date' => $faker->optional()->dateTimeBetween($createdAt, 'now'),
                'application_receive_date' => $faker->dateTimeBetween(Carbon::parse($createdAt)->subYear(), $createdAt),
                'last_evaluation_date' => $faker->dateTimeBetween($createdAt, 'now'),
                'created_at' => $createdAt,
                'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
            ];

            DB::connection('yeaf')->table('grants')->insert($grant);
        }
    }
}

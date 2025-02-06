<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class YeafAppealsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentIds = DB::connection('yeaf')->table('students')->pluck('student_id')->toArray();
        $grantIds = DB::connection('yeaf')->table('grants')->pluck('grant_id')->toArray();
        $programYearIds = DB::connection('yeaf')->table('program_years')->pluck('program_year_id')->toArray();

        $appealCodes = ['ACA', 'FIN', 'MED', 'OTH'];
        $statusCodes = ['AP', 'DE', 'PE'];

        foreach (range(1, 20) as $index) {
            $studentId = $faker->randomElement($studentIds);
            $grantId = $faker->randomElement($grantIds);
            $programYearId = $faker->randomElement($programYearIds);
            $appealCode = $faker->randomElement($appealCodes);
            $createdAt = $faker->dateTimeThisYear();

            DB::connection('yeaf')->table('appeals')->updateOrInsert(
                [
                    'student_id' => $studentId,
                    'grant_id' => $grantId,
                    'program_year_id' => $programYearId,
                    'appeal_code' => $appealCode,
                ],
                [
                    'adjudicated_by_user_id' => $faker->uuid,
                    'updated_by_user_id' => $faker->uuid,
                    'appeal_date' => $faker->dateTimeBetween('-1 year', 'now'),
                    'status_code' => $faker->randomElement($statusCodes),
                    'status_affective_date' => $faker->dateTimeBetween($createdAt, 'now'),
                    'other_appeal_explain' => $faker->optional()->sentence,
                    'created_at' => $createdAt,
                    'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
                ]
            );
        }
    }
}

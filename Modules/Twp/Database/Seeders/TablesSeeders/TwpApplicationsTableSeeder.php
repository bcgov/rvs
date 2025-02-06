<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpApplicationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentIds = DB::connection('twp')->table('students')->pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            $studentId = $faker->randomElement($studentIds);
            $receivedDate = $faker->dateTimeBetween('-1 year', 'now');

            DB::connection('twp')->table('applications')->updateOrInsert(
                [
                    'student_id' => $studentId,
                    'received_date' => $receivedDate,
                ],
                [
                    'application_status' => $faker->randomElement(['Pending', 'Approved', 'Denied']),
                    'twp_status' => $faker->randomElement(['Eligible', 'Ineligible', 'Pending']),
                    'denial_reason' => $faker->optional()->paragraph,
                    'exception_comments' => $faker->optional()->sentence,
                    'institution_student_number' => $faker->numerify('############'),
                    'apply_twp' => $faker->boolean(90),
                    'apply_lfg' => $faker->boolean(30),
                    'confirmation_enrolment' => $faker->boolean(70),
                    'sabc_app_number' => $faker->optional()->numerify('##########'),
                    'fao_name' => $faker->name,
                    'fao_email' => $faker->safeEmail,
                    'fao_phone' => $faker->phoneNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpGrantsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $applicationIds = DB::connection('twp')->table('applications')->pluck('id')->toArray();
        $studentIds = DB::connection('twp')->table('students')->pluck('id')->toArray();

        foreach (range(1, 20) as $index) {
            $applicationId = $faker->randomElement($applicationIds);
            $studentId = DB::connection('twp')->table('applications')
                ->where('id', $applicationId)
                ->value('student_id');

            DB::connection('twp')->table('grants')->insert([
                'application_id' => $applicationId,
                'student_id' => $studentId,
                'received_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'grant_status' => $faker->randomElement(['Approved', 'Pending', 'Denied']),
                'grant_comments' => $faker->optional()->paragraph,
                'grant_amount' => $faker->randomFloat(2, 500, 5000),
                'created_by' => $faker->name,
                'updated_by' => $faker->name,
                'extra_1' => $faker->optional()->word,
                'extra_2' => $faker->optional()->word,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebApplicationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentIds = DB::connection('neb')->table('students')->pluck('id')->toArray();

        foreach (range(1, 20) as $index) {
            DB::connection('neb')->table('applications')->insert([
                'student_id' => $faker->randomElement($studentIds),
                'sin' => $faker->unique()->numberBetween(100000000, 999999999),
                'application_number' => $faker->unique()->numerify('APP-#####'),
                'complete' => $faker->boolean,
                'eligible' => $faker->boolean,
                'award_status' => $faker->randomElement(['Pending', 'Approved', 'Rejected']),
                'rank' => $faker->optional()->numberBetween(1, 1000),
                'total_score' => $faker->optional()->randomFloat(2, 0, 100),
                'receive_date' => $faker->date(),
                'effective_date' => $faker->optional()->date(),
                'process_date' => $faker->optional()->date(),
                'comment' => $faker->optional()->sentence,
                'program_code' => $faker->bothify('??###'),
                'bursary_period_id' => $faker->numberBetween(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

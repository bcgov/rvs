<?php

namespace Modules\Lfp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LfpIntakesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            $sin = $faker->unique()->numberBetween(100000000, 999999999);
            $appIdx = $faker->unique()->numberBetween(1000, 9999);

            DB::connection('lfp')->table('intakes')->updateOrInsert(
                ['sin' => $sin],
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'profession' => substr($faker->jobTitle, 0, 50),
                    'employer' => substr($faker->company, 0, 30),
                    'community' => substr($faker->city, 0, 100),
                    'in_good_standing' => $faker->randomElement(['Yes', 'No']),
                    'repayment_start_date' => $faker->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d'),
                    'amount_owing' => $faker->randomFloat(2, 0, 100000),
                    'receive_date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                    'employment_status' => $faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Self-employed']),
                    'intake_status' => $faker->randomElement(['Pending', 'Approved', 'Denied']),
                    'comment' => $faker->optional()->paragraph,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'denial_reason' => $faker->optional()->sentence,
                    'proposed_registration_date' => $faker->optional()
                        ->dateTimeBetween('now', '+1 year')
                        ?->format('Y-m-d'),
                    'app_idx' => $appIdx,
                    'alias_name' => $faker->optional()->name,
                ]
            );
        }
    }
}

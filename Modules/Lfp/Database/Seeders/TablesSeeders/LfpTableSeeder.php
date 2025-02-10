<?php

namespace Modules\Lfp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LfpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            $sin = $faker->unique()->numberBetween(100000000, 999999999);
            $appIdx = $faker->unique()->numberBetween(1000, 9999);

            DB::connection('lfp')->table('lfps')->updateOrInsert(
                ['sin' => $sin],
                [
                    'profession' => $faker->jobTitle,
                    'employer' => substr($faker->company, 0, 30),
                    'employment_status' => $faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Self-employed']),
                    'community' => $faker->city,
                    'declined_removed_reason' => $faker->optional()->sentence,
                    'app_idx' => $appIdx,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'direct_lend' => $faker->randomElement(['Yes', 'No']),
                    'risk_sharing_guaranteed' => $faker->randomElement(['Yes', 'No']),
                    'comment' => $faker->optional()->paragraph,
                    'full_name_alias' => $faker->name,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                ]
            );
        }
    }
}

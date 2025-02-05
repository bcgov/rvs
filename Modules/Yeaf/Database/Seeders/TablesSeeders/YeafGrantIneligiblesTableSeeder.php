<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class YeafGrantIneligiblesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $grantIds = DB::connection('yeaf')->table('grants')->pluck('grant_id')->toArray();
        $ineligibleCodeTypes = ['A', 'B', 'C', 'D', 'E']; // Example ineligible code types

        foreach (range(1, 20) as $index) {
            $createdAt = $faker->dateTimeThisYear();

            DB::connection('yeaf')->table('grant_ineligibles')->insert([
                'grant_id' => $faker->randomElement($grantIds),
                'ineligible_code_id' => $faker->numberBetween(1, 100),
                'created_by' => $faker->uuid,
                'cleared_flag' => $faker->boolean,
                'ineligible_code_type' => $faker->randomElement($ineligibleCodeTypes),
                'created_at' => $createdAt,
                'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
            ]);
        }
    }
}

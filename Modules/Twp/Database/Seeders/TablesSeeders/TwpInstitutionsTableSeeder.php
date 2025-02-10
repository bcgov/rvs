<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpInstitutionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            $name = $faker->unique()->company . ' University';

            DB::connection('twp')->table('institutions')->updateOrInsert(
                ['name' => $name],
                [
                    'contact_name' => $faker->name,
                    'contact_email' => $faker->unique()->safeEmail,
                    'active_flag' => $faker->boolean(80), // 80% chance of being active
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

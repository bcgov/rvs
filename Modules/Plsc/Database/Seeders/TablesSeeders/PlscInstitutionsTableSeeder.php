<?php

namespace Modules\Plsc\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PlscInstitutionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::connection('plsc')->table('institutions')->insert([
                'name' => $faker->unique()->company . ' University',
                'contact_name' => $faker->name,
                'contact_email' => $faker->unique()->safeEmail,
                'active_flag' => $faker->boolean(80), // 80% chance of being active
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

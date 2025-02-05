<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class YeafInstitutionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            $country = $faker->randomElement(['CAN', 'USA']);

            DB::connection('yeaf')->table('institutions')->insert([
                'name' => $faker->company . ' University',
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'province' => $country === 'CAN' ? $faker->stateAbbr : null,
                'state' => $country === 'USA' ? $faker->stateAbbr : null,
                'postal_code' => substr($country === 'CAN' ? $faker->postcode : null,0,7),
                'zip_code' => substr($country === 'USA' ? $faker->postcode : null,0,6),
                'country' => $country,
                'tele' => substr($faker->phoneNumber,0,16),
                'fax' => substr($faker->phoneNumber,0,16),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

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
            $name = $faker->company . ' University';

            DB::connection('yeaf')->table('institutions')->updateOrInsert(
                ['name' => $name],
                [
                    'address' => $faker->streetAddress,
                    'city' => $faker->city,
                    'province' => $country === 'CAN' ? $faker->randomElement(['BC', 'AB', 'SK', 'MB', 'ON', 'QC', 'NB', 'NS', 'PE', 'NL']) : null,
                    'state' => $country === 'USA' ? $faker->randomElement(['BC', 'AB', 'SK', 'MB', 'ON', 'QC', 'NB', 'NS', 'PE', 'NL']) : null,
                    'postal_code' => substr($country === 'CAN' ? $faker->postcode : null, 0, 7),
                    'zip_code' => substr($country === 'USA' ? $faker->postcode : null, 0, 6),
                    'country' => $country,
                    'tele' => substr($faker->phoneNumber, 0, 16),
                    'fax' => substr($faker->phoneNumber, 0, 16),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

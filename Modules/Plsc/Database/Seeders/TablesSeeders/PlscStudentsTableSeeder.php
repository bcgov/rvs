<?php

namespace Modules\Plsc\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PlscStudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            $sin = $faker->unique()->numberBetween(100000000, 999999999);
            $pen = $faker->unique()->numberBetween(100000000, 999999999);
            $individualIdx = $faker->unique()->numberBetween(1000000, 9999999);

            DB::connection('plsc')->table('students')->updateOrInsert(
                ['sin' => $sin],
                [
                    'pen' => $pen,
                    'individual_idx' => $individualIdx,
                    'birth_date' => $faker->date('Y-m-d', '-18 years'),
                    'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'address1' => $faker->streetAddress,
                    'address2' => $faker->optional()->secondaryAddress,
                    'city' => substr($faker->city,0,40),
                    'postal_code' => substr($faker->postcode, 0, 7),
                    'province' => substr($faker->state,0,40),
                    'country' => substr($faker->country,0,40),
                    'phone_number' => substr($faker->phoneNumber, 0, 10),
                    'email' => $faker->safeEmail,
                    'comment' => $faker->optional()->paragraph,
                    'citizenship' => substr($faker->country, 0, 20),
                    'marital_status' => $faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
                    'title' => $faker->randomElement(['Mr.', 'Mrs.', 'Ms.', 'Dr.']),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => $faker->optional(0.1)->dateTimeThisYear(), // 10% chance of being soft deleted
                ]
            );
        }
    }
}

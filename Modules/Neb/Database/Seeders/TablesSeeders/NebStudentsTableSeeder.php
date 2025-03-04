<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebStudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            $sin = $faker->unique()->numberBetween(100000000, 999999999);
            $pen = $faker->unique()->numberBetween(100000000, 999999999);

            DB::connection('neb')->table('students')->updateOrInsert(
                ['sin' => $sin],
                [
                    'pen' => $pen,
                    'date_of_birth' => $faker->date('Y-m-d', '-18 years'),
                    'title' => $faker->randomElement(['Mr.', 'Mrs.', 'Ms.', 'Dr.']),
                    'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                    'first_name' => $faker->firstName,
                    'middle_name' => $faker->optional()->firstName,
                    'last_name' => $faker->lastName,
                    'old_first_name' => $faker->optional()->firstName,
                    'old_middle_name' => $faker->optional()->firstName,
                    'old_last_name' => $faker->optional()->lastName,
                    'citizenship' => substr($faker->country, 0, 20),
                    'marital_status' => $faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
                    'address1' => $faker->streetAddress,
                    'address2' => $faker->optional()->secondaryAddress,
                    'city' => substr($faker->city, 0, 40),
                    'postal_code' => substr($faker->postcode, 0, 7),
                    'province' => substr($faker->state, 0, 40),
                    'country' => substr($faker->country, 0, 40),
                    'phone_number' => substr($faker->phoneNumber, 0, 10),
                    'email' => $faker->safeEmail,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => $faker->optional(0.1)->dateTimeThisYear(), // 10% chance of being soft deleted
                ]
            );
        }
    }
}

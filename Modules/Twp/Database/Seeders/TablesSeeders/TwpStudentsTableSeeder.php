<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpStudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            DB::connection('twp')->table('students')->insert([
                'yeaf_student_id' => $faker->unique()->numberBetween(100000, 999999),
                'sin' => $faker->numerify('#########'),
                'name' => $firstName . ' ' . $lastName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'birth_date' => $faker->date('Y-m-d', '-18 years'),
                'address' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'gender' => $faker->randomElement(['M', 'F', 'O']),
                'pen' => $faker->numerify('#########'),
                'institution_student_number' => $faker->numerify('############'),
                'citizenship' => $faker->randomElement(['Canadian', 'Permanent Resident', 'International']),
                'bc_resident' => $faker->boolean(80), // 80% chance of being BC resident
                'indigeneity' => $faker->optional()->randomElement(['First Nations', 'Métis', 'Inuit']),
                'comment' => $faker->optional()->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => $faker->optional(0.1)->dateTimeThisYear(), // 10% chance of being soft deleted
            ]);
        }
    }
}

<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class YeafStudentsTableSeeder extends Seeder {

    public function run(): void {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            $studentId = $faker->unique()->numerify('YEAF#####');

            DB::connection('yeaf')->table('students')->updateOrInsert(
                ['student_id' => $studentId],
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'sin' => $faker->numerify('#########'),
                    'birth_date' => $faker->date('Y-m-d', '-18 years'),
                    'address' => $faker->streetAddress,
                    'city' => $faker->city,
                    'province' => $faker->randomElement(['BC', 'AB', 'ON', 'QC', 'NS']),
                    'postal_code' => substr($faker->postcode, 0, 7),
                    'country' => 'CAN',
                    'tele' => $faker->phoneNumber,
                    'email' => $faker->email,
                    'gender' => $faker->randomElement(['M', 'F', 'O']),
                    'life' => $faker->randomFloat(2, 0, 10000),
                    'overaward_amount' => $faker->randomFloat(2, 0, 1000),
                    'overaward_flag' => $faker->boolean,
                    'overaward_deducted_amount' => $faker->randomFloat(2, 0, 500),
                    'investigate' => $faker->boolean(10),
                    'pen' => $faker->numerify('#########'),
                    'pd' => $faker->boolean(5),
                    'institution_student_number' => $faker->numerify('###########'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

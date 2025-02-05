<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpPaymentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentIds = DB::connection('twp')->table('students')->pluck('id')->toArray();
        $programIds = DB::connection('twp')->table('programs')->pluck('id')->toArray();
        $applicationIds = DB::connection('twp')->table('applications')->pluck('id')->toArray();
        $paymentTypeIds = DB::connection('twp')->table('payment_types')->pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            DB::connection('twp')->table('payments')->insert([
                'student_id' => $faker->randomElement($studentIds),
                'program_id' => $faker->randomElement($programIds) ?? $programIds[0],
                'application_id' => $faker->randomElement($applicationIds),
                'payment_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'payment_amount' => $faker->randomFloat(2, 100, 5000),
                'payment_type_id' => $faker->randomElement($paymentTypeIds),
                'created_by' => $faker->name,
                'updated_by' => $faker->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

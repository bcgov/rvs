<?php

namespace Modules\Lfp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LfpPaymentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $lfpIds = DB::connection('lfp')->table('lfps')->pluck('id')->toArray();

        foreach (range(1, 30) as $index) {
            DB::connection('lfp')->table('payments')->insert([
                'lfp_id' => $faker->randomElement($lfpIds),
                'profession' => substr($faker->jobTitle, 0, 50),
                'employer' => substr($faker->company, 0, 30),
                'employment_status' => $faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Self-employed']),
                'community' => substr($faker->city, 0, 100),
                'app_idx' => $faker->unique()->numberBetween(1000, 9999),
                'pay_idx' => $faker->unique()->numberBetween(1000, 9999),
                'reconciled_with_payment_report_date' => $faker->optional()->date(),
                'reconciled_with_galaxy_date' => $faker->optional()->date(),
                'comment' => $faker->optional()->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
                'anniversary_date' => $faker->optional()->date(),
                'proposed_pay_date' => $faker->optional()->dateTimeBetween('now', '+1 year'),
                'proposed_pay_amount' => $faker->randomFloat(2, 100, 10000),
                'proposed_hrs_of_service' => $faker->numberBetween(1, 2000),
                'sfas_pay_status' => $faker->randomElement(['Pending', 'Approved', 'Denied']),
                'oc_pay_status' => $faker->randomElement(['Pending', 'Approved', 'Denied']),
            ]);
        }
    }
}

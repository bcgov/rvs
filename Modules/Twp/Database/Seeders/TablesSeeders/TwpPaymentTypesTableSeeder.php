<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpPaymentTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $paymentTypes = [
            'Direct Deposit',
            'Cheque',
            'Electronic Funds Transfer',
            'Credit Card',
            'PayPal',
            'Wire Transfer',
        ];

        foreach ($paymentTypes as $type) {
            DB::connection('twp')->table('payment_types')->updateOrInsert(
                ['title' => $type],
                [
                    'active_flag' => $faker->boolean(90), // 90% chance of being active
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

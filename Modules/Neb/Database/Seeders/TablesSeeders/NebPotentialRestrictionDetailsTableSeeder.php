<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebPotentialRestrictionDetailsTableSeeder extends Seeder {

    public function run(): void {
        $faker = Faker::create();

        $bursaryPeriodIds = DB::connection('neb')
            ->table('bursary_periods')
            ->pluck('id')
            ->toArray();

        $restrictionCodes = [
            'REST001' => 'Financial Hold',
            'REST002' => 'Academic Probation',
            'REST003' => 'Incomplete Documentation',
            'REST004' => 'Eligibility Issue',
            'REST005' => 'Disciplinary Action',
        ];

        foreach (range(1, 20) as $index) {
            $restrictionCode = $faker->randomElement(array_keys($restrictionCodes));
            $sin = $faker->unique()->numberBetween(100000000, 999999999);

            DB::connection('neb')
                ->table('el_potential_restriction_details')
                ->updateOrInsert(
                    ['sin' => $sin, 'restriction_code' => $restrictionCode],
                    [
                        'restriction_description' => $restrictionCodes[$restrictionCode],
                        'applied_date' => $faker->dateTimeBetween('-1 year', 'now')
                            ->format('Y-m-d'),
                        'bursary_period_id' => $faker->randomElement($bursaryPeriodIds),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
        }
    }

}

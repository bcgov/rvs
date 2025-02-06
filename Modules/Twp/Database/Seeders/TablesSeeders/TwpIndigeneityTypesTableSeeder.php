<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpIndigeneityTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $indigeneityTypes = [
            'First Nations',
            'Métis',
            'Inuit',
            'Non-Status Indian',
            'Indigenous Ancestry',
        ];

        foreach ($indigeneityTypes as $type) {
            DB::connection('twp')->table('indigeneity_types')->updateOrInsert(
                ['title' => $type],
                [
                    'active_flag' => $faker->boolean(90),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

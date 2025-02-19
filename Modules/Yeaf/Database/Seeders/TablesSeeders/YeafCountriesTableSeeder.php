<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YeafCountriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['country_code' => 'CAN', 'country_name' => 'Canada'],
            ['country_code' => 'USA', 'country_name' => 'United States of America'],
            ['country_code' => 'GBR', 'country_name' => 'United Kingdom'],
            ['country_code' => 'FRA', 'country_name' => 'France'],
            ['country_code' => 'DEU', 'country_name' => 'Germany'],
            ['country_code' => 'JPN', 'country_name' => 'Japan'],
            ['country_code' => 'AUS', 'country_name' => 'Australia'],
            ['country_code' => 'NZL', 'country_name' => 'New Zealand'],
            ['country_code' => 'MEX', 'country_name' => 'Mexico'],
            ['country_code' => 'BRA', 'country_name' => 'Brazil'],
        ];

        foreach ($countries as $country) {
            DB::connection('yeaf')->table('countries')->updateOrInsert(
                ['country_code' => $country['country_code']],
                [
                    'country_name' => $country['country_name'],
                ]
            );

        }
    }
}

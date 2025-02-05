<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YeafProvincesTableSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = [
            ['country_code' => 'CAN', 'province_code' => 'AB', 'province_name' => 'Alberta'],
            ['country_code' => 'CAN', 'province_code' => 'BC', 'province_name' => 'British Columbia'],
            ['country_code' => 'CAN', 'province_code' => 'MB', 'province_name' => 'Manitoba'],
            ['country_code' => 'CAN', 'province_code' => 'NB', 'province_name' => 'New Brunswick'],
            ['country_code' => 'CAN', 'province_code' => 'NL', 'province_name' => 'Newfoundland and Labrador'],
            ['country_code' => 'CAN', 'province_code' => 'NS', 'province_name' => 'Nova Scotia'],
            ['country_code' => 'CAN', 'province_code' => 'ON', 'province_name' => 'Ontario'],
            ['country_code' => 'CAN', 'province_code' => 'PE', 'province_name' => 'Prince Edward Island'],
            ['country_code' => 'CAN', 'province_code' => 'QC', 'province_name' => 'Quebec'],
            ['country_code' => 'CAN', 'province_code' => 'SK', 'province_name' => 'Saskatchewan'],
            ['country_code' => 'CAN', 'province_code' => 'NT', 'province_name' => 'Northwest Territories'],
            ['country_code' => 'CAN', 'province_code' => 'NU', 'province_name' => 'Nunavut'],
            ['country_code' => 'CAN', 'province_code' => 'YT', 'province_name' => 'Yukon'],
        ];

        foreach ($provinces as $province) {
            DB::connection('yeaf')->table('provinces')->insert($province);
        }
    }
}

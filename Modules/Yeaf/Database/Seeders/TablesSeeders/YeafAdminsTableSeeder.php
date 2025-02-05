<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class YeafAdminsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            DB::connection('yeaf')->table('admins')->insert([
                'contact_fname' => $faker->firstName,
                'contact_lname' => $faker->lastName,
                'contact_tele' => $faker->phoneNumber,
                'contact_title' => $faker->jobTitle,
                'contact_dept' => $faker->word,
                'contact_branch' => $faker->word,
                'ministry' => $faker->company,
                'ministry_branch' => $faker->word,
                'ministry_address' => $faker->streetAddress,
                'ministry_city' => $faker->city,
                'ministry_prov' => $faker->stateAbbr,
                'ministry_postal' => $faker->postcode,
                'ministry_tele_victoria' => $faker->phoneNumber,
                'ministry_tele_lower' => $faker->phoneNumber,
                'ministry_tele_toll_free' => $faker->phoneNumber,
                'ministry_TTY_line' => $faker->phoneNumber,
                'ministry_location' => $faker->secondaryAddress,
                'ministry_location_city' => $faker->city,
                'ministry_location_prov' => $faker->stateAbbr,
                'ministry_location_postal' => $faker->postcode,
                'ministry_location_tele_toll_free' => $faker->phoneNumber,
                'ministry_fax' => $faker->phoneNumber,
                'org' => $faker->company,
                'org_fname' => $faker->firstName,
                'org_lname' => $faker->lastName,
                'org_fax' => $faker->phoneNumber,
                'user_fname' => $faker->firstName,
                'user_lname' => $faker->lastName,
                'user_branch' => $faker->word,
                'user_tele' => $faker->phoneNumber,
                'user_fax' => $faker->phoneNumber,
                'start_month' => $faker->monthName,
                'start_day' => $faker->dayOfMonth,
                'end_month' => $faker->monthName,
                'end_day' => $faker->dayOfMonth,
                'temp' => $faker->paragraph,
                'application_name' => $faker->words(3, true),
                'application_abbreviation' => $faker->lexify('???'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

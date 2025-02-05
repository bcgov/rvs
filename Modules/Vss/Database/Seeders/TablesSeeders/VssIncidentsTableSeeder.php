<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssIncidentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $referralSourceIds = DB::connection('vss')->table('referral_sources')->pluck('id')->toArray();
        $areaOfAuditCodes = DB::connection('vss')->table('area_of_audits')->pluck('area_of_audit_code')->toArray();

        foreach (range(1, 20) as $index) {
            $openDate = $faker->dateTimeBetween('-2 years', 'now');

            DB::connection('vss')->table('incidents')->insert([
                'incident_id' => $faker->unique()->numberBetween(100000, 999999),
                'application_number' => $faker->optional()->randomFloat(2, 1000, 9999),
                'sin' => $faker->numerify('#########'),
                'last_name' => $faker->lastName,
                'first_name' => $faker->firstName,
                'referral_source_id' => $faker->randomElement($referralSourceIds),
                'open_date' => $openDate,
                'reactivate_date' => $faker->optional()->dateTimeBetween($openDate, 'now'),
                'year_of_audit' => $faker->year,
                'institution_code' => $faker->bothify('??###'),
                'auditor_user_id' => $faker->optional()->uuid,
                'audit_date' => $faker->optional()->dateTimeBetween($openDate, 'now'),
                'investigator_user_id' => $faker->optional()->uuid,
                'investigation_date' => $faker->optional()->dateTimeBetween($openDate, 'now'),
                'audit_type' => $faker->randomElement(['I', 'E']),
                'area_of_audit_code' => $faker->randomElement($areaOfAuditCodes),
                'incident_status' => $faker->randomElement(['Active', 'Closed', 'Pending']),
                'close_date' => $faker->optional()->dateTimeBetween($openDate, 'now'),
                'case_close' => $faker->boolean,
                'reason_for_closing' => $faker->optional()->sentence,
                'case_outcome' => $faker->optional()->sentence,
                'appeal_flag' => $faker->boolean,
                'appeal_outcome' => $faker->optional()->sentence,
                'severity' => $faker->randomElement(['Low', 'Medium', 'High']),
                'bring_forward' => $faker->boolean,
                'bring_forward_date' => $faker->optional()->dateTimeBetween('now', '+1 year'),
                'rcmp_referral_flag' => $faker->boolean,
                'rcmp_referral_date' => $faker->optional()->dateTimeBetween($openDate, 'now'),
                'rcmp_closure_date' => $faker->optional()->dateTimeBetween($openDate, 'now'),
                'charges_laid_flag' => $faker->boolean,
                'conviction_flag' => $faker->boolean,
                'sentence_comment' => $faker->optional()->paragraph,
                'archived' => $faker->boolean(20),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => $faker->optional(0.1)->dateTimeThisYear(),
            ]);
        }
    }
}

<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssReferralSourcesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $referralSources = [
            'INT' => 'Internal Referral',
            'EXT' => 'External Referral',
            'TIP' => 'Anonymous Tip',
            'AUD' => 'Audit Finding',
            'GOV' => 'Government Agency',
            'EDU' => 'Educational Institution',
            'MED' => 'Media Report',
            'WHS' => 'Whistleblower',
            'LAW' => 'Law Enforcement',
            'OTH' => 'Other',
        ];

        foreach ($referralSources as $code => $description) {
            DB::connection('vss')->table('referral_sources')->updateOrInsert(
                ['referral_code' => $code],
                [
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Add some additional random referral sources
        for ($i = 1; $i <= 3; $i++) {
            DB::connection('vss')->table('referral_sources')->updateOrInsert(
                ['referral_code' => $faker->unique()->lexify('???')],
                [
                    'description' => $faker->sentence,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssFundingTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $fundingTypes = [
            'GOV' => 'Government Funding',
            'PRI' => 'Private Funding',
            'NGO' => 'Non-Governmental Organization Funding',
            'COR' => 'Corporate Sponsorship',
            'GRA' => 'Grant',
            'DON' => 'Donation',
            'END' => 'Endowment',
            'LOA' => 'Loan',
            'CRO' => 'Crowdfunding',
            'SEL' => 'Self-Funded',
        ];

        foreach ($fundingTypes as $type => $description) {
            DB::connection('vss')->table('funding_types')->updateOrInsert(
                ['funding_type' => $type],
                [
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Add some additional random funding types
        for ($i = 1; $i <= 3; $i++) {
            DB::connection('vss')->table('funding_types')->insert([
                'funding_type' => $faker->unique()->lexify('???'),
                'description' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

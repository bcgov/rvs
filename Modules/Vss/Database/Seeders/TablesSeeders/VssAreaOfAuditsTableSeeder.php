<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssAreaOfAuditsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $areaOfAudits = [
            'FIN' => 'Financial Audit',
            'OPS' => 'Operational Audit',
            'COM' => 'Compliance Audit',
            'IT' => 'Information Technology Audit',
            'PER' => 'Performance Audit',
            'ENV' => 'Environmental Audit',
            'FOR' => 'Forensic Audit',
            'INT' => 'Internal Control Audit',
            'GOV' => 'Governance Audit',
            'RIS' => 'Risk Management Audit',
        ];

        foreach ($areaOfAudits as $code => $description) {
            DB::connection('vss')->table('area_of_audits')->updateOrInsert(
                ['area_of_audit_code' => $code],
                [
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Add some additional random area of audits
        for ($i = 1; $i <= 5; $i++) {
            DB::connection('vss')->table('area_of_audits')->insert([
                'area_of_audit_code' => $faker->unique()->bothify('???'),
                'description' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

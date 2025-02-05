<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssNatureOffencesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $natureOffences = [
            'FRD' => 'Fraud',
            'FOR' => 'Forgery',
            'IMP' => 'Impersonation',
            'MIS' => 'Misrepresentation',
            'THF' => 'Theft',
            'BRB' => 'Bribery',
            'EMB' => 'Embezzlement',
            'COL' => 'Collusion',
            'FAL' => 'False Documentation',
            'UNA' => 'Unauthorized Access',
        ];

        foreach ($natureOffences as $code => $description) {
            DB::connection('vss')->table('nature_offences')->updateOrInsert(
                ['nature_code' => $code],
                [
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Add some additional random nature of offences
        for ($i = 1; $i <= 3; $i++) {
            DB::connection('vss')->table('nature_offences')->updateOrInsert(
                ['nature_code' => $faker->unique()->lexify('???')],
                [
                    'description' => $faker->sentence,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssCaseAuditTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $areaOfAuditCodes = DB::connection('vss')->table('area_of_audits')->pluck('area_of_audit_code')->toArray();

        $incidentIds = DB::connection('vss')->table('incidents')->pluck('incident_id')->toArray();

        $auditTypes = ['P', 'S', 'T'];

        foreach (range(1, 50) as $index) {
            DB::connection('vss')->table('case_audit_types')->insert([
                'area_of_audit_code' => $faker->randomElement($areaOfAuditCodes),
                'incident_id' => $faker->randomElement($incidentIds),
                'audit_type' => $faker->randomElement($auditTypes),
            ]);
        }
    }
}

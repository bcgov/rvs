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

        foreach (range(1, 20) as $index) {
            $areaOfAuditCode = $faker->randomElement($areaOfAuditCodes);
            $incidentId = $faker->randomElement($incidentIds);
            $auditType = $faker->randomElement($auditTypes);

            DB::connection('vss')->table('case_audit_types')->updateOrInsert(
                [
                    'area_of_audit_code' => $areaOfAuditCode,
                    'incident_id' => $incidentId,
                    'audit_type' => $auditType,
                ],
            );
        }
    }
}

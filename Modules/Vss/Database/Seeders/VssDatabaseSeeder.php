<?php

namespace Modules\Vss\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssAreaOfAuditsTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssCaseAuditTypesTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssCaseCommentsTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssCaseFundingsTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssCaseNatureOffencesTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssCaseSanctionTypesTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssFundingTypesTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssIncidentsTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssInstitutionsTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssNatureOffencesTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssReferralSourcesTableSeeder;
use Modules\Vss\Database\Seeders\TablesSeeders\VssSanctionTypesTableSeeder;

class VssDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            VssAreaOfAuditsTableSeeder::class,
            VssFundingTypesTableSeeder::class,
            VssReferralSourcesTableSeeder::class,
            VssInstitutionsTableSeeder::class,
            VssNatureOffencesTableSeeder::class,
            VssSanctionTypesTableSeeder::class,
            VssIncidentsTableSeeder::class,
            VssCaseAuditTypesTableSeeder::class,
            VssCaseCommentsTableSeeder::class,
            VssCaseFundingsTableSeeder::class,
            VssCaseNatureOffencesTableSeeder::class,
            VssCaseSanctionTypesTableSeeder::class,
        ]);

        Model::reguard();
    }
}

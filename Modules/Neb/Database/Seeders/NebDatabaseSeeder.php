<?php

namespace Modules\Neb\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebApplicationsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebBursaryPeriodsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebElSinPySsdsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebFyBudgetsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebNebsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebPotentialRestrictionDetailsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebPotentialRestrictionsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebPotentialsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebProgramsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebRestrictionsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebSfasProgramsTableSeeder;
use Modules\Neb\Database\Seeders\TablesSeeders\NebStudentsTableSeeder;

class NebDatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (app()->environment('local', 'development')) {
            Model::unguard();
            $this->call([
                NebStudentsTableSeeder::class,
                NebBursaryPeriodsTableSeeder::class,
                NebApplicationsTableSeeder::class,
                NebPotentialRestrictionDetailsTableSeeder::class,
                NebPotentialRestrictionsTableSeeder::class,
                NebPotentialsTableSeeder::class,
                NebElSinPySsdsTableSeeder::class,
                NebFyBudgetsTableSeeder::class,
                NebNebsTableSeeder::class,
                NebProgramsTableSeeder::class,
                NebSfasProgramsTableSeeder::class,
                NebRestrictionsTableSeeder::class,
            ]);
            Model::reguard();
        }
        else {
            $this->command->info('Seeding skipped in non-local/development environment.');
        }
    }

}

<?php

namespace Modules\Yeaf\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafAdminsTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafAppealsTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafBatchesTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafCommentsTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafCountriesTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafGrantIneligiblesTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafGrantsTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafIneligiblesTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafInstitutionsTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafProgramsTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafProgramYearsTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafProvincesTableSeeder;
use Modules\Yeaf\Database\Seeders\TablesSeeders\YeafStudentsTableSeeder;

class YeafDatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (app()->environment('local', 'development')) {
            Model::unguard();
            $this->call([
                YeafCountriesTableSeeder::class,
                YeafStudentsTableSeeder::class,
                YeafCommentsTableSeeder::class,
                YeafProvincesTableSeeder::class,
                YeafProgramsTableSeeder::class,
                YeafProgramYearsTableSeeder::class,
                YeafBatchesTableSeeder::class,
                YeafAdminsTableSeeder::class,
                YeafGrantsTableSeeder::class,
                YeafIneligiblesTableSeeder::class,
                YeafGrantIneligiblesTableSeeder::class,
                YeafInstitutionsTableSeeder::class,
                YeafAppealsTableSeeder::class,
            ]);
            Model::reguard();
        }
        else {
            $this->command->info('Seeding skipped in non-local/development environment.');
        }
    }

}

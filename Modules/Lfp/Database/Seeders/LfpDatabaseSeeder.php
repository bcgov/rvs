<?php

namespace Modules\Lfp\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Lfp\Database\Seeders\TablesSeeders\LfpApplicationsTableSeeder;
use Modules\Lfp\Database\Seeders\TablesSeeders\LfpIntakesTableSeeder;
use Modules\Lfp\Database\Seeders\TablesSeeders\LfpPaymentsTableSeeder;
use Modules\Lfp\Database\Seeders\TablesSeeders\LfpStudentsTableSeeder;
use Modules\Lfp\Database\Seeders\TablesSeeders\LfpTableSeeder;

class LfpDatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (app()->environment('local', 'development')) {
            Model::unguard();
            $this->call([
                LfpTableSeeder::class,
                LfpApplicationsTableSeeder::class,
                LfpIntakesTableSeeder::class,
                LfpPaymentsTableSeeder::class,
                LfpStudentsTableSeeder::class,
            ]);
            Model::reguard();
        }
        else {
            $this->command->info('Seeding skipped in non-local/development environment.');
        }
    }

}

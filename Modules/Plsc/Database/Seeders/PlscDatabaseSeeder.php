<?php

namespace Modules\Plsc\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Plsc\Database\Seeders\TablesSeeders\PlscApplicationsTableSeeder;
use Modules\Plsc\Database\Seeders\TablesSeeders\PlscInstitutionsTableSeeder;
use Modules\Plsc\Database\Seeders\TablesSeeders\PlscStudentsTableSeeder;

class PlscDatabaseSeeder extends Seeder
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
            PlscStudentsTableSeeder::class,
            PlscInstitutionsTableSeeder::class,
            PlscApplicationsTableSeeder::class,
        ]);

        Model::reguard();
    }
}

<?php

namespace Modules\Twp\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpApplicationReasonsTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpApplicationsTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpGrantsTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpIndigeneityTypesTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpIndigeneityTypeStudentTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpInstitutionsTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpPaymentsTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpPaymentTypesTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpProgramHistoriesTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpProgramsTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpReasonsTableSeeder;
use Modules\Twp\Database\Seeders\TablesSeeders\TwpStudentsTableSeeder;

class TwpDatabaseSeeder extends Seeder
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
            TwpStudentsTableSeeder::class,
            TwpInstitutionsTableSeeder::class,
            TwpReasonsTableSeeder::class,
            TwpApplicationsTableSeeder::class,
            TwpProgramsTableSeeder::class,
            TwpProgramHistoriesTableSeeder::class,
            TwpApplicationReasonsTableSeeder::class,
            TwpGrantsTableSeeder::class,
            TwpIndigeneityTypesTableSeeder::class,
            TwpIndigeneityTypeStudentTableSeeder::class,
            TwpPaymentTypesTableSeeder::class,
            TwpPaymentsTableSeeder::class,
        ]);

        Model::reguard();
    }
}

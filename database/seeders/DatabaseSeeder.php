<?php

namespace Database\Seeders;

use Database\Seeders\ModelsSeeders\MinistrySeeder;
use Database\Seeders\ModelsSeeders\UserSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            UserSeeder::class,
            MinistrySeeder::class,
        ]);

        Model::reguard();
    }
}

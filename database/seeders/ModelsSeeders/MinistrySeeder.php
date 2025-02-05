<?php

namespace Database\Seeders\ModelsSeeders;

use App\Models\Ministry;
use Illuminate\Database\Seeder;

class MinistrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ministry::factory()->count(10)->create();
    }
}

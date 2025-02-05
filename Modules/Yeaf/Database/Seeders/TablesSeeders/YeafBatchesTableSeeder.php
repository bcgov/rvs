<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class YeafBatchesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::connection('yeaf')->table('batches')->insert([
                'batch_number' => $faker->unique()->bothify('BATCH-####'),
                'batch_date' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}

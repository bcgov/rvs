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
            $batchNumber = $faker->unique()->bothify('BATCH-####');
            $batchDate = $faker->dateTimeBetween('-1 year', 'now');

            DB::connection('yeaf')->table('batches')->updateOrInsert(
                ['batch_number' => $batchNumber],
                [
                    'batch_date' => $batchDate,
                ]
            );
        }
    }
}

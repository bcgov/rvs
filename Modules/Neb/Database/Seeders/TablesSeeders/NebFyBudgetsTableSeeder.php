<?php

namespace Modules\Neb\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NebFyBudgetsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $currentYear = date('Y');

        for ($i = 0; $i < 5; $i++) {
            $fiscalYear = ($currentYear + $i) . '-' . substr(($currentYear + $i + 1), -2);

            DB::connection('neb')->table('fy_budgets')->insert([
                'fiscal_year' => $fiscalYear,
                'budget_amount' => $faker->randomFloat(2, 1000000, 10000000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

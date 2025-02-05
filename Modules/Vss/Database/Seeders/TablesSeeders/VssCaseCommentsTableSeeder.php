<?php

namespace Modules\Vss\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VssCaseCommentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $incidentIds = DB::connection('vss')->table('incidents')->pluck('incident_id')->toArray();

        foreach (range(1, 20) as $index) {
            $createdAt = $faker->dateTimeThisYear();
            $commentDate = $faker->dateTimeBetween($createdAt, 'now');

            DB::connection('vss')->table('case_comments')->insert([
                'incident_id' => $faker->randomElement($incidentIds),
                'staff_user_id' => $faker->uuid,
                'comment_date' => $commentDate,
                'comment_text' => $faker->paragraph,
                'created_at' => $createdAt,
                'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
                'deleted_by_user_id' => $faker->optional(0.1)->uuid, // 10% chance of being deleted
                'deleted_at' => $faker->optional(0.1)->dateTimeBetween($createdAt, 'now'),
            ]);
        }
    }
}

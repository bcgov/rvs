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
            $incidentId = $faker->randomElement($incidentIds);
            $staffUserId = $faker->uuid;
            $createdAt = $faker->dateTimeThisYear();
            $commentDate = $faker->dateTimeBetween($createdAt, 'now');

            DB::connection('vss')->table('case_comments')->updateOrInsert(
                [
                    'incident_id' => $incidentId,
                    'staff_user_id' => $staffUserId,
                    'comment_date' => $commentDate,
                ],
                [
                    'comment_text' => $faker->paragraph,
                    'created_at' => $createdAt,
                    'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
                    'deleted_by_user_id' => $faker->optional(0.1)->uuid,
                    'deleted_at' => $faker->optional(0.1)->dateTimeBetween($createdAt, 'now'),
                ]
            );
        }
    }
}

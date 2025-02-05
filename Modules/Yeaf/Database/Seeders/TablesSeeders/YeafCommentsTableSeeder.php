<?php

namespace Modules\Yeaf\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class YeafCommentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentIds = DB::connection('yeaf')->table('students')->pluck('student_id')->toArray();

        foreach (range(1, 20) as $index) {
            $createdAt = $faker->dateTimeThisYear();

            DB::connection('yeaf')->table('comments')->insert([
                'student_id' => $faker->randomElement($studentIds),
                'user_id' => $faker->uuid,
                'comment_text' => $faker->paragraph,
                'deleted_at' => $faker->optional(0.1)->dateTimeThisYear(), // 10% chance of being soft deleted
                'created_at' => $createdAt,
                'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
            ]);
        }
    }
}

<?php

namespace Modules\Twp\Database\Seeders\TablesSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TwpIndigeneityTypeStudentTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentIds = DB::connection('twp')->table('students')->pluck('id')->toArray();
        $indigeneityTypeIds = DB::connection('twp')->table('indigeneity_types')->pluck('id')->toArray();

        if (empty($studentIds) || empty($indigeneityTypeIds)) {
            return;
        }

        foreach ($studentIds as $studentId) {
            $numberOfTypes = $faker->numberBetween(0, 2);
            $selectedTypes = $faker->randomElements($indigeneityTypeIds, $numberOfTypes);

            foreach ($selectedTypes as $typeId) {
                DB::connection('twp')->table('indigeneity_type_student')->updateOrInsert(
                    ['student_id' => $studentId, 'indigeneity_type_id' => $typeId],
                    []
                );
            }
        }
    }
}

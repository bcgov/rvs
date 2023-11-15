<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection(env('DB_DATABASE_YEAF'))->update("alter table institutions alter column institution_id set default nextval('institutions_id_seq'::regclass)-1;");
        DB::connection(env('DB_DATABASE_YEAF'))->update('alter sequence students_id_seq restart with 43;');
        DB::connection(env('DB_DATABASE_YEAF'))->update("alter table students alter column student_id set default nextval('students_id_seq'::regclass)-1;");
        DB::connection(env('DB_DATABASE_YEAF'))->update("alter table program_years alter column program_year_id set default nextval('program_years_id_seq'::regclass)-1;");
        DB::connection(env('DB_DATABASE_YEAF'))->update("alter table appeals alter column appeal_id set default nextval('appeals_id_seq'::regclass)-1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};

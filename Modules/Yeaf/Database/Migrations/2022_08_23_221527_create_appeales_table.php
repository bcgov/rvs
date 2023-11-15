<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::connection(env('DB_DATABASE_YEAF'))->hasTable('appeals')) {
            Schema::connection(env('DB_DATABASE_YEAF'))->create('appeals', function (Blueprint $table) {
                $table->id();

                $table->integer('appeal_id')->unique();

                $table->string('student_id');
                $table->foreign('student_id')->references('student_id')->on('students');

                $table->bigInteger('grant_id')->nullable()->comment('If application number is filled in, then relate this appeal to a particular application');
                $table->foreign('grant_id')->references('grant_id')->on('grants');

                $table->integer('program_year_id')->nullable();
                $table->foreign('program_year_id')->references('program_year_id')->on('program_years');

                $table->string('adjudicated_by_user_id')->nullable()->comment('Person who decided the appeal');

                $table->string('updated_by_user_id')->nullable()->comment('Logonid of person changing data.');

                $table->string('appeal_code', 3);
                $table->date('appeal_date')->nullable()->comment('Original Date of the appeal.');

                $table->string('status_code', 2)->nullable();
                $table->date('status_affective_date')->nullable()->comment('Date Appeal status became effective.');

                $table->string('other_appeal_explain')->nullable()->comment('If Appeal code of "other" selected, then enter the explanation here');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_YEAF'))->dropIfExists('appeals');
    }
};

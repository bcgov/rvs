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
        if (! Schema::connection(env('DB_DATABASE_TWP'))->hasTable('programs')) {
            Schema::connection(env('DB_DATABASE_TWP'))->create('programs', function (Blueprint $table): void {
                $table->id();

                $table->bigInteger('student_id');
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

                $table->bigInteger('application_id')->default(5424);
                $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');

                $table->bigInteger('yeaf_institution_id')->nullable();
                $table->integer('yeaf_program_year_id')->nullable();
                $table->date('yeaf_study_start_date')->nullable()->comment('ssd');
                $table->date('yeaf_study_end_date')->nullable()->comment('sed');
                $table->string('study_field')->default('Unselected');

                $table->string('institution_name')->nullable();
                $table->date('study_period_start_date')->nullable();
                $table->string('credential')->nullable();
                $table->integer('program_length')->nullable();
                $table->string('program_length_type')->nullable();
                $table->double('total_estimated_cost', null, 2)->default(0)->nullable();
                $table->string('student_status')->nullable();
                $table->text('comments')->nullable();
                $table->bigInteger('institution_twp_id')->nullable();
                $table->string('credential_type')->nullable();

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
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('programs');
    }
};

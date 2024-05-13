<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlscApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_PLSC'))->create('applications', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('student_id')->index();
            $table->foreign('student_id')->references('id')->on('students');

            $table->bigInteger('institution_id')->index();
            $table->foreign('institution_id')->references('id')->on('institutions');

            $table->bigInteger('app_idx')->nullable()->comment('SFAS ID.');
            $table->bigInteger('individual_idx')->nullable()->comment('SFAS ID.');

            $table->integer('application_year')->nullable();
            $table->date('receive_date')->nullable()->comment('App receive date.');
            $table->date('ssd')->nullable()->comment('Study start date.')->index();
            $table->date('sed')->nullable()->comment('Study end date.')->index();

            $table->string('program_of_study', 100)->nullable();
            $table->string('credential', 100)->nullable();

            $table->string('parent_last_name', 50)->nullable();
            $table->string('parent_first_name', 30)->nullable();
            $table->string('parent_employee_id', 30)->nullable();
            $table->string('parent_department_id', 30)->nullable();
            $table->string('parent_address', 70)->nullable();
            $table->string('parent_city', 40)->nullable();
            $table->string('parent_province', 40)->nullable();
            $table->string('parent_postal_code', 7)->nullable();
            $table->string('parent_phone_number', 15)->nullable();
            $table->string('parent_email', 50)->nullable();
            $table->string('parent_ministry', 75)->nullable();
            $table->string('parent_branch', 50)->nullable();
            $table->string('parent_job_title', 40)->nullable();
            $table->boolean('parent_three_years_in_gov')->default(false);

            $table->string('high_school_average', 5)->nullable()->comment('High school average (i.e., B, A-, etc.).');
            $table->string('post_secondary_average', 5)->nullable()->comment('Post-Secondary average (i.e., B, A-, etc.).');

            $table->boolean('seven_fifty_word_essay')->default(false)->comment('Yes/no flag indicating whether 750 word essay has been received.');
            $table->boolean('high_school_transcript')->default(false)->comment('Yes/no flag indicating whether high school transcript has been received.');
            $table->boolean('post_secondary_transcript')->default(false)->comment('Yes/no flag indicating whether Post-Secondary transcript has been received.');
            $table->boolean('student_reference_letter')->default(false)->comment('Yes/no flag indicating whether two reference letters have been received.');
            $table->boolean('communication_skills')->default(false)->comment('Yes/no flag indicating whether student demonstrates communication skills.');
            $table->boolean('enrollment_confirmed')->default(false)->comment('Yes/no flag indicating whether applicant enrollment has been confirmed.');
            $table->boolean('forward_to_committee')->default(false)->comment('Yes/no flag indicating whether to forward application to committee.');

            $table->text('comment')->nullable();

            $table->string('status_code', 5)->nullable()->comment('DCLN, DONE, INTF.')->index();
            $table->string('other_org', 75)->nullable();


            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_PLSC'))->dropIfExists('applications');
    }
}

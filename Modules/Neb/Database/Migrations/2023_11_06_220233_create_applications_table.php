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
        Schema::connection(env('DB_DATABASE_NEB'))->create('applications', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');

            $table->bigInteger('sin')->nullable()->comment('Social insurance number.');
            $table->string('application_number', 20)->nullable();
            $table->boolean('complete')->default(0)->comment('Indicates whether or not application data is complete.');
            $table->boolean('eligible')->default(0)->comment('Indicates whether or not application is eligible to receive award.');
            $table->string('award_status')->nullable()->comment('Status of appllication (i.e., Approved, Denied, etc.).');
            $table->integer('rank')->nullable()->comment('Applicant rating (compared to other applicants).');
            $table->double('total_score')->nullable()->comment('Score of the application.');
            $table->date('receive_date')->nullable()->comment('Date the application was received.');
            $table->date('effective_date')->nullable()->comment('Date application award decision made.');
            $table->date('process_date')->nullable()->comment('Date the application was processed.');
            $table->string('comment')->nullable()->comment('Information entered by staff member who processed the application.');
            $table->string('program_code')->nullable()->comment('Program code (i.e., ECE, LFP, PCLF, ...).');
            $table->bigInteger('bursary_period_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_NEB'))->dropIfExists('applications');
    }
};

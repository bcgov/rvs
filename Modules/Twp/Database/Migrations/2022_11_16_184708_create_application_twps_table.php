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
        if (! Schema::connection(env('DB_DATABASE_TWP'))->hasTable('applications')) {
            Schema::connection(env('DB_DATABASE_TWP'))->create('applications', function (Blueprint $table): void {
                $table->id();

                $table->bigInteger('student_id');
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

                $table->date('received_date')->nullable();

                $table->string('application_status')->nullable();
                $table->string('twp_status')->nullable();
                $table->text('denial_reason')->nullable();
                $table->text('exception_comments')->nullable();

                $table->string('institution_student_number', 12)->nullable();
                $table->boolean('apply_twp')->default(true);
                $table->boolean('apply_lfg')->default(false)->comment('apply for learning future grant');
                $table->boolean('confirmation_enrolment')->default(false);
                $table->string('sabc_app_number', 10)->nullable();
                $table->string('fao_name')->nullable();
                $table->string('fao_email')->nullable();
                $table->string('fao_phone')->nullable();

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
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('applications');
    }
};

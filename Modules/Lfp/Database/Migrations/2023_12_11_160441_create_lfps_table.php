<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLfpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->create('lfps', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('application_id');
            $table->bigInteger('sin')->nullable()->comment('Social insurance number.');
            $table->string('profession', 50)->nullable()->comment('Occupation of applicant.');
            $table->string('employer', 30)->nullable()->comment('Employer of applicant.');
            $table->string('employment_status', 30)->nullable()->comment('Casual/Part-time/Full-time.');
            $table->string('community', 100)->nullable()->comment('Underserved community.');
            $table->string('declined_removed_reason')->nullable()->comment('Reason why application was declined or removed.');
            $table->integer('app_idx')->nullable();

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
        Schema::connection(env('DB_DATABASE_LFP'))->dropIfExists('lfps');
    }
}

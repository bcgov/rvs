<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->create('intakes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sin')->nullable()->comment('Social insurance number.');
            $table->string('first_name', 30)->nullable();
            $table->string('last_name', 30)->nullable();
            $table->string('profession', 50)->nullable()->comment('Occupation of applicant.');
            $table->string('employer', 30)->nullable()->comment('Employer of applicant.');
            $table->string('community', 100)->nullable()->comment('Underserved community.');
            $table->string('in_good_standing')->nullable();
            $table->date('repayment_start_date')->nullable();
            $table->double('amount_owing', null, 2)->default(0)->nullable();
            $table->date('receive_date')->default(now());
            $table->string('employment_status', 30)->nullable()->comment('Casual/Part-time/Full-time.');
            $table->string('repayment_status', 30)->nullable();
            $table->string('intake_status', 30)->nullable()->comment('Ready/Pending.');
            $table->text('comment')->nullable();

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
        Schema::connection(env('DB_DATABASE_LFP'))->dropIfExists('intakes');
    }
}

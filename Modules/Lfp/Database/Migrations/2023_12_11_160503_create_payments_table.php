<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->create('payments', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('lfp_id');
            $table->foreign('lfp_id')->references('id')->on('lfps');

            $table->string('profession', 50)->nullable()->comment('Occupation of applicant.');
            $table->string('employer', 30)->nullable()->comment('Employer of applicant.');
            $table->string('employment_status', 30)->nullable()->comment('Casual/Part-time/Full-time.');
            $table->string('community', 100)->nullable()->comment('Underserved community.');

            $table->integer('app_idx')->nullable();
            $table->integer('pay_idx')->nullable();
            $table->date('reconciled_with_payment_report_date')->nullable();
            $table->date('reconciled_with_galaxy_date')->nullable();
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
        Schema::connection(env('DB_DATABASE_LFP'))->dropIfExists('payments');
    }
}

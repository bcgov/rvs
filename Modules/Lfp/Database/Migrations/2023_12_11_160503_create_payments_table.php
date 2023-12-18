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

            $table->date('payment_date')->nullable();
            $table->double('direct_lend_payment_amount', null, 2)->default(0)->nullable();
            $table->double('direct_lend_interest_payment_amount', null, 2)->default(0)->nullable();
            $table->double('risk_sharing_payment_amount', null, 2)->default(0)->nullable();
            $table->double('risk_sharing_interest_payment_amount', null, 2)->default(0)->nullable();
            $table->double('guaranteed_payment_amount', null, 2)->default(0)->nullable();
            $table->string('amount_issued', 30)->nullable()->comment('Issued 33% pmt/Issued 66% pmt/Issued final pmt.');
            $table->integer('reported_hours')->nullable()->default(0);
            $table->boolean('employment_letter_provided')->default(0)->comment('If provided then True');

            $table->date('entered_in_sfas_date')->nullable();
            $table->date('anniversary_date')->nullable();
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

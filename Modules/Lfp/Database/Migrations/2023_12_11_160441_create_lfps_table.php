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
            $table->string('application_number', 20)->nullable();


            $table->string('direct_lend', 40)->nullable()->comment('BCSLSB or None.');
            $table->string('risk_sharing_guaranteed', 40)->nullable()->comment('BNS, CIBC,  RBC, or None.');
            $table->double('direct_lend_outstanding_balance', null, 2)->nullable()->comment('Outstanding balance of direct lend loan.');
            $table->double('risk_sharing_outstanding_balance', null, 2)->nullable()->comment('Outstanding balance of risk-shared loan.');
            $table->double('guaranteed_outstanding_balance', null, 2)->nullable()->comment('Outstanding balance of guaranteed loan.');
            $table->string('profession', 50)->nullable()->comment('Occupation of applicant.');
            $table->string('employer', 30)->nullable()->comment('Employer of applicant.');
            $table->string('employment_status', 30)->nullable()->comment('Casual/Part-time/Full-time.');
            $table->boolean('why_choose1')->default(0)->comment('Always lived in the community in which I am practising.');
            $table->boolean('why_choose2')->default(0)->comment('Career opportunities/advancement.');
            $table->boolean('why_choose3')->default(0)->comment('Felt I could make the greatest contribution in an underserved community.');
            $table->boolean('why_choose4')->default(0)->comment('Other.');
            $table->boolean('why_choose5')->default(0)->comment('The incentive to have my BC student loan forgiven under the loan forgiveness program.');
            $table->string('other_reason')->nullable()->comment('Other reason why you choose to practice in an underserved community.');
            $table->string('community', 100)->nullable()->comment('Underserved community.');
            $table->string('declined_removed_reason')->nullable()->comment('Reason why application was declined or removed.');

            $table->date('birth_date')->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('status', 30)->nullable();
            $table->date('receive_date')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('process_date')->nullable();

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

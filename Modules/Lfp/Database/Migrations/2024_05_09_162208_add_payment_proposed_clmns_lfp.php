<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentProposedClmnsLfp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->table('payments', function (Blueprint $table): void {
            $table->date('anniversary_date')->nullable()->index();
            $table->date('proposed_pay_date')->nullable();
            $table->double('proposed_pay_amount', null, 2)->default(0)->nullable();
            $table->integer('proposed_hrs_of_service')->nullable();
            $table->string('sfas_pay_status', 10)->nullable()->comment('SFAS payment status')->index();
            $table->string('oc_pay_status', 10)->nullable()->comment('Openshift payment status')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->table('payments', function (Blueprint $table): void {
            $table->dropColumn('oc_pay_status');
            $table->dropColumn('sfas_pay_status');
            $table->dropColumn('proposed_hrs_of_service');
            $table->dropColumn('proposed_pay_amount');
            $table->dropColumn('proposed_pay_date');
            $table->dropColumn('anniversary_date');
        });
    }
}

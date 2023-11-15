<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_VSS'))->create('incidents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('incident_id')->unique();

            $table->double('application_number')->nullable();
            $table->string('sin');
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();

            $table->bigInteger('referral_source_id')->default(1);
            $table->foreign('referral_source_id')->references('id')->on('referral_sources')->onDelete('cascade');

            $table->date('open_date');
            $table->date('reactivate_date')->nullable();
            $table->string('year_of_audit')->nullable();

            $table->string('institution_code')->default(1);

            $table->string('auditor_user_id')->nullable();

            $table->date('audit_date')->nullable();

            $table->string('investigator_user_id')->nullable();

            $table->date('investigation_date')->nullable();

            $table->char('audit_type');

            $table->string('area_of_audit_code')->default(1);
            $table->foreign('area_of_audit_code')->references('area_of_audit_code')->on('area_of_audits')->onDelete('cascade');

            $table->string('incident_status')->default('Active');

            $table->date('close_date')->nullable();
            $table->boolean('case_close')->default('false');
            $table->string('reason_for_closing')->nullable();
            $table->string('case_outcome')->nullable();

            $table->boolean('appeal_flag')->default('false');
            $table->string('appeal_outcome')->nullable();

            $table->string('severity')->nullable();

            $table->boolean('bring_forward')->default('false');
            $table->date('bring_forward_date')->nullable();

            $table->boolean('rcmp_referral_flag')->default('false');
            $table->date('rcmp_referral_date')->nullable();
            $table->date('rcmp_closure_date')->nullable();

            $table->boolean('charges_laid_flag')->default('false');
            $table->boolean('conviction_flag')->default('false');
            $table->text('sentence_comment')->nullable();

            $table->boolean('archived')->default('false');

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
        Schema::connection(env('DB_DATABASE_VSS'))->dropIfExists('incidents');
    }
}

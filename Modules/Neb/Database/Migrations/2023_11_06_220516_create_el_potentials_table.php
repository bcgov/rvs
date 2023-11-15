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
        Schema::connection(env('DB_DATABASE_NEB'))->create('el_potentials', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_number')->nullable();
            $table->bigInteger('sin')->nullable();
            $table->string('postal_code', 16)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('first_name', 15)->nullable();
            $table->string('middle_name', 15)->nullable();
            $table->string('last_name', 25)->nullable();
            $table->double('assessed_need_amount')->nullable();
            $table->double('total_unmet_need')->nullable();
            $table->bigInteger('weeks_of_study')->nullable();
            $table->double('weekly_unmet_need')->nullable();
            $table->string('program_year', 8)->nullable();
            $table->string('street_address1', 70)->nullable();
            $table->string('street_address2', 70)->nullable();
            $table->string('city', 25)->nullable();
            $table->string('province', 40)->nullable();
            $table->string('gender', 1)->nullable();
            $table->string('phone_number', 10)->nullable();
            $table->date('study_start_date')->nullable();
            $table->date('study_end_date')->nullable();
            $table->string('institution_name', 40)->nullable();
            $table->string('program_code', 4)->nullable();
            $table->string('inst_code', 4)->nullable();
            $table->string('area_of_study', 80)->nullable();
            $table->string('degree_level', 40)->nullable();
            $table->date('receive_date')->nullable();

            $table->bigInteger('bursary_period_id')->nullable();
            $table->boolean('month_overlap')->nullable();
            $table->integer('num_day_overlap')->nullable();
            $table->boolean('valid_institution')->nullable();
            $table->boolean('restriction')->nullable();
            $table->boolean('awarded_in_prior_year')->nullable();
            $table->boolean('withdrawal')->nullable();
            $table->string('nurse_type')->nullable();
            $table->string('sector')->nullable();
            $table->string('eligibility')->nullable();
            $table->string('neb_ineligible_reason')->nullable();
            $table->integer('rank_by_unmet_need')->nullable();
            $table->integer('rank_by_nurse_type')->nullable();
            $table->integer('rank_by_sector')->nullable();
            $table->string('award_or_deny')->nullable();
            $table->string('neb_deny_reason')->nullable();
            $table->double('award_amount')->nullable();
            $table->bigInteger('sfas_award_id')->nullable();
            $table->bigInteger('supplier_no')->nullable();
            $table->string('created_by')->nullable();
            $table->boolean('finalized')->nullable()->default(0);

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
        Schema::connection(env('DB_DATABASE_NEB'))->dropIfExists('el_potentials');
    }
};

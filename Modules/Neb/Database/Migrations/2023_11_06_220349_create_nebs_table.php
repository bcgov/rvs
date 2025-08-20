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
        Schema::connection(env('DB_DATABASE_NEB'))->create('nebs', function (Blueprint $table): void {
            $table->id();

            $table->integer('application_id')->unsigned()->unique()->nullable();
            $table->integer('bursary_period_id')->unsigned()->nullable();
            $table->string('program_code')->nullable()->nullable();
            $table->string('inst_code', 10)->nullable();
            $table->date('study_start_date')->nullable();
            $table->date('study_end_date')->nullable();
            $table->string('sfas_program_code', 4)->nullable();
            $table->double('award_amount')->nullable();
            $table->string('declined_removed_reason', 60)->nullable()->comment('Reason why application was declined or removed.');
            $table->integer('sfas_award_id')->nullable()->comment('Random id produced by legacy NEB system.');
            $table->double('unmet_need')->nullable();
            $table->integer('weeks_of_study')->nullable();
            $table->double('weekly_unmet_need')->nullable();
            $table->double('assessed_need_amount')->nullable();
            $table->string('nurse_type', 3)->nullable()->comment('LPN or RN.');
            $table->string('sector', 7)->nullable()->comment('public or private');
            $table->boolean('valid_institution')->nullable()->default(0)->comment('Valid institutions are open, designated, BC institutions.');
            $table->boolean('restriction')->nullable()->default(0)->comment('Yes if borrower has one or more restrictions (including bankruptcies)->');
            $table->boolean('awarded_in_prior_year')->nullable()->default(0)->comment('Yes if borrower received grant in prior three bursary periods.');
            $table->boolean('withdrawal')->nullable()->default(0)->comment('Yes if borrower withdrew within bursary period.');
            $table->string('neb_ineligible_reason', 60)->nullable()->comment('Reason why borrower was ineligible for grant (i.e., invalid institution, restriction, etc.)');

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
        Schema::connection(env('DB_DATABASE_NEB'))->dropIfExists('nebs');
    }
};

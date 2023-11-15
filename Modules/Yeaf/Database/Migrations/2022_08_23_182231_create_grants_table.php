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
        if (! Schema::connection(env('DB_DATABASE_YEAF'))->hasTable('grants')) {
            Schema::connection(env('DB_DATABASE_YEAF'))->create('grants', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('grant_id')->unique();

                $table->bigInteger('institution_id')->nullable();
                $table->foreign('institution_id')->references('institution_id')->on('institutions');

                $table->string('student_id');
                $table->foreign('student_id')->references('student_id')->on('students');

                $table->integer('program_year_id');
                $table->foreign('program_year_id')->references('program_year_id')->on('program_years');

                $table->string('program_code')->nullable();
                $table->foreign('program_code')->references('program_code')->on('programs');

                $table->string('cheque_batch_number')->nullable()->comment('The batch this cheque was included in');
                $table->foreign('cheque_batch_number')->references('batch_number')->on('batches');

                $table->string('officer_user_id')->nullable();

                $table->string('creator_user_id')->comment('The name of the person that constructed the application');

                $table->string('update_user_id')->nullable()->comment('The name of the person that changed or updated the application');

                $table->bigInteger('application_number')->nullable()->comment('eg)  2002 - 836483 takes only straight numbers    2002836483');
                $table->integer('age')->nullable();
                $table->double('eligible_need', null, 2)->default(0)->nullable()->comment('Eligible need = total award + unmet need');
                $table->double('total_award', null, 2)->default(0)->nullable();
                $table->double('unmet_need', null, 2)->default(0)->nullable();
                $table->double('total_bcsl_award', null, 2)->default(0)->nullable();
                $table->double('total_yeaf_award', null, 2)->default(0)->nullable()->comment('Award amount student actually recieves.  This field + overaward deduction = total award student could have received this year.');
                $table->double('total_yeaf_award_remit', null, 2)->default(0)->nullable()->comment('total YEAF award to remit to BCSL     **Contract Neg.');
                $table->double('overaward', null, 2)->default(0)->nullable()->comment('If the student withdrew, then the original amount of the award would now be an over award.');
                $table->double('overaward_calc', null, 2)->default(0)->nullable();
                $table->double('overaward_deducted_amount', null, 2)->default(0)->nullable()->comment('Amount of overaward that has been deducted from this application.  Total_YEAF_Award + this field = total amount awarded');

                $table->string('reason_for_ineligibility')->nullable();
                $table->string('program_name')->nullable();
                $table->string('program_other_description')->nullable()->comment('If a program type of Other has been chosen, then specify program');
                $table->string('status_code', 10)->nullable()->comment('A=Approved, D=Denied, P=Pending');
                $table->string('date_issued_month', 2)->nullable();
                $table->string('date_issued_year', 4)->nullable();
                $table->string('application_type')->nullable()->comment('Either  "SFAS Extract" or "Paper Application"');
                $table->longText('letter_text')->nullable();
                $table->longText('custom_pending_reason')->nullable();
                $table->longText('custom_denial_reason')->nullable();

                $table->boolean('study_period_completion')->default(false);
                $table->boolean('confirmation_bcsl_remission')->default(false)->comment('flag     **Contract Neg.');
                $table->boolean('reassess')->default(false);
                $table->boolean('overaward_cleared')->default(false);
                $table->boolean('withdrawal')->default(false);

                $table->date('study_start_date')->nullable()->comment('ssd');
                $table->date('study_end_date')->nullable()->comment('sed');
                $table->date('bcsl_remission')->nullable()->comment('date of BCSL remission    **Contract Neg.');
                $table->date('letter_date')->nullable();
                $table->date('cheque_issue_date')->nullable();
                $table->date('withdrawal_date')->nullable();
                $table->date('status_date')->nullable()->comment('Date Status was set');
                $table->date('last_letter_produced_date')->nullable()->comment('Date of the last letter sent out');
                $table->date('application_receive_date')->nullable()->comment('Date application was received by the ministry');
                $table->date('last_evaluation_date')->nullable()->comment('Last time application was evaluated using the "Evaluate" button');

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
        Schema::connection(env('DB_DATABASE_YEAF'))->dropIfExists('grants');
    }
};

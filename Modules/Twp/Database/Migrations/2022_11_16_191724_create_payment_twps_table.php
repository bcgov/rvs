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
        if (! Schema::connection(env('DB_DATABASE_TWP'))->hasTable('payments')) {
            Schema::connection(env('DB_DATABASE_TWP'))->create('payments', function (Blueprint $table): void {
                $table->id();

                $table->bigInteger('student_id');
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

                $table->bigInteger('program_id');
                $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');

                $table->bigInteger('application_id')->default(5424);
                $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');

                $table->date('payment_date');
                $table->double('payment_amount', null, 2)->default(0)->nullable();

                $table->bigInteger('payment_type_id')->default(1);

                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();

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
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('payments');
    }
};

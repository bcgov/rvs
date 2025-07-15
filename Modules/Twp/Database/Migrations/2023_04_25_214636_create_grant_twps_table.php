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
        if (! Schema::connection(env('DB_DATABASE_TWP'))->hasTable('grants')) {
            Schema::connection(env('DB_DATABASE_TWP'))->create('grants', function (Blueprint $table): void {
                $table->id();

                $table->bigInteger('application_id')->default(5424);
                $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');

                $table->bigInteger('student_id');
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

                $table->date('received_date');
                $table->string('grant_status')->nullable();
                $table->text('grant_comments')->nullable();
                $table->double('grant_amount', null, 2)->default(0)->nullable();

                $table->string('created_by');
                $table->string('updated_by');

                $table->string('extra_1')->nullable();
                $table->string('extra_2')->nullable();

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
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('grants');
    }
};

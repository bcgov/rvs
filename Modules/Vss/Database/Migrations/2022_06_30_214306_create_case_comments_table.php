<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_VSS'))->create('case_comments', function (Blueprint $table): void {
            $table->id();

            $table->bigInteger('incident_id')->default(1);
            $table->foreign('incident_id')->references('incident_id')->on('incidents')->onDelete('cascade');

            $table->string('staff_user_id')->default(1);

            $table->date('comment_date')->nullable();
            $table->longText('comment_text')->nullable();

            $table->timestamps();
            $table->string('deleted_by_user_id')->nullable();
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
        Schema::connection(env('DB_DATABASE_VSS'))->dropIfExists('case_comments');
    }
}

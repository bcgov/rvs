<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->create('applications', function (Blueprint $table): void {
            $table->id();

            $table->bigInteger('lfp_id');
            $table->foreign('lfp_id')->references('id')->on('lfps');

            $table->string('application_number', 20)->nullable();
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
        Schema::connection(env('DB_DATABASE_LFP'))->dropIfExists('applications');
    }
}

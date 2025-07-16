<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseNatureOffencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_VSS'))->create('case_nature_offences', function (Blueprint $table): void {
            $table->bigInteger('incident_id')->default(1);
            $table->foreign('incident_id')->references('incident_id')->on('incidents')->onDelete('cascade');

            $table->string('nature_code');
            $table->foreign('nature_code')->references('nature_code')->on('nature_offences')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_VSS'))->dropIfExists('case_nature_offences');
    }
}

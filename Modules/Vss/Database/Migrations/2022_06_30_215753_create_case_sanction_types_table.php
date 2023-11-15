<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseSanctionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_VSS'))->create('case_sanction_types', function (Blueprint $table) {
            $table->bigInteger('incident_id')->default(1);
            $table->foreign('incident_id')->references('incident_id')->on('incidents')->onDelete('cascade');

            $table->integer('sanction_code');
            $table->foreign('sanction_code')->references('sanction_code')->on('sanction_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_VSS'))->dropIfExists('case_sanction_types');
    }
}

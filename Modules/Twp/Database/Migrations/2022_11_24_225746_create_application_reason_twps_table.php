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
        if (! Schema::connection(env('DB_DATABASE_TWP'))->hasTable('application_reasons')) {
            Schema::connection(env('DB_DATABASE_TWP'))->create('application_reasons', function (Blueprint $table): void {
                $table->bigInteger('application_id');
                $table->foreign('application_id')->references('id')->on('applications');
                $table->bigInteger('reason_id');
                $table->foreign('reason_id')->references('id')->on('reasons');
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
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('application_reasons');
    }
};

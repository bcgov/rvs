<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlscUtilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_PLSC'))->create('utils', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->index();
            $table->string('field_type')->index();
            $table->boolean('active_flag')->default(true)->index();

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
        Schema::connection(env('DB_DATABASE_PLSC'))->dropIfExists('utils');
    }
}

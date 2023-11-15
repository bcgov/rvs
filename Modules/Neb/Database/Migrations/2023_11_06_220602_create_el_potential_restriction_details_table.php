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
        Schema::connection(env('DB_DATABASE_NEB'))->create('el_potential_restriction_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sin')->nullable();
            $table->string('restriction_code', 12)->nullable();
            $table->string('restriction_description', 40)->nullable();
            $table->date('applied_date')->nullable();

            $table->bigInteger('bursary_period_id')->nullable();
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
        Schema::connection(env('DB_DATABASE_NEB'))->dropIfExists('el_potential_restriction_details');
    }
};

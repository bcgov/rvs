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
        Schema::connection(env('DB_DATABASE_NEB'))->create('el_sin_py_ssds', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('sin')->nullable();
            $table->string('max_program_year')->nullable();
            $table->date('max_study_start_date')->nullable();
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
        Schema::connection(env('DB_DATABASE_NEB'))->dropIfExists('el_sin_py_ssds');
    }
};

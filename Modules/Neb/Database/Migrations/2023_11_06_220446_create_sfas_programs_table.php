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
        Schema::connection(env('DB_DATABASE_NEB'))->create('sfas_programs', function (Blueprint $table): void {
            $table->id();

            $table->string('neb_program_code', 4)->nullable()->comment('Foreign key - NEB_PROGRAM (used for historical purposes)');
            $table->foreign('neb_program_code')->references('program_code')->on('programs');

            $table->string('sfas_program_code', 4);
            $table->string('area_of_study', 50)->nullable()->comment('Program name.');
            $table->string('degree_level', 20)->nullable()->comment('Degree level (certification, diploma, masters, etc.)');
            $table->string('nurse_type', 3)->nullable()->comment('Type of nursing program (LPN or RN)');
            $table->boolean('eligible')->nullable()->default(0)->comment('Flag to indicate if program is NEB-eligible.');

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
        Schema::connection(env('DB_DATABASE_NEB'))->dropIfExists('sfas_programs');
    }
};

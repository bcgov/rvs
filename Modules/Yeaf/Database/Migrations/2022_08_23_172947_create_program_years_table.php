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
        if (! Schema::connection(env('DB_DATABASE_YEAF'))->hasTable('program_years')) {
            Schema::connection(env('DB_DATABASE_YEAF'))->create('program_years', function (Blueprint $table) {
                $table->id();
                $table->integer('program_year_id')->unique();
                $table->string('year_start', 4)->nullable();
                $table->string('year_end', 4)->nullable();
                $table->double('grant_amount', null, 2)->default(0);

                $table->integer('max_years_allowed')->default(4)->comment('Maximum number of years a student is allowed to be in the program');
                $table->integer('min_age')->comment('Minimum age required to apply');
                $table->integer('max_age')->comment('Maximum age required to apply');

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
        Schema::connection(env('DB_DATABASE_YEAF'))->dropIfExists('program_years');
    }
};

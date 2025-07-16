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
        if (! Schema::connection(env('DB_DATABASE_YEAF'))->hasTable('provinces')) {
            Schema::connection(env('DB_DATABASE_YEAF'))->create('provinces', function (Blueprint $table): void {
                $table->id();

                $table->string('country_code');
                $table->foreign('country_code')->references('country_code')->on('countries');

                $table->string('province_code', 2);
                $table->string('province_name');
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
        Schema::connection(env('DB_DATABASE_YEAF'))->dropIfExists('provinces');
    }
};

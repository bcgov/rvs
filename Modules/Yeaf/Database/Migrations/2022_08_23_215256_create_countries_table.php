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
        if (! Schema::connection(env('DB_DATABASE_YEAF'))->hasTable('countries')) {
            Schema::connection(env('DB_DATABASE_YEAF'))->create('countries', function (Blueprint $table): void {
                $table->id();
                $table->string('country_code', 3)->unique();
                $table->string('country_name');
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
        Schema::connection(env('DB_DATABASE_YEAF'))->dropIfExists('countries');
    }
};

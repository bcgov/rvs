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
        if (! Schema::connection(env('DB_DATABASE_YEAF'))->hasTable('institutions')) {
            Schema::connection(env('DB_DATABASE_YEAF'))->create('institutions', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('institution_id')->unique();
                $table->string('name')->nullable();
                $table->string('address');
                $table->string('city');
                $table->string('province', 3)->nullable();
                $table->string('state', 3)->nullable();
                $table->string('postal_code', 7)->nullable();
                $table->string('zip_code', 6)->nullable();
                $table->string('country', 7)->nullable();
                $table->string('tele', 16)->nullable();
                $table->string('fax', 16)->nullable();
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
        Schema::connection(env('DB_DATABASE_YEAF'))->dropIfExists('institutions');
    }
};

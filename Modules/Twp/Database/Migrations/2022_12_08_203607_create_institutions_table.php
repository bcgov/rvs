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
        if (! Schema::connection(env('DB_DATABASE_TWP'))->hasTable('institutions')) {
            Schema::connection(env('DB_DATABASE_TWP'))->create('institutions', function (Blueprint $table) {
                $table->id();

                $table->string('name');
                $table->string('contact_name')->nullable();
                $table->string('contact_email')->nullable();
                $table->boolean('active_flag')->default(false);

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
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('institutions');
    }
};

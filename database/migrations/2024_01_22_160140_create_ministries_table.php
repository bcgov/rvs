<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ministries', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('branch')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('prov')->nullable();
            $table->string('postal')->nullable();
            $table->string('tele_victoria')->nullable();
            $table->string('tele_lower')->nullable();
            $table->string('tele_toll_free')->nullable();
            $table->string('TTY_line')->nullable();
            $table->string('location')->nullable();
            $table->string('location_city')->nullable();
            $table->string('location_prov')->nullable();
            $table->string('location_postal')->nullable();
            $table->string('location_tele_toll_free')->nullable();
            $table->string('fax')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ministries');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::connection(env('DB_DATABASE_TWP'))
            ->table('applications', function (Blueprint $table) {
                $table->text('comment')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::connection(env('DB_DATABASE_TWP'))
            ->table('applications', function (Blueprint $table) {
                $table->dropColumn('comment');
            });
    }
};

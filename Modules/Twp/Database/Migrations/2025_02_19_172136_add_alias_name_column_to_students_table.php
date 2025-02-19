<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::connection(env('DB_DATABASE_TWP'))
            ->table('students', function (Blueprint $table) {
                $table->string('alias_name')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::connection(env('DB_DATABASE_TWP'))
            ->table('students', function (Blueprint $table) {
                $table->dropColumn('alias_name');
            });
    }

};

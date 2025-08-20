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
        Schema::connection(env('DB_DATABASE_LFP'))->table('payments', function (Blueprint $table) {
            $table->boolean('both_eligibility_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->table('payments', function (Blueprint $table) {
            $table->dropColumn('both_eligibility_status');
        });
    }
};

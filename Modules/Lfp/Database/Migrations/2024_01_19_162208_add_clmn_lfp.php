<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClmnLfp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->table('intakes', function (Blueprint $table): void {
            $table->string('denial_reason')->nullable()->comment('Reason why application was declined or removed.');
            $table->date('proposed_registration_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->table('intakes', function (Blueprint $table): void {
            $table->dropColumn('proposed_registration_date');
            $table->dropColumn('denial_reason');
        });
    }
}

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
        if (!Schema::connection(env('DB_DATABASE_LFP'))->hasColumn('lfps', 'direct_lend')) {
            Schema::connection(env('DB_DATABASE_LFP'))->table('lfps', function (Blueprint $table): void {
                $table->string('direct_lend')->nullable();
            });
        }
        if (!Schema::connection(env('DB_DATABASE_LFP'))->hasColumn('lfps', 'risk_sharing_guaranteed')) {
            Schema::connection(env('DB_DATABASE_LFP'))->table('lfps', function (Blueprint $table): void {
                $table->string('risk_sharing_guaranteed')->nullable();
            });
        }

        Schema::connection(env('DB_DATABASE_LFP'))->table('lfps', function (Blueprint $table): void {
            $table->text('comment')->nullable();
            $table->string('full_name_alias', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_LFP'))->table('lfps', function (Blueprint $table): void {
            $table->dropColumn('comment');
            $table->dropColumn('full_name_alias');
            $table->dropColumn('risk_sharing_guaranteed');
            $table->dropColumn('direct_lend');
        });
    }
};

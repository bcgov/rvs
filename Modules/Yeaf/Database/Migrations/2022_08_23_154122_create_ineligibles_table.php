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
        if (! Schema::connection(env('DB_DATABASE_YEAF'))->hasTable('ineligibles')) {
            Schema::connection(env('DB_DATABASE_YEAF'))->create('ineligibles', function (Blueprint $table) {
                $table->id();
                $table->string('code_id', 2);
                $table->string('description')->nullable();
                $table->boolean('active_flag')->default(true);
                $table->string('code_type', 1)->nullable()->comment('D = Denied status reason, P = Pending status reason');
                $table->longText('paragraph_text')->nullable();
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
        Schema::connection(env('DB_DATABASE_YEAF'))->dropIfExists('ineligibles');
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_TWP'))->create('indigeneity_types', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->boolean('active_flag')->default(true);
            $table->timestamps();
        });

        // seed the table
        DB::connection(env('DB_DATABASE_TWP'))->table('indigeneity_types')->insert([
            [
                'title' => 'First Nations',
                'active_flag' => true
            ],
            [
                'title' => 'Metis',
                'active_flag' => true
            ],
            [
                'title' => 'Inuit',
                'active_flag' => true
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('indigeneity_types');
    }
};

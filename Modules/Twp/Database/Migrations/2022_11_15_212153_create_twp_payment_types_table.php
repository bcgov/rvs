<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::connection(env('DB_DATABASE_TWP'))->create('payment_types', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->boolean('active_flag')->default(true);
            $table->timestamps();
        });

        // seed the table
        DB::connection(env('DB_DATABASE_TWP'))->table('payment_types')->insert([
            ['title' => 'Tuition and Fees',
                'active_flag' => true, ],
            ['title' => 'Grant',
                'active_flag' => true, ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('payment_types');
    }
};

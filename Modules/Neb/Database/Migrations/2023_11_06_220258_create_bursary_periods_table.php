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
        Schema::connection(env('DB_DATABASE_NEB'))->create('bursary_periods', function (Blueprint $table): void {
            $table->id();
            $table->date('bursary_period_start_date')->unique()->nullable();
            $table->date('bursary_period_end_date')->unique()->nullable();
            $table->boolean('awarded')->default(0)->comment('Indicates if awards are distributed for period (yes or no).');
            $table->double('default_award')->nullable()->comment('The default amount of award for period.');
            $table->double('period_budget')->nullable()->comment('Total budget amount for period.');
            $table->integer('rn_budget')->nullable()->comment('Number between 1 and 100; portion of budget to allocate to RN programs.');
            $table->integer('public_sector_budget')->nullable()->comment('Number between 1 and 100; portion of budget to allocate to public sector programs.');
            $table->string('budget_allocation_type')->nullable()->comment('Method of allocating budget; either by sector (private/public), nurse type (LPN/RN), or none.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_NEB'))->dropIfExists('bursary_periods');
    }
};

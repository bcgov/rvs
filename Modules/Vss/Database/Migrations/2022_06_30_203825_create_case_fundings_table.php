<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseFundingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_VSS'))->create('case_fundings', function (Blueprint $table): void {
            $table->id();

            $table->bigInteger('incident_id')->default(1);
            $table->foreign('incident_id')->references('incident_id')->on('incidents')->onDelete('cascade');

            $table->double('application_number')->nullable();

            $table->string('funding_type');
            $table->foreign('funding_type')->references('funding_type')->on('funding_types')->onDelete('cascade');

            $table->float('over_award')->default(0);
            $table->float('prevented_funding')->default(0);
            $table->date('fund_entry_date')->nullable();

            $table->timestamps();
            $table->string('deleted_by_user_id')->nullable();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_VSS'))->dropIfExists('case_fundings');
    }
}

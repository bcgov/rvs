<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_DATABASE_PLSC'))->create('students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sin')->comment('Social insurance number.')->index();
            $table->bigInteger('pen')->nullable()->comment('Personal education number.');
            $table->bigInteger('individual_idx')->nullable()->comment('SFAS ID.');
            $table->date('birth_date')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('first_name', 30)->nullable()->index();
            $table->string('last_name', 50)->nullable()->index();
            $table->string('address1', 70)->nullable();
            $table->string('address2', 70)->nullable();
            $table->string('city', 40)->nullable();
            $table->string('postal_code', 7)->nullable();
            $table->string('province', 40)->nullable();
            $table->string('country', 40)->nullable();
            $table->string('phone_number', 10)->nullable();
            $table->string('email', 50)->nullable();
            $table->text('comment')->nullable();
            $table->string('citizenship', 20)->nullable();
            $table->string('marital_status', 30)->nullable();
            $table->string('title', 10)->nullable()->comment('Title (i.e., Mr., Mrs., etc.)');

            $table->timestamps();
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
        Schema::connection(env('DB_DATABASE_PLSC'))->dropIfExists('students');
    }
}

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
        Schema::connection(env('DB_DATABASE_TWP'))->create('indigeneity_type_student', function (Blueprint $table): void {
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('indigeneity_type_id')->constrained('indigeneity_types')->onDelete('cascade');
            $table->primary(['student_id', 'indigeneity_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('indigeneity_type_student');
    }
};

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
        if (! Schema::connection(env('DB_DATABASE_YEAF'))->hasTable('comments')) {
            Schema::connection(env('DB_DATABASE_YEAF'))->create('comments', function (Blueprint $table) {
                $table->id();

                $table->string('student_id');
                $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');

                $table->string('user_id');

                $table->longText('comment_text')->nullable();
                $table->softDeletes();
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
        Schema::connection(env('DB_DATABASE_YEAF'))->dropIfExists('comments');
    }
};

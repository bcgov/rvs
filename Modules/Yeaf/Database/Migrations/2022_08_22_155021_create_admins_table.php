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
        if (! Schema::connection(env('DB_DATABASE_YEAF'))->hasTable('admins')) {
            Schema::connection(env('DB_DATABASE_YEAF'))->create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('contact_fname')->nullable();
                $table->string('contact_lname')->nullable();
                $table->string('contact_tele')->nullable();
                $table->string('contact_title')->nullable();
                $table->string('contact_dept')->nullable();
                $table->string('contact_branch')->nullable();
                $table->string('ministry')->nullable();
                $table->string('ministry_branch')->nullable();
                $table->string('ministry_address')->nullable();
                $table->string('ministry_city')->nullable();
                $table->string('ministry_prov')->nullable();
                $table->string('ministry_postal')->nullable();
                $table->string('ministry_tele_victoria')->nullable();
                $table->string('ministry_tele_lower')->nullable();
                $table->string('ministry_tele_toll_free')->nullable();
                $table->string('ministry_TTY_line')->nullable();
                $table->string('ministry_location')->nullable();
                $table->string('ministry_location_city')->nullable();
                $table->string('ministry_location_prov')->nullable();
                $table->string('ministry_location_postal')->nullable();
                $table->string('ministry_location_tele_toll_free')->nullable();
                $table->string('ministry_fax')->nullable();
                $table->string('org')->nullable();
                $table->string('org_fname')->nullable();
                $table->string('org_lname')->nullable();
                $table->string('org_fax')->nullable();
                $table->string('user_fname')->nullable();
                $table->string('user_lname')->nullable();
                $table->string('user_branch')->nullable();
                $table->string('user_tele')->nullable();
                $table->string('user_fax')->nullable();
                $table->string('start_month')->nullable();
                $table->string('start_day')->nullable();
                $table->string('end_month')->nullable();
                $table->string('end_day')->nullable();
                $table->longText('temp')->nullable();
                $table->string('application_name')->nullable();
                $table->string('application_abbreviation')->nullable();
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
        Schema::connection(env('DB_DATABASE_YEAF'))->dropIfExists('admins');
    }
};

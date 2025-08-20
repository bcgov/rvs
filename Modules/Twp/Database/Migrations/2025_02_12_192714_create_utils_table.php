<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::connection(env('DB_DATABASE_TWP'))
            ->create('utils', function (Blueprint $table): void {
                $table->id();
                $table->string('field_name');
                $table->string('field_type');
                $table->text('field_description')->nullable();
                $table->boolean('active_flag')->default(TRUE);
                $table->timestamps();
            });

        $defaultData = [
            // Citizenship
            [
                'field_name' => 'Canadian Citizen',
                'field_type' => 'Citizenship',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Permanent Resident',
                'field_type' => 'Citizenship',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Protected Person',
                'field_type' => 'Citizenship',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Other',
                'field_type' => 'Citizenship',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            // Application Status
            [
                'field_name' => 'APPROVED',
                'field_type' => 'Application Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'DENIED',
                'field_type' => 'Application Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'IN PROGRESS',
                'field_type' => 'Application Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'APPROVED ON EXCEPTION',
                'field_type' => 'Application Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'WITHDRAWN',
                'field_type' => 'Application Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            // Exception Comment
            [
                'field_name' => 'Location of Study',
                'field_type' => 'Exception Comment',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Time in Care',
                'field_type' => 'Exception Comment',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Legal Status',
                'field_type' => 'Exception Comment',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Age',
                'field_type' => 'Exception Comment',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            // Credential Level
            [
                'field_name' => 'Bachelor',
                'field_type' => 'Credential Level',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Certificate',
                'field_type' => 'Credential Level',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Diploma',
                'field_type' => 'Credential Level',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Non-Credit',
                'field_type' => 'Credential Level',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Unclassified Qualifying',
                'field_type' => 'Credential Level',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Other',
                'field_type' => 'Credential Level',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            // Field of Study
            [
                'field_name' => 'Art',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Sciences',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Trades',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Health',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Education',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Visual and Performing Arts',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Business and Management',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Engineering and Applied Sciences',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Human and Social Services',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Developmental',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Personal Improvement and Leisure',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Other',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Unselected',
                'field_type' => 'Field of Study',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            // Student Status
            [
                'field_name' => 'Attending',
                'field_type' => 'Student Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Completed',
                'field_type' => 'Student Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Hiatus',
                'field_type' => 'Student Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'Never Attended',
                'field_type' => 'Student Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'No Longer Attending',
                'field_type' => 'Student Status',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            // Program Length Type
            [
                'field_name' => 'day',
                'field_type' => 'Program Length Type',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'week',
                'field_type' => 'Program Length Type',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'month',
                'field_type' => 'Program Length Type',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
            [
                'field_name' => 'year',
                'field_type' => 'Program Length Type',
                'active_flag' => TRUE,
                'created_at' => now(),
            ],
        ];

        DB::connection(env('DB_DATABASE_TWP'))
            ->table('utils')
            ->insert($defaultData);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::connection(env('DB_DATABASE_TWP'))->dropIfExists('utils');
    }

};

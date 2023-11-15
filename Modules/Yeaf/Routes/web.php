<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('yeaf')->group(function () {
    Route::get('/maintenance/reports/{type}', 'ReportController@index')->name('reports.type');
    Route::group(
        [
            'middleware' => ['auth', 'yeaf_active'],
            'as' => 'yeaf.',
        ], function () {

            Route::resource('students', 'StudentController');
            Route::resource('institutions', 'InstitutionController');
            Route::resource('comments', 'CommentController');
            Route::resource('grants', 'GrantController');
            Route::group(
                [
                    'prefix' => 'grants',
                    'as' => 'grants.',
                ], function () {
                    Route::get('/validate-letter/{grant}', 'GrantController@validateLetter')->name('validate-letter');
                    Route::get('/export-letter/{grant}/{docName?}', 'GrantController@exportLetter')->name('export-letter');
                    Route::get('/export-withdrawal-letter/{grant}/{docName?}', 'GrantController@exportWithdrawalLetter')->name('export-withdrawal-letter');
                    Route::post('/evaluate/{grant}', 'GrantController@evaluateApp')->name('evaluate');
                });

            //authenticated admin routes
            Route::group(
                [
                    'prefix' => 'maintenance',
                    'middleware' => 'yeaf_admin',
                    'as' => 'maintenance.',
                ], function () {
                    Route::get('/staff', 'MaintenanceController@staffList')->name('staff.list');
                    Route::get('/staff/{user}', 'MaintenanceController@staffShow')->name('staff.show');
                    Route::post('/staff/{user}', 'MaintenanceController@staffEdit')->name('staff.edit');
                    Route::get('/ineligibles', 'MaintenanceController@ineligiblesList')->name('ineligibles.list');
                    Route::post('/ineligibles', 'MaintenanceController@ineligibleStore')->name('ineligible.store');
                    Route::post('/ineligibles/{ineligible}', 'MaintenanceController@ineligibleEdit')->name('ineligible.edit');
                    Route::get('/program-years', 'MaintenanceController@programYearsList')->name('program-years.list');
                    Route::post('/program-years', 'MaintenanceController@programYearStore')->name('program-year.store');
                    Route::post('/program-years/{programYear}', 'MaintenanceController@programYearEdit')->name('program-year.edit');
                    Route::get('/batches', 'MaintenanceController@batchesList')->name('batches.list');
                    Route::post('/batches', 'MaintenanceController@batchStore')->name('batches.store');
                    Route::post('/batches/{batch}', 'MaintenanceController@batchEdit')->name('batches.edit');
                    Route::get('/ministry', 'MaintenanceController@ministryShow')->name('ministry.show');
                    Route::post('/ministry/{admin}', 'MaintenanceController@ministryUpdate')->name('ministry.update');
                    Route::get('/reports', 'MaintenanceController@reportsShow')->name('reports.show');
                    Route::get('/letters', 'MaintenanceController@letters')->name('letters.index');
                    Route::get('/download-letters/{type}/{extra}', 'MaintenanceController@downloadLetter')->name('letters.download');

                    Route::post('/reports/switch', 'ReportController@switchOn')->name('reports.switch-on');
                });
        });

});

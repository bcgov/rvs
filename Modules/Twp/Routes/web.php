<?php

use Modules\Twp\Http\Middleware\HandleInertiaRequests;

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
Route::prefix('twp')->middleware(['web', 'twp_inertia'])->group(function (): void {
    Route::get('/maintenance/reports/{type}', 'ReportController@fetchReport')->name('twp.reports.type');
    Route::group(
        [
            'middleware' => ['auth', 'twp_active'],
            'as' => 'twp.',
        ], function (): void {
            Route::resource('students', 'StudentController');
            Route::delete('students/{student}', 'StudentController@delete');
            Route::get('/application-list', 'StudentController@apps')->name('application-list');
            Route::resource('applications', 'ApplicationController');
            Route::resource('programs', 'ProgramController');
            Route::resource('payments', 'PaymentController');
            Route::resource('grants', 'GrantController');
            Route::put('/application-status/{application}', 'ApplicationController@applicationStatus')->name('application-status.update');
            Route::post('/students-letters/{type}/{extra}', 'ApplicationController@downloadLetter')->name('applications.letters.download');
            Route::post('/students-letters/{extra}', 'ApplicationController@downloadSchoolLetter')->name('applications.letters.school-download');
            Route::post('/students-transfer-letters/{extra}', 'ApplicationController@downloadStudentTransferLetter')->name('applications.letters.student-transfer-download');

            Route::group(
                [
                    'prefix' => 'maintenance',
                    'as' => 'maintenance.',
                ], function (): void {
                    Route::get('/staff', 'MaintenanceController@staffList')->name('staff.list');
                    Route::get('/staff/{user}', 'MaintenanceController@staffShow')->name('staff.show');
                    Route::post('/staff/{user}', 'MaintenanceController@staffEdit')->name('staff.edit');

                    Route::get('/indigeneity', 'IndigeneityTypeController@index')->name('indigeneity.index');
                    Route::post('/indigeneity', 'IndigeneityTypeController@store')->name('indigeneity.store');
                    Route::put('/indigeneity/{indigeneity}', 'IndigeneityTypeController@update')->name('indigeneity.update');

                    Route::get('/utils', 'MaintenanceController@utilList')->name('utils.list');
                    Route::put('/utils/{util}', 'MaintenanceController@utilUpdate')->name('utils.update');
                    Route::post('/utils', 'MaintenanceController@utilStore')->name('utils.store');

                    Route::get('/institutions', 'MaintenanceController@institutionList')->name('institutions.list');
                    Route::post('/institutions', 'MaintenanceController@institutionStore')->name('institutions.store');
                    Route::put('/institutions/{institution}', 'MaintenanceController@institutionUpdate')->name('institutions.update');
                    Route::get('/reasons', 'MaintenanceController@reasonList')->name('reasons.list');
                    Route::post('/reasons', 'MaintenanceController@reasonStore')->name('reasons.store');
                    Route::put('/reasons/{reason}', 'MaintenanceController@reasonUpdate')->name('reasons.update');
                    Route::get('/payments', 'MaintenanceController@paymentList')->name('payments.list');
                    Route::post('/payments', 'MaintenanceController@paymentStore')->name('payments.store');
                    Route::put('/payments/{payment}', 'MaintenanceController@paymentUpdate')->name('payments.update');
                    Route::get('/reports', 'ReportController@reportsShow')->name('reports.show');
                    Route::post('/reports/switch', 'ReportController@switchOn')->name('reports.switch-on');
                });
        });
});

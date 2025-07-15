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

Route::prefix('vss')->group(function (): void {
    //    Route::get('/', 'VssController@index');
    Route::group(
        [
            'middleware' => ['auth', 'vss_active'],
            'as' => 'vss.',
        ], function (): void {
            Route::get('/fetch-active-users', 'AdminController@activeUsers')->name('fetch-active-users');
            Route::get('/fetch-cancelled-users', 'AdminController@cancelledUsers')->name('fetch-cancelled-users');

            Route::get('/dashboard', 'IncidentController@dashboard')->name('dashboard');
            Route::resource('cases', Modules\Vss\Http\Controllers\IncidentController::class);

            Route::get('/case-search/{x?}', function () {
                return Redirect::route('dashboard');
            })->name('case-sin-search-dashboard-redirect');

            Route::resource('case-funding', Modules\Vss\Http\Controllers\CaseFundingController::class);

            Route::post('/cases/{case}/delete-sanction', 'CaseSanctionTypeController@deleteSanction')->name('case-funding-delete-sanction');
            Route::post('/cases/{case}/delete-offence', 'CaseNatureOffenceController@deleteOffence')->name('case-funding-delete-offence');
            Route::post('/cases/{case}/delete-area-of-audit', 'CaseAuditTypeController@deleteAuditType')->name('case-funding-delete-audit-type');

            Route::resource('case-comment', Modules\Vss\Http\Controllers\CaseCommentController::class);

            Route::get('/reports', 'AdminController@reports')->name('reports');
            Route::get('/reports/download/{case}', 'ReportController@downloadSingleStudentReport')->name('download-single-student-report');

            Route::post('/reports', 'ReportController@searchReports')->name('reports-search');

        //authenticated admin routes
            Route::group(['middleware' => 'vss_admin'], function (): void {

                Route::name('maintenance.')->group(function (): void {
                    Route::get('/maintenance/staff', 'MaintenanceController@staffList')->name('staff.list');
                    Route::get('/maintenance/staff/{user}', 'MaintenanceController@staffShow')->name('staff.show');
                    Route::post('/maintenance/staff/{user}', 'MaintenanceController@staffEdit')->name('staff.edit');

                    Route::prefix('maintenance')->group(function (): void {
                        Route::resource('area-of-audit', Modules\Vss\Http\Controllers\AreaOfAuditController::class);
                        Route::resource('sanction-type', Modules\Vss\Http\Controllers\SanctionTypeController::class);
                        Route::resource('nature-offence', Modules\Vss\Http\Controllers\NatureOffenceController::class);
                        Route::resource('referral-source', Modules\Vss\Http\Controllers\ReferralSourceController::class);
                        Route::resource('school', Modules\Vss\Http\Controllers\InstitutionController::class);
                    });
                });

                Route::get('/archive', 'AdminController@reports')->name('archive');
                Route::get('/archive/cases', 'IncidentController@archived')->name('archive.cases.list');

            });

        });
});

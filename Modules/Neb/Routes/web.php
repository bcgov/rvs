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

Route::prefix('neb')->group(function () {
    //    Route::get('/maintenance/reports/{type}', 'ReportController@fetchReport')->name('twp.reports.type');
    Route::group(
        [
            'middleware' => ['auth', 'neb_active'],
            'as' => 'neb.',
        ], function () {
            //        Route::resource('students', 'StudentController');
            Route::get('/dashboard', 'BursaryPeriodController@index')->name('dashboard');
            Route::group(['middleware' => 'neb_admin'], function () {

                Route::post('/finalize-neb', 'NebController@finalizeNeb')->name('finalize-neb');
                Route::post('/process-neb', 'NebController@processNeb')->name('process-neb');
                Route::get('/export-neb/{type}/{id}', 'NebController@exportNeb')->name('export-neb');

                Route::get('/process-neb', function () {
                    return redirect('neb.dashboard');
                });
                //    Route::get('/process-neb', async ({ response }) => {
                //                response.redirect('/dashboard')
                //    });

                Route::post('/bursary-periods/fetch-neb', 'NebController@fetchNeb')->name('fetch-neb');
                //    Route::get('/bursary-periods/fetch-neb', async ({ response }) => {
                //                response.redirect('/dashboard')
                //    });
                Route::get('/bursary-periods/fetch-neb', function () {
                    return redirect('neb.dashboard');
                });

                Route::get('/bursary-periods/show/{id}', 'BursaryPeriodController@show')->name('bursary-periods.show');
                Route::get('/bursary-periods/tobe-awarded', 'BursaryPeriodController@tobeAwarded')->name('bursary-periods.tobe-awarded');
                Route::get('/bursary-periods', 'BursaryPeriodController@index')->name('bursary-periods.index');
                Route::get('/bursary-periods/create', 'BursaryPeriodController@index')->name('bursary-periods.create');
                Route::get('/bursary-periods/fetch/{id?}', 'BursaryPeriodController@fetch')->name('bursary-periods.fetch');
                Route::post('/bursary-periods/store', 'BursaryPeriodController@store')->name('bursary-periods.store');
                Route::put('/bursary-periods/update', 'BursaryPeriodController@update')->name('bursary-periods.update');
                Route::post('/bursary-periods/delete', 'BursaryPeriodController@delete')->name('bursary-periods.delete');

                Route::get('/restrictions', 'RestrictionController@index')->name('restrictions.index');
                Route::get('/restrictions/fetch/{id?}', 'RestrictionController@fetch')->name('restrictions.fetch');
                Route::post('/restrictions/store', 'RestrictionController@store')->name('restrictions.store');

                Route::get('/programs', 'ProgramController@index')->name('programs.index');
                Route::get('/programs/fetch/{id?}', 'ProgramController@fetch')->name('programs.fetch');
                Route::post('/programs/store', 'ProgramController@store')->name('programs.store');

                Route::get('/sfas-programs', 'SfasProgramController@index')->name('sfas-programs.index');
                Route::get('/sfas-programs/fetch/{id?}', 'SfasProgramController@fetch')->name('sfas-programs.fetch');
                Route::post('/sfas-programs/store', 'SfasProgramController@store')->name('sfas-programs.store');

                Route::get('/staff', 'MaintenanceController@staffList')->name('staff.list');
                Route::get('/staff/id', 'MaintenanceController@staffShow')->name('staff.show');
                Route::post('/staff/id', 'MaintenanceController@staffEdit')->name('staff.edit');
            });
        });
});

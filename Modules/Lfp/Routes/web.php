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


Route::prefix('lfp')->group(function () {
    Route::group(
        [
            'middleware' => ['auth', 'lfp_active'],
            'as' => 'lfp.',
        ], function () {
        Route::get('/dashboard', 'LfpController@index')->name('dashboard');
        Route::get('/applications', 'LfpController@index')->name('applications.index');
//        Route::post('/applications', 'LfpController@store')->name('applications.store');
        Route::get('/applications/show/{lfp}', 'LfpController@show')->name('applications.show');
        Route::put('/applications/update/{lfp}', 'LfpController@update')->name('applications.update');
        Route::get('/applications/sync', 'LfpController@sync')->name('applications.sync');
        Route::get('/payments', 'PaymentController@index')->name('payments.index');
//        Route::post('/payments', 'PaymentController@store')->name('payments.store');
        Route::put('/payments/{payment}', 'PaymentController@update')->name('payments.update');
//        Route::post('/applications/connect-app', 'LfpController@connectApp')->name('applications.connect-app');
//        Route::post('/applications/remove-app', 'LfpController@removeApp')->name('applications.remove-app');
        Route::group(['middleware' => 'lfp_admin'], function () {
            Route::group(
                [
                    'prefix' => 'maintenance',
                    'as' => 'maintenance.',
                ], function () {

                Route::get('/staff', 'MaintenanceController@staffList')->name('staff.list');
                Route::get('/staff/{user}', 'MaintenanceController@staffShow')->name('staff.show');
                Route::post('/staff/{user}', 'MaintenanceController@staffEdit')->name('staff.edit');
                Route::get('/utils', 'MaintenanceController@utilList')->name('utils.list');
                Route::put('/utils/{util}', 'MaintenanceController@utilUpdate')->name('utils.update');
                Route::post('/utils', 'MaintenanceController@utilStore')->name('utils.store');
            });
        });
    });
});


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


use Modules\Plsc\Http\Controllers\ApplicationController;
use Modules\Plsc\Http\Controllers\StudentController;

Route::prefix('plsc')->group(function () {
    Route::group(
        [
            'middleware' => ['auth', 'plsc_active'],
            'as' => 'plsc.',
        ], function () {

        Route::resource('students', StudentController::class);
        Route::resource('applications', ApplicationController::class);
        Route::get('/application-list', [StudentController::class, 'apps'])->name('application-list');
        Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
//        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
//        Route::get('/applications/show/{plsc}', [ApplicationController::class, 'show'])->name('applications.show');
//        Route::put('/applications/update/{plsc}', [ApplicationController::class, 'update'])->name('applications.update');
//        Route::get('/applications/sync', [ApplicationController::class, 'sync'])->name('applications.sync');
//
//        Route::group(['middleware' => 'plsc_admin'], function () {
//            Route::group(
//                [
//                    'prefix' => 'maintenance',
//                    'as' => 'maintenance.',
//                ], function () {
//
//                Route::get('/staff', 'MaintenanceController@staffList')->name('staff.list');
//                Route::get('/staff/{user}', 'MaintenanceController@staffShow')->name('staff.show');
//                Route::post('/staff/{user}', 'MaintenanceController@staffEdit')->name('staff.edit');
//                Route::get('/utils', 'MaintenanceController@utilList')->name('utils.list');
//                Route::put('/utils/{util}', 'MaintenanceController@utilUpdate')->name('utils.update');
//                Route::post('/utils', 'MaintenanceController@utilStore')->name('utils.store');
//            });
//        });
    });
});

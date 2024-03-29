<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('get-logout');
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::get('/applogin', [App\Http\Controllers\UserController::class, 'appLogin'])->name('app-login');
Route::middleware(['auth'])->get('/home', [App\Http\Controllers\UserController::class, 'home'])->name('home');



Route::prefix('admin')->group(function () {
    Route::group(
        [
            'middleware' => ['auth', 'super_admin'],
            'as' => 'super-admin.',
        ], function () {

        Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'userEdit'])->name('user-edit');

        Route::get('/ministry', [App\Http\Controllers\AdminController::class, 'ministry'])->name('ministry');
        Route::put('/ministry', [App\Http\Controllers\AdminController::class, 'ministryEdit'])->name('ministry-edit');

    });

});

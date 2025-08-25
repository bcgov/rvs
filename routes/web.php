<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
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

Route::get('/logout', [UserController::class, 'logout'])->name('get-logout');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/', fn() => redirect('/login'));
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/applogin', [UserController::class, 'appLogin'])->name('app-login');
Route::middleware(['auth'])->get('/home', [UserController::class, 'home'])->name('home');
Route::post('/pdex-login', [UserController::class, 'pdexLogin'])->name('pdex-login');



Route::prefix('admin')->group(function (): void {
    Route::group(
        [
            'middleware' => ['auth', 'super_admin'],
            'as' => 'super-admin.',
        ], function (): void {

        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}', [AdminController::class, 'userEdit'])->name('user-edit');

        Route::get('/ministry', [AdminController::class, 'ministry'])->name('ministry');
        Route::put('/ministry', [AdminController::class, 'ministryEdit'])->name('ministry-edit');

    });

});

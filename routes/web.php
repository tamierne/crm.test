<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');

Route::permanentRedirect('/', 'login')->name('welcome');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified']], function() {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Route::get('{model}/{id}/restore')
    // Route::get('clients/active', [ClientController::class, 'activeIndex'])->name('clients.active');
    Route::group(['prefix' => 'projects', 'controller' => ProjectController::class], function() {
        Route::post('{project}/restore', 'restore')->name('projects.restore');
        Route::post('{project}/wipe', 'wipe')->name('projects.wipe');
    });

    Route::group(['prefix' => 'tasks', 'controller' => TaskController::class], function() {
        Route::post('{task}/restore', 'restore')->name('tasks.restore');
        Route::post('{task}/wipe', 'wipe')->name('tasks.wipe');
    });

    Route::resources([
        'clients' => ClientController::class,
        'users' => UserController::class,
        'projects' => ProjectController::class,
        'tasks' => TaskController::class,
    ], [
        'except' => ['show']
    ]);
});


require __DIR__.'/auth.php';

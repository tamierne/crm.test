<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ParserTaskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

    Route::group([
        'as' => 'admin.',
        'controller' => AdminController::class
    ], function() {
            Route::get('/', 'index')->name('index');
            Route::get('mark','mark')->name('mark');

            Route::get('activity', 'activityLogs')
                ->middleware('role:super-admin')
                ->name('activity');
    });


        Route::get('/email/verify', function () {
            return view('auth.verify-email');
        })->name('verification.notice');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::post('clients/{client}/restore', [ClientController::class, 'restore'])->name('clients.restore');

        Route::group([
            'prefix' => 'projects',
            'as' => 'projects.',
            'controller' => ProjectController::class
        ], function() {
                Route::post('{project}/restore', 'restore')->name('restore');
                Route::post('{project}/wipe', 'wipe')->name('wipe');
        });

        Route::group([
            'prefix' => 'tasks',
            'as' => 'tasks.',
            'controller' => TaskController::class
        ], function() {
                Route::post('{task}/restore', 'restore')->name('restore');
                Route::post('{task}/wipe', 'wipe')->name('wipe');
        });

        Route::group([
            'prefix' => 'users',
            'as' => 'users.',
            'controller' => UserController::class
        ], function() {
            Route::post('{user}/restore', 'restore')->name('restore');
            Route::post('{user}/wipe', 'wipe')->name('wipe');
        });

        Route::group([
            'middleware' => ['role:super-admin|admin'],
            'name' => 'roles.',
        ], function() {
            Route::post('roles/{role}/restore', [RoleController::class, 'restore'])->name('restore');
            Route::post('roles/{role}/wipe', [RoleController::class, 'wipe'])->name('wipe');
            Route::resource('roles', RoleController::class)->except('show');
        });

        Route::group([
            'prefix' => 'parsers',
            'as' => 'parsers.',
            'controller' => ParserTaskController::class
        ], function() {
            Route::group([
                'middleware' => ['role:super-admin|admin'],
            ], function() {
                Route::post('{parser}/restore', 'restore')->name('restore');
                Route::post('{parser}/wipe', 'wipe')->name('wipe');
            });
            Route::get('force', 'forceParse')->name('force');
        });

        Route::resource('parsers', ParserTaskController::class)->except(['create', 'edit', 'show']);

        Route::resources([
            'clients' => ClientController::class,
            'users' => UserController::class,
            'projects' => ProjectController::class,
            'tasks' => TaskController::class,
        ], [
            'except' => ['show']
        ]);


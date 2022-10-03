<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ParserTaskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\SocialAuth\GoogleController;
use App\Http\Controllers\Auth\SocialAuth\GithubController;
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

Route::permanentRedirect('/', 'login')->name('welcome');

Route::group(['prefix' => 'auth'], function () {
    Route::group([
        'prefix' => 'google',
        'controller' => GoogleController::class
    ], function() {
            Route::get('/', 'redirect')->name('login.google');
            Route::get('callback','callback')->name('callback.google');
    });

    Route::group([
        'prefix' => 'github',
        'controller' => GithubController::class
    ], function() {
        Route::get('/', 'redirect')->name('login.github');
        Route::get('callback','callback')->name('callback.github');
    });
});

require __DIR__.'/auth.php';

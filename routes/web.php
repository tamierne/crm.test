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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route::get('/admin', function () {
//     return view('admin.index');
// })->middleware(['auth'])->name('admin.index');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::resources([
        'clients' => ClientController::class,
        'users' => UserController::class,
        'projects' => ProjectController::class,
        'tasks' => TaskController::class,
    ]);
});


require __DIR__.'/auth.php';

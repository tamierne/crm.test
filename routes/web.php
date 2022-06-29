<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController;

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
    Route::resource('clients', ClientController::class);
    Route::resource('users', UserController::class);
    Route::resource('projects', ProjectController::class);
    // Route::resources([
    //     'album' => AlbumController::class,
    //     'user' => UserlistController::class,
    // ]);
    // // Route::group(['prefix' => 'album/{album}'], function() {
    // //     Route::resource('photo', PhotoController::class);
    // // });
    // // Route::resource('photo', PhotoController::class);
    // Route::post('album/{album}/upload', [PhotoController::class, 'store'])->name('photo.upload');
    // Route::delete('album.photo', [PhotoController::class, 'remove'])->name('photo.destroy');
});


require __DIR__.'/auth.php';

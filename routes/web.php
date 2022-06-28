<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

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
});

// Route::get('/admin', function () {
//     return view('admin.index');
// })->middleware(['auth'])->name('admin.index');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('/', [UserController::class, 'index'])->name('admin.index');
    // Route::get('logout', [AdminController::class, 'logout'])->name('logout');
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

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/user', function(Request $request) {
        return $request->user();
    });

    Route::apiResources([
        'clients' => ClientController::class,
        'projects' => ProjectController::class,
    ], [
        'only' => ['index', 'show'],
    ]);

    Route::post('/logout', [AuthController::class, 'logout']);
});



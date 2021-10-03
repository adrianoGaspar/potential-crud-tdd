<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeveloperController;
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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // GET /developers
    // Codes 200
    // GET /developers/{id}
    // Codes 200 / 404
    Route::get('/developers/{id?}', [DeveloperController::class, 'get']);

    // POST /developers
    // Codes 201 / 400
    // Adiciona um novo desenvolvedor
    Route::post('/developers', [DeveloperController::class, 'store']);

    // PUT /developers/{id}
    // Codes 200 / 400
    // Atualiza os dados de um desenvolvedor
    Route::put('/developers/{id}', [DeveloperController::class, 'update']);

    // DELETE /developers/{id}
    // Codes 204 / 400
    Route::delete('/developers/{id}', [DeveloperController::class, 'destroy']);

});

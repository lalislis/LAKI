<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\PresencesController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/profiles', [ProfilesController::class, 'index']);
Route::get('/profiles/{user:id}', [ProfilesController::class, 'show']);
Route::post('/profiles/update/{user:id}', [ProfilesController::class, 'update']);
Route::post('/profiles/updatefoto/{user:id}', [ProfilesController::class, 'updateFoto']);

Route::post('/presence/{user:id}', [PresencesController::class, 'store']);

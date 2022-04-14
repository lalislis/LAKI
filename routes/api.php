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
Route::get('/profiles/{profile:id}', [ProfilesController::class, 'show']);
Route::post('/profiles/update/{profile:id}', [ProfilesController::class, 'update']);

Route::post('/presence/{user:id}', [PresencesController::class, 'store']);

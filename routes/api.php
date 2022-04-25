<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\PresencesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

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
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login')->name('login');

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/profiles', 'ProfilesController@index');
    Route::get('/profiles/{user:id}', 'ProfilesController@show');
    Route::post('/profiles/update/{user:id}', 'ProfilesController@update');
    Route::post('/profiles/updatefoto/{user:id}', 'ProfilesController@updateFoto');
    Route::post('/presence/{user:id}', 'PresencesController@clockIn');
    Route::post('/logout', 'AuthController@logout');
    Route::apiResource('/tasks', 'TaskController');
    Route::get('/adminsuperuser', 'AdminController@showSuperUser');
    Route::post('/adminsuperuser/register', 'AdminController@registerSuperUser');
    Route::get('/admincompany', 'AdminController@showCompanies');
    Route::post('/admincompany/register', 'AdminController@registerCompany');
    Route::get('/karyawan', 'KaryawanController@index');
    Route::get('/superuser', 'SuperUserController@index');
    Route::get('/superuser/user/{user:id}', 'SuperUserController@showKaryawan');
    Route::get('/superuser/task/{user:id}', 'SuperUserController@showTask');
    Route::delete('/superuser/user/{user:id}', 'SuperUserController@deleteKaryawan');
    Route::post('/superuser', 'SuperUserController@createKaryawan');
});

Route::get('/dashboard/{user:id}', 'ProfilesController@dashboard');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
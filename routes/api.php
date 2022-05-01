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
Route::group(['prefix' => 'auth'], function(){
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login')->name('login');
    Route::post('/logout', 'AuthController@logout')->name('logout');
});

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/profiles', 'ProfilesController@index');
    Route::get('/profiles', 'ProfilesController@show');
    Route::put('/profiles/edit-password', 'ProfilesController@editPassword');
    Route::post('/profiles/edit-profile', 'ProfilesController@update');
    Route::post('/profiles/updatefoto/{user:id}', 'ProfilesController@updateFoto');
    Route::post('/presence/{user:id}', 'PresencesController@clockIn');
    Route::post('/logout', 'AuthController@logout');
    Route::get('/tasks', 'TaskController@index');
    Route::put('/tasks', 'TaskController@update');
    Route::post('/admin/superusers', 'AdminController@registerSuperUser');
    Route::get('/admin/superusers', 'AdminController@getSuperUser');
    Route::delete('/admin/superusers/{user:id}', 'AdminController@deleteSuperUser');
    Route::get('/admin/list-company', 'AdminController@listCompanies');
    Route::post('/admin/companies', 'AdminController@registerCompany');
    Route::get('/admin/companies', 'AdminController@getCompanies');
    Route::get('/karyawan', 'KaryawanController@index');
    Route::get('/superuser', 'SuperUserController@index');
    Route::get('/superuser/user/{user:id}', 'SuperUserController@showKaryawan');
    Route::get('/superuser/task/{user:id}', 'SuperUserController@showTask');
    Route::delete('/superuser/user/{user:id}', 'SuperUserController@deleteKaryawan');
    Route::post('/superuser', 'SuperUserController@createKaryawan');
    Route::get('/dashboard/user/{user:id}', 'DashboardController@index');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::post('/forgot-password', 'AuthController@forgotPassword');
    Route::post('/reset-password', 'AuthController@reset');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profiles', 'ProfilesController@index');
    Route::get('/profiles', 'ProfilesController@show');
    Route::post('/profiles/edit-password', 'ProfilesController@editPassword');
    Route::post('/profiles/edit-profile', 'ProfilesController@update');
    // Route::post('/profiles/edit-photo', 'ProfilesController@updateFoto');
    Route::post('/presences/clock-in', 'PresencesController@clockIn');
    Route::post('/presences/clock-out', 'PresencesController@clockOut');
    Route::post('/auth/logout', 'AuthController@logout');
    Route::get('/tasks', 'TaskController@index');
    Route::put('/tasks', 'TaskController@update');
    Route::post('/admin/superusers', 'AdminController@registerSuperUser');
    Route::get('/admin/superusers', 'AdminController@getSuperUser');
    Route::delete('/admin/superusers/{user:id}', 'AdminController@deleteSuperUser');
    Route::get('/admin/list-company', 'AdminController@listCompanies');
    Route::post('/admin/companies', 'AdminController@registerCompany');
    Route::get('/admin/companies', 'AdminController@getCompanies');
    Route::get('/employees', 'KaryawanController@index');
    Route::group(['prefix' => 'superuser'], function () {
        Route::get('/user-profiles', 'SuperUserController@index');
        Route::get('/info-company', 'SuperUserController@showCompany');
        Route::get('/users', 'SuperUserController@allEmployeeAccounts');
        Route::delete('/users/{user:id}', 'SuperUserController@deleteKaryawan');
        Route::post('/users', 'SuperUserController@createKaryawan');
    });
    Route::get('/dashboard/user', 'DashboardController@index');
    Route::get('/dashboard/clock-today', 'DashboardController@clockToday');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

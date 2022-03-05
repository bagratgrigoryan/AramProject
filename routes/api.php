<?php


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

Route::post('/user', 'UserController@CreateUser');
Route::post('/user/login', 'UserController@Login');
Route::get('/user/all', 'UserController@Index');
Route::get('/user/auth', 'UserController@GetUser');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/user/auth', 'UserController@GetUser');

});

//Route::middleware('auth:api')->get('/user/auth', 'UserController@GetUser');
//Route::middleware('auth:api')->put('/user/update', 'UserController@UpdateUser');
//Route::middleware('auth:api')->post('/user/logout', 'UserController@LogOut');
//Route::middleware('auth:api')->delete('/user/delete', 'UserController@Delete');

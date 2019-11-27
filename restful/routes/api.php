<?php

use Illuminate\Http\Request;

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

Route::post('/auth/login', 'AuthController@login');
Route::get('/auth/credential', 'AuthController@credential');

Route::middleware('auth:api')->group(function() 
{
	Route::get('/user/import', 'UserController@import');
	Route::apiResource('user', 'UserController');
	Route::apiResource('task', 'TaskController');
	
	Route::post('/auth/logout', 'AuthController@logout');

});


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
Route::prefix('auth')->group(function (){
    Route::post('/login', 'Api\AuthController@login')->name('login');
    Route::post('/register', 'Api\AuthController@register')->name('register');
    Route::post('/logout', 'Api\AuthController@logout')->name('logout');
});

Route::middleware(['auth:api'])->group(function () {
    Route::resource('product','Api\ProductController')->only(
        'index','store','update','destroy','show'
    );
    Route::get('/import/user', 'Api\ImportController@importUser')->name('import.user');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


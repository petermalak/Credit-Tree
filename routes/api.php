<?php

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

Route::post('/login', 'LoginController@login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', 'LoginController@logout');

    Route::group(['prefix' => 'users'], function () {
        Route::put('/credit/transfer', 'TreeController@transferCredit');
        Route::get('/credit/logs', 'UserController@getCreditLogs');
        Route::get('/credit', 'UserController@getCredit');
        Route::put('/update/{field}', 'UserController@updateProfile');
    });

    Route::group(['prefix' => 'tree'], function () {
        Route::post('/add', 'TreeController@addToTree');
        Route::get('/{user_id}/childes', 'TreeController@getChildes');
    });

    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
        Route::put('/credit/{user_id}/add', 'AdminController@addCredit');
        Route::put('/credit/{user_id}/remove', 'AdminController@removeCredit');
        Route::get('/getUsers', 'AdminController@getAllUsers');
    });
});

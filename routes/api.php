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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/apiAuth', 'Auth\LoginController@apiAuth')->name('api.auth');
Route::middleware('auth:api')->get('/getDayData', 'HomeController@getDayData')->name('api.day.data.get');
Route::middleware('auth:api')->post('/saveDayData', 'HomeController@saveDayData')->name('api.day.data.save');
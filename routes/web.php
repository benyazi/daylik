<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->get('/home', 'HomeController@index')->name('home');
Route::middleware('auth')->get('/getDayData', 'HomeController@getDayData')->name('day.data.get');
Route::middleware('auth')->post('/saveDayData', 'HomeController@saveDayData')->name('day.data.save');

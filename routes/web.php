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

Route::get('/', 'HotelsController@index')->name('index-page');

Route::get('/hotel/{slug}/details', 'HotelsController@getHotelDetails')->name('detail-page');

Route::get('{slug}/getbydate', 'HotelsController@getHotelDetailsByDate')->name('get-detail-date');

Route::post('{slug}/store-details', 'HotelsController@store')->name('store-details');

Route::post('update', 'HotelsController@update')->name('update-details');

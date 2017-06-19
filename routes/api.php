<?php

Route::post('hotel', 'ApiController@createHotel');
Route::get('hotel/list', 'ApiController@getHotelsList');

Route::post('hotel/room-type', 'ApiController@createHotelType');
Route::get('hotel/room-type', 'ApiController@getHotelRoomTypes');
Route::get('hotel/room-type/{slug}', 'ApiController@getHotelRoomTypes');

Route::get('hotel/detail/{slug}/{date}', 'ApiController@getHotelWithRoomsAndPrice');

Route::post('hotel/detail/{slug}', 'ApiController@addHotelRoomsPriceAndType');

Route::patch('hotel/detail/{slug}', 'ApiController@addHotelRoomsPriceAndType');

//NOTE: RND to move ahead. Need to find way to bind the route with 
//the controller name 
// Route::get('hotel/detail/{slug}/{date}', function ($slug, $date) {})
// 	->where(['slug' => '[a-z]+', 'date' => '[0-9]+']);
<?php

Route::get('/', 'HomeController@index')->name('Home');
Route::post('/zipcodes', 'SearchController@zipcode')->name('EnterZip');
Route::post('/service', 'SearchController@serviceid')->name('ServiceID');
Route::post('/search', 'SearchController@search')->name('Search');
Route::post('/channel', 'SearchController@channel')->name('Channel');

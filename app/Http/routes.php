<?php

Route::get('/', [
	'as' => 'index',
	'uses' => 'SiteController@graph'
]);

Route::get('get-data', [
	'as' => 'data',
	'uses' => 'SiteController@data'
]);

Route::get('welcome', function() {
	return view('welcome');
});

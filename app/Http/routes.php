<?php

Route::get('/', [
	'as' => 'index',
	'uses' => 'SiteController@graph'
]);

Route::get('welcome', function() {
	return view('welcome');
});

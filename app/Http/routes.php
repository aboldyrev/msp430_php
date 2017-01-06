<?php

Route::get('/', function () {
	$period = [
			\Carbon\Carbon::now()->subDay(),
			\Carbon\Carbon::now()
	];

	$temperatures = \App\Models\Temperature::whereBetween('created_at', $period)->get();
	$lights = \App\Models\Light::whereBetween('created_at', $period)->get();

    return view('index', compact('temperatures', 'lights'));
});

Route::get('welcome', function () {
    return view('welcome');
});

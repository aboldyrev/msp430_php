<?php

Route::get('/', function() {
	$period = [
		\Carbon\Carbon::now()->subDay(),
		\Carbon\Carbon::now()
	];

	$temperature_models = \App\Models\Temperature::whereBetween('created_at', $period)->get();
	$light_models = \App\Models\Light::whereBetween('created_at', $period)->get();

	$temperatures = [];
	$lights = [];
	$timestamps = [];

	foreach ($temperature_models as $key => $temperature_model) {
		// Сбор временных меток
		$timestamp = \Carbon\Carbon::parse($temperature_model->created_at);
		$timestamps[] = '"' .
			($timestamp->hour < 10 ? '0' . $timestamp->hour : $timestamp->hour) .
			':' .
			($timestamp->minute < 10 ? '0' . $timestamp->minute : $timestamp->minute) . '"';

		// Сбор температуры
		$temperatures[] = '"' . $temperature_model->value . '"';

		// Сбор освещённости
		$lights[] = '"' . $light_models[$key]->value . '"';
	}

	return view('index', compact('temperatures', 'lights', 'timestamps'));
});

Route::get('welcome', function() {
	return view('welcome');
});

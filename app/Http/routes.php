<?php

Route::get('/{hours?}', [
	'as' => 'index',
	function() {
		$hours = request()->hours;
		if (!isset($hours) || $hours == 12) {
			$hours = 12;
		} elseif (( $hours != 24 )) {
			$hours = 24;
		}
		$period = [
			\Carbon\Carbon::now()->subHours($hours),
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
				( $timestamp->hour < 10 ? '0' . $timestamp->hour : $timestamp->hour ) .
				':' .
				( $timestamp->minute < 10 ? '0' . $timestamp->minute : $timestamp->minute ) . '"';

			// Сбор температуры
			$temperatures[] = '"' . $temperature_model->value . '"';

			// Сбор освещённости
			$lights[] = '"' . $light_models[ $key ]->value . '"';
		}

		return view('index', compact('temperatures', 'lights', 'timestamps', 'hours'));
	}]
);

Route::get('welcome', function() {
	return view('welcome');
});

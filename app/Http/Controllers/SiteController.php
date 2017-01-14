<?php

namespace App\Http\Controllers;

use App\Models\Light;
use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SiteController extends Controller
{
	public function graph(Request $request) {
		$hours = $request->hours;

		if (isset($request->day)){
			$day = $request->day;
		} else {
			$day = 0;
		}

		if (!isset($hours) || $hours == 12) {
			$hours = 12;
		} elseif (( $hours != 24 )) {
			$hours = 24;
		}

		$period = [
			Carbon::now()->subDays($day)->subHours($hours),
			Carbon::now()->subDays($day)
		];

		$temperature_models = Temperature::whereBetween('created_at', $period)->get();
		$light_models = Light::whereBetween('created_at', $period)->get();

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
	}
}

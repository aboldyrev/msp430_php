<?php

namespace App\Http\Controllers;

use App\Models\Light;
use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SiteController extends Controller
{
	public function graph() {
		return view('index');
	}


 	public function data(Request $request) {
//		$hours = $request->hours;
//
//		if (isset($request->day)){
//			$day = $request->day;
//		} else {
//			$day = 0;
//		}
//
//		if (!isset($hours) || $hours == 12) {
//			$hours = 12;
//		} elseif (( $hours != 24 )) {
//			$hours = 24;
//		}

	    $hours = 4;
	    $day = 0;

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
			$timestamps[] = \Carbon\Carbon::parse($temperature_model->created_at)->toDateTimeString();

			// Сбор температуры
			$temperatures[] = $temperature_model->value;

			// Сбор освещённости
			$lights[] = $light_models[ $key ]->value;
		}
		return response()->json(compact('temperatures', 'lights', 'timestamps'));
	}
}

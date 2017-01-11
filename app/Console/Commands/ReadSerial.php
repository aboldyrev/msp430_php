<?php

namespace App\Console\Commands;

use App\Models\Light;
use Illuminate\Console\Command;
use App\Models\Temperature;

class ReadSerial extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'read:serial';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		$device_name = '';

		// имя девайса
		$devices = glob('/dev/ttyACM*');

		if (count($devices) == 0){
			$this->error('Device not found');
			exit;
		} elseif (count($devices) == 1) {
			$device_name = array_first($devices);
		} elseif (count($devices) > 1) {
			$this->warn('Found more than one device');
			$device_name = $this->choice('Select your device', $devices);
		}

		// настройка
		exec('stty -F ' . $device_name . ' 9600');

		$device = fopen($device_name, "r+b");
		fwrite($device, "t");
		$temperature = (float)trim(fgets($device));
		fwrite($device, "l");
		$light = (float)trim(fgets($device));
		fclose($device);

		Temperature::create(['value' => $temperature]);
		Light::create(['value'=>$light]);
	}
}

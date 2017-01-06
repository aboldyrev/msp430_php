<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
		// имя девайса
		$device_name = "/dev/ttyACM1";

		// настройка
		exec('stty -F ' . $device_name . ' 9600 raw');

		$device = fopen($device_name, "r+b");
		fwrite($device, "t");
		$temperature = (float)trim(fgets($device));
		fwrite($device, "l");
		$light = (float)trim(fgets($device));
		fclose($device);

		$this->line('Temperature: ' . $temperature);
		$this->line('Light: ' . $light);
	}
}

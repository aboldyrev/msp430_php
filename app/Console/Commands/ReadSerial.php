<?php

namespace App\Console\Commands;

use App\Models\Light;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Temperature;
use Illuminate\Support\Facades\Mail;

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


	protected function logging($content, $value = NULL) {
		if (is_array($value)) {
			$context = 'Уровень света: ' . $value[ 'light' ] . '; Температура: ' . $value[ 'temp' ] . '°C';
		} elseif (is_string($value)) {
			$context = $value;
		} else {
			$context = false;
		}

		if ($context) {
			\Log::info($content, [ 'data' => $context ]);
		} else {
			\Log::info($content);
		}


		foreach (config('contacts.mails') as $mail) {
			Mail::send(
				'emails.simple',
				[ 'content' => $content, 'context' => $context ],
				function($message) use ($content, $mail) {
					$subject = $content . ' - ' . Carbon::now()->format('H:i');
					$message
						->from(
							config('mail.from')['address'],
							isset($mail['from']) ? $mail['from'] : config('mail.from')['name']
						)
						->to($mail['address'], $mail['name'])
						->subject($subject);
				}
			);
		}
	}

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$device_name = '';

		// имя девайса
		$devices = glob('/dev/ttyACM*');

		if (count($devices) == 0) {
			$this->error('Device not found');
			exit;
		} elseif (count($devices) == 1) {
			$device_name = array_first($devices);
		} elseif (count($devices) > 1) {
			$this->warn('Found more than one device');
			$device_name = $this->choice('Select your device', $devices);
		}

		$params = [
			'', 'cs8', '115200',
			'ignbrk', '-brkint', '-icrnl',
			'-imaxbel', '-opost', '-onlcr',
			'-isig', '-icanon', '-iexten',
			'-echo', '-echoe', '-echok',
			'-echoctl', '-echoke', 'noflsh',
			'-ixon', '-crtscts'
		];

		// настройка
		exec('stty -F ' . $device_name . implode(' ', $params));

		$device = fopen($device_name, "r+b");
		fwrite($device, "t");
		$temperature = (float)trim(fgets($device));
		fwrite($device, "l");
		$light = (float)trim(fgets($device));
		fclose($device);

		$last_value_light = Light::orderBy('created_at', 'desc')->first()->value;

		$diff_light = $light - $last_value_light;

		$values = [
			'light' => $light,
			'temp'  => $temperature
		];

		if ($light > 0){
			if (abs($diff_light) >= 300) {
				// Общий свет
				if ($diff_light > 0) {
					$this->logging('Общий свет включен', $values);
				} else {
					$this->logging('Общий свет выключен', $values);
				}
			}

			Temperature::create([ 'value' => $temperature ]);
			Light::create([ 'value' => $light ]);
		}

	}
}

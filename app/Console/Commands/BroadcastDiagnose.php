<?php

namespace App\Console\Commands;

use App\Events\NotificationCreated;
use App\Events\TestBroadcastEvent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class BroadcastDiagnose extends Command
{
	/**
	 * The name and signature of the console command.
	 */
	protected $signature = 'broadcast:diagnose {--user_id=}';

	/**
	 * The console command description.
	 */
	protected $description = 'Diagnose broadcasting: config, socket reachability, and live event dispatch';

	public function handle()
	{
		$this->info('Broadcast diagnose starting...');

		$driver = config('broadcasting.default');
		$this->line('Broadcast driver: ' . $driver);

		$host = config('reverb.host', '127.0.0.1');
		$port = (int) config('reverb.port', 8080);
		$this->line('Reverb host:port => ' . $host . ':' . $port);

		$this->line('Testing TCP reachability...');
		$socket = @fsockopen($host, $port, $errno, $errstr, 3);
		if (! $socket) {
			$this->error("Cannot connect to Reverb at $host:$port - $errstr ($errno)");
		} else {
			$this->info('Connected to Reverb successfully.');
			fclose($socket);
		}

		$this->line('Dispatching public test event...');
		event(new TestBroadcastEvent('diagnostic-ping'));
		$this->info('Public event dispatched on channel test-channel as test.event');

		$userId = $this->option('user_id');
		if ($userId) {
			$user = User::find((int) $userId);
			if ($user) {
				$notification = Notification::create([
					'user_id' => $user->id,
					'type' => 'diagnose',
					'notifiable_type' => User::class,
					'notifiable_id' => $user->id,
					'data' => [
						'user_id' => $user->id,
						'message' => 'diagnostic-notification',
					],
				]);
				NotificationCreated::dispatch($notification);
				$this->info('Private event dispatched on channel user.' . $user->id . ' as notification.created');
			} else {
				$this->error('User not found for user_id=' . $userId);
			}
		} else {
			$this->line('Skip private event (no --user_id given).');
		}

		$this->info('Broadcast diagnose finished.');
		return 0;
	}
}



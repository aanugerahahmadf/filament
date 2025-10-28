<?php

namespace App\Console\Commands;

use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class SendInstantNotification extends Command
{
	/**
	 * The name and signature of the console command.
	 */
	protected $signature = 'notifications:instant {user_id?} {--all} {--type=info} {--message=Hello}';

	/**
	 * The console command description.
	 */
	protected $description = 'Send a real-time notification instantly (no queue, no delay)';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$type = (string) $this->option('type');
		$message = (string) $this->option('message');
		$toAll = (bool) $this->option('all');

		if ($toAll) {
			$this->info('Sending instant notification to all users...');
			$sent = 0;
			User::query()->select(['id'])->chunkById(500, function ($users) use ($type, $message, &$sent) {
				foreach ($users as $user) {
					$notification = Notification::create([
						'user_id' => $user->id,
						'type' => $type,
						'notifiable_type' => User::class,
						'notifiable_id' => $user->id,
						'data' => [
							'user_id' => $user->id,
							'message' => $message,
						],
					]);
					NotificationCreated::dispatch($notification);
					$sent++;
				}
			});
			$this->info('Broadcasted to users: ' . $sent);
			return 0;
		}

		$userId = $this->argument('user_id');
		if (! $userId) {
			$this->error('Please provide {user_id} or use --all.');
			return 1;
		}

		$user = User::find((int) $userId);
		if (! $user) {
			$this->error('User not found.');
			return 1;
		}

		$notification = Notification::create([
			'user_id' => $user->id,
			'type' => $type,
			'notifiable_type' => User::class,
			'notifiable_id' => $user->id,
			'data' => [
				'user_id' => $user->id,
				'message' => $message,
			],
		]);

		NotificationCreated::dispatch($notification);

		$this->info('Instant notification sent and broadcasted. ID: ' . $notification->id);
		return 0;
	}
}



<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Notification;
use App\Services\NotificationService;
use App\Services\SettingsService;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get a user to test with
$user = User::first();
if (!$user) {
    echo "No users found in database\n";
    exit(1);
}

echo "Testing notification system with user: {$user->name} (ID: {$user->id})\n";

// Create a notification service instance
$settingsService = new SettingsService();
$notificationService = new NotificationService($settingsService);

// Test sending a message notification
$sender = User::orderBy('id', 'desc')->first();
if ($sender && $sender->id != $user->id) {
    echo "Sending message notification from {$sender->name} to {$user->name}\n";
    $notificationService->sendMessageNotification($user, $sender, "Test message notification");
    echo "Message notification sent successfully\n";
} else {
    echo "Could not find a different user to send message from\n";
}

// Test sending a generic notification
echo "Sending generic notification to {$user->name}\n";
$notificationService->sendUserNotification($user, 'test', 'Test generic notification');
echo "Generic notification sent successfully\n";

echo "Notifications created successfully!\n";
echo "You can now test the notification system in the browser\n";

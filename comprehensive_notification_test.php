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

echo "=== Comprehensive Notification System Test ===\n\n";

// Get users to test with
$users = User::take(2)->get();
if ($users->count() < 2) {
    echo "Need at least 2 users for testing\n";
    exit(1);
}

$user1 = $users[0];
$user2 = $users[1];

echo "Testing with users:\n";
echo "User 1: {$user1->name} (ID: {$user1->id})\n";
echo "User 2: {$user2->name} (ID: {$user2->id})\n\n";

// Create notification service
$settingsService = new SettingsService();
$notificationService = new NotificationService($settingsService);

// Test 1: Send message notification
echo "Test 1: Sending message notification...\n";
$notificationService->sendMessageNotification($user1, $user2, "Test message from User 2 to User 1");
echo "Message notification sent successfully\n\n";

// Test 2: Send user notification
echo "Test 2: Sending user notification...\n";
$notificationService->sendUserNotification($user1, 'test', 'Test user notification');
echo "User notification sent successfully\n\n";

// Test 3: Verify notifications were created
echo "Test 3: Verifying notifications...\n";
$notifications = Notification::where('user_id', $user1->id)
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

echo "Found {$notifications->count()} notifications for User 1:\n";
foreach ($notifications as $notification) {
    echo "  - ID: {$notification->id}\n";
    echo "    Type: {$notification->type}\n";
    echo "    Read: " . ($notification->read_at ? 'Yes' : 'No') . "\n";
    echo "    Data: " . json_encode($notification->data) . "\n";
    echo "    Created: {$notification->created_at}\n\n";
}

// Test 4: Test marking as read/unread
echo "Test 4: Testing read/unread functionality...\n";
$unreadNotification = $notifications->firstWhere('read_at', null);
if ($unreadNotification) {
    echo "Marking notification {$unreadNotification->id} as read...\n";
    $unreadNotification->markAsRead();
    echo "Notification marked as read\n";

    echo "Marking notification {$unreadNotification->id} as unread...\n";
    $unreadNotification->markAsUnread();
    echo "Notification marked as unread\n";
} else {
    echo "No unread notifications found to test\n";
}

echo "\n=== All tests completed successfully! ===\n";

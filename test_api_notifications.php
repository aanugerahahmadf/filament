<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Notification;

echo "=== TESTING NOTIFICATIONS API ===\n";

// Test 1: Check if user exists
$user = User::find(1);
if ($user) {
    echo "✓ User found: {$user->name} (ID: {$user->id})\n";
} else {
    echo "✗ User not found\n";
    exit;
}

// Test 2: Check notifications in database
$notifications = Notification::where('user_id', 1)->get();
echo "✓ Notifications in database: " . $notifications->count() . "\n";

foreach ($notifications as $n) {
    echo "  - ID: {$n->id}, Type: {$n->type}, Read: " . ($n->read_at ? 'Yes' : 'No') . "\n";
}

// Test 3: Test API controller directly
$controller = new \App\Http\Controllers\Api\NotificationApiController();
$request = new \Illuminate\Http\Request();
$request->setUserResolver(function () use ($user) {
    return $user;
});

try {
    $response = $controller->index($request);
    $data = $response->getData(true);
    
    echo "✓ API Response Success: " . ($data['success'] ? 'Yes' : 'No') . "\n";
    echo "✓ API Items Count: " . count($data['items']) . "\n";
    
    if (count($data['items']) > 0) {
        echo "✓ First notification:\n";
        $first = $data['items'][0];
        echo "  - Type: {$first['type']}\n";
        echo "  - Message: {$first['message']}\n";
        echo "  - Read: " . ($first['read_at'] ? 'Yes' : 'No') . "\n";
    }
} catch (Exception $e) {
    echo "✗ API Error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";

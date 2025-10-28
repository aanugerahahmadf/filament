<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Notification;

echo "=== TESTING NOTIFICATIONS PAGE ===\n";

// Test 1: Check if user exists and is authenticated
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

// Test 3: Test if we can render the notifications page
try {
    // Simulate authenticated user
    auth()->login($user);
    
    // Test API endpoint
    $controller = new \App\Http\Controllers\Api\NotificationApiController();
    $request = new \Illuminate\Http\Request();
    $request->setUserResolver(function () use ($user) {
        return $user;
    });
    
    $response = $controller->index($request);
    $data = $response->getData(true);
    
    echo "✓ API Response Success: " . ($data['success'] ? 'Yes' : 'No') . "\n";
    echo "✓ API Items Count: " . count($data['items']) . "\n";
    
    if (count($data['items']) > 0) {
        echo "✓ Sample notifications:\n";
        foreach (array_slice($data['items'], 0, 3) as $i => $n) {
            echo "  " . ($i + 1) . ". Type: {$n['type']}, Message: {$n['message']}, Read: " . ($n['read_at'] ? 'Yes' : 'No') . "\n";
        }
    }
    
    // Test if notifications page can be rendered
    $view = view('notifications');
    $html = $view->render();
    
    if (strpos($html, 'id="list"') !== false) {
        echo "✓ Notifications page contains list container\n";
    } else {
        echo "✗ Notifications page missing list container\n";
    }
    
    if (strpos($html, 'load()') !== false) {
        echo "✓ Notifications page contains load() function\n";
    } else {
        echo "✗ Notifications page missing load() function\n";
    }
    
    if (strpos($html, 'AppUserId') !== false) {
        echo "✓ Notifications page contains AppUserId\n";
    } else {
        echo "✗ Notifications page missing AppUserId\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error testing notifications page: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";

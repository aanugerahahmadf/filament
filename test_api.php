<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Get the first user
$user = App\Models\User::first();

if ($user) {
    echo "Testing notifications API for user ID: " . $user->id . "\n";

    // Create a test request to the notifications API
    $request = Request::create('/api/notifications', 'GET');

    // Set the user on the request
    $request->setUserResolver(function () use ($user) {
        return $user;
    });

    // Set the request as having API guard
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');

    // Manually authenticate the user for web guard
    Auth::login($user);
    Auth::shouldUse('web');

    // Handle the request through the router
    $response = app()->handle($request);

    echo "Response status: " . $response->getStatusCode() . "\n";

    // Parse the JSON response
    $data = json_decode($response->getContent(), true);

    if ($data && isset($data['items'])) {
        echo "Found " . count($data['items']) . " notifications\n";

        // Look for our test notification
        $testNotification = null;
        foreach ($data['items'] as $notification) {
            if (isset($notification['data']['message']) &&
                strpos($notification['data']['message'], 'Test real-time message notification') !== false) {
                $testNotification = $notification;
                break;
            }
        }

        if ($testNotification) {
            echo "SUCCESS: Test notification found!\n";
            echo "Notification ID: " . $testNotification['id'] . "\n";
            echo "Notification type: " . $testNotification['type'] . "\n";
            echo "Notification message: " . $testNotification['data']['message'] . "\n";
        } else {
            echo "INFO: Test notification not found in the list. This might be because there are many notifications.\n";
            // Show the last few notifications
            $recentNotifications = array_slice($data['items'], 0, 3);
            foreach ($recentNotifications as $notification) {
                echo "Recent notification - Type: " . $notification['type'] . ", Message: " . ($notification['data']['message'] ?? 'N/A') . "\n";
            }
        }
    } else {
        echo "ERROR: Could not parse notifications data\n";
        echo "Response content: " . $response->getContent() . "\n";
    }
} else {
    echo "No user found\n";
}

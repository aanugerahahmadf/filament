<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CctvStreamController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;

use Livewire\Volt\Volt;

// Public routes
Route::get('/', function () { return view('welcome'); })->name('home');

// Authentication required routes
Route::middleware(['auth'])->group(function () {
    // Main pages
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['verified'])->name('dashboard');
    // Render maps page (Blade view)
    Route::get('/maps', [MapController::class, 'index'])->name('maps');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // Notification API routes (AJAX endpoints)
    // Use a completely different path to avoid any conflicts
    Route::get('/user-notifications', [NotificationController::class, 'getNotifications'])->name('notifications.data');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread'])->name('notifications.unread');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Location, Room and CCTV routes
    Route::get('/locations', [LocationController::class, 'index'])->name('locations');
    Route::get('/rooms', [LocationController::class, 'rooms'])->name('rooms');
    Route::get('/cctv-list', [LocationController::class, 'cctvList'])->name('cctv.list');
    Route::get('/cctv/stream/{cctv}', [CctvStreamController::class, 'show'])->name('cctv-live-stream');

    // Messages routes
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/conversation/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages/typing', [MessageController::class, 'typing'])->name('messages.typing');

    // Search route
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // Map data endpoints (consumed by UI)
    Route::get('/map-data', [MapController::class, 'data'])->name('maps.data');
    Route::get('/location-data', [MapController::class, 'locationData'])->name('locations.data');
});

// Settings routes
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Volt::route('settings/otp', 'settings/otp.index')->name('settings.otp');
});

// Export routes
Route::middleware(['auth'])->group(function () {
    Route::get('/export/stats', [ExportController::class, 'stats'])->name('export.stats');
    Route::get('/export/buildings', [ExportController::class, 'buildings'])->name('export.buildings');
    Route::get('/export/rooms', [ExportController::class, 'rooms'])->name('export.rooms');
    Route::get('/export/cctvs', [ExportController::class, 'cctvs'])->name('export.cctvs');
    Route::get('/export/users', [ExportController::class, 'users'])->name('export.users');
    Route::get('/export/contacts', [ExportController::class, 'contacts'])->name('export.contacts');
});

// Authentication routes
require __DIR__.'/auth.php';

// Test routes
Route::get('/test-whatsapp-otp', function () { return view('livewire.test-whatsapp-otp'); })->name('test.whatsapp.otp');
Route::get('/test-email-otp', function () { return view('livewire.email-otp-verify'); })->name('test.email.otp');

Route::middleware(['auth'])->group(function () {
    // Authenticated test routes
    Route::get('/test-notifications', function () {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'user' => Auth::user()->name,
            'notifications' => $notifications->map(function($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'message' => $n->data['message'] ?? 'No message',
                    'read' => $n->read_at ? true : false,
                    'created_at' => $n->created_at->toISOString()
                ];
            })
        ]);
    })->name('test.notifications');

    Route::get('/test-users', function() {
        $users = \App\Models\User::where('id', '!=', Auth::id())->orderBy('name')->get();
        return response()->json([
            'current_user' => Auth::user()->name,
            'users' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'initials' => $user->initials()
                ];
            })
        ]);
    })->name('test.users');

    Route::get('/test-db', function() {
        try {
            $users = User::limit(5)->get();
            return response()->json([
                'status' => 'success',
                'users' => $users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'initials' => $user->initials()
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    })->name('test.db');
});

// Test route to simulate authentication error (not protected by auth middleware)
Route::get('/test-auth-error', function () {
    return response()->json([
        'success' => false,
        'message' => 'Unauthenticated'
    ], 401);
})->name('test.auth.error');

// Pusher Test Routes
Route::get('/pusher-test', function () {
    return view('pusher-test');
})->name('pusher.test');

Route::get('/pusher-send-test', function () {
    // Get Pusher configuration
    $pusherAppId = env('PUSHER_APP_ID', '2069100');
    $pusherAppKey = env('PUSHER_APP_KEY', '238e99fba712c4216292');
    $pusherAppSecret = env('PUSHER_APP_SECRET', 'a88169403b750fe6359d');
    $pusherAppCluster = env('PUSHER_APP_CLUSTER', 'ap1');

    // Initialize Pusher
    $pusher = new Pusher\Pusher(
        $pusherAppKey,
        $pusherAppSecret,
        $pusherAppId,
        [
            'cluster' => $pusherAppCluster,
            'useTLS' => false,
            'encrypted' => false,
            'curl_options' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]
        ]
    );

    // Prepare data
    $data = [
        'message' => 'Hello world from ATCS web interface',
        'timestamp' => now()->toDateTimeString(),
        'source' => 'ATCS Web Interface'
    ];

    try {
        // Trigger the event
        $result = $pusher->trigger('my-channel', 'my-event', $data);

        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Event sent successfully!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to send event.']);
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
})->name('pusher.send.test');

Route::get('/pusher-send-cctv-test', function () {
    // Get Pusher configuration
    $pusherAppId = env('PUSHER_APP_ID', '2069100');
    $pusherAppKey = env('PUSHER_APP_KEY', '238e99fba712c4216292');
    $pusherAppSecret = env('PUSHER_APP_SECRET', 'a88169403b750fe6359d');
    $pusherAppCluster = env('PUSHER_APP_CLUSTER', 'ap1');

    // CCTV data
    $cctvNames = ['Main Entrance', 'Control Room', 'Tank Area', 'Loading Dock', 'Perimeter'];
    $locations = ['Building A', 'Building B', 'Storage Area', 'Administration', 'Perimeter'];

    $cctvId = rand(1, 100);
    $cctvName = $cctvNames[array_rand($cctvNames)];
    $location = $locations[array_rand($locations)];
    $status = ['online', 'offline', 'recording', 'error'][array_rand(['online', 'offline', 'recording', 'error'])];

    // Initialize Pusher
    $pusher = new Pusher\Pusher(
        $pusherAppKey,
        $pusherAppSecret,
        $pusherAppId,
        [
            'cluster' => $pusherAppCluster,
            'useTLS' => false,
            'encrypted' => false,
            'curl_options' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]
        ]
    );

    // Prepare data
    $data = [
        'cctv_id' => $cctvId,
        'cctv_name' => $cctvName,
        'status' => $status,
        'location' => $location,
        'timestamp' => now()->toDateTimeString(),
        'source' => 'ATCS Web Interface'
    ];

    try {
        // Trigger the event
        $result = $pusher->trigger('cctv-status', 'cctv.status.changed', $data);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'CCTV event sent successfully!',
                'data' => $data
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to send CCTV event.']);
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
})->name('pusher.send.cctv.test');

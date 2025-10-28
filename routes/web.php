<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Livewire\Volt\Volt;

// Web routes (Livewire / Volt Starter Kit style)
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::view('/maps', 'maps')->middleware(['auth'])->name('maps');
Route::view('/locations', 'location')->middleware(['auth'])->name('locations');
Route::view('/contact', 'contact')->middleware(['auth'])->name('contact');
Route::view('/notifications', 'notifications')->middleware(['auth'])->name('notifications');

// Test route for WhatsApp OTP
Route::get('/test-whatsapp-otp', function () { return view('livewire.test-whatsapp-otp'); })->name('test.whatsapp.otp');

// Test route for Email OTP
Route::get('/test-email-otp', function () { return view('livewire.email-otp-verify'); })->name('test.email.otp');

// Authentication routes
require __DIR__.'/auth.php';

// Test route to check database connection
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
});

// New UI pages for Rooms & CCTV lists
Route::view('/rooms', 'rooms')->middleware(['auth'])->name('rooms');
Route::view('/cctv', 'cctv-list')->middleware(['auth'])->name('cctv.list');
Route::get('/cctv/stream/{cctv}', [StreamController::class, 'showLiveStream'])->middleware(['auth'])->name('cctv.stream');

// Messages routes
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/conversation/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages/typing', [MessageController::class, 'typing'])->name('messages.typing');

    // Test route to check users
    Route::get('/test-users', function() {
        if (Auth::check()) {
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
        } else {
            return response()->json(['error' => 'Not authenticated']);
        }
    });
});

// Search route
Route::get('/search', [SearchController::class, 'index'])->middleware(['auth'])->name('search.index');

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

// Map data endpoints (consumed by UI)
Route::middleware(['auth'])->group(function () {
    Route::get('/map-data', [MapController::class, 'data'])->name('maps.data');
    Route::get('/location-data', [MapController::class, 'locationData'])->name('locations.data');
    // Streaming controls used by UI (maps/location)
    Route::post('/stream/{cctv}/start', [StreamController::class, 'start'])->name('stream.start');
    Route::post('/stream/{cctv}/stop', [StreamController::class, 'stop'])->name('stream.stop');
    Route::post('/stream/{cctv}/snapshot', [StreamController::class, 'snapshot'])->name('stream.snapshot');
    Route::post('/stream/{cctv}/record', [StreamController::class, 'record'])->name('stream.record');
});

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

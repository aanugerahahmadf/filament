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

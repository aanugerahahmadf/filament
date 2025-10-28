<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Api\AlertApiController;
use App\Http\Controllers\Api\BuildingApiController;
use App\Http\Controllers\Api\CctvApiController;
use App\Http\Controllers\Api\MaintenanceApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\RoomApiController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\StreamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Health check routes
Route::prefix('health')->group(function () {
    Route::get('/', [HealthCheckController::class, 'index'])->name('api.health.index');
    Route::get('/alerts', [HealthCheckController::class, 'alerts'])->name('api.health.alerts');
});

// Notification API routes
Route::prefix('notifications')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [NotificationApiController::class, 'index'])->name('api.notifications.index');
    Route::post('/{id}/read', [NotificationApiController::class, 'markAsRead'])->name('api.notifications.mark-as-read');
    Route::delete('/{id}', [NotificationApiController::class, 'destroy'])->name('api.notifications.destroy');
    Route::post('/{id}/unread', [NotificationApiController::class, 'markAsUnread'])->name('api.notifications.mark-as-unread');
});

// CCTV API routes
Route::prefix('cctvs')->group(function () {
    Route::get('/', [CctvApiController::class, 'index'])->name('api.cctvs.index');
    Route::get('/{cctv}', [CctvApiController::class, 'show'])->name('api.cctvs.show');
    Route::get('/{cctv}/check-status', [CctvApiController::class, 'checkStatus'])->name('api.cctvs.check-status');
    Route::post('/{cctv}/start-stream', [CctvApiController::class, 'startStream'])->name('api.cctvs.start-stream');
    Route::post('/{cctv}/stop-stream', [CctvApiController::class, 'stopStream'])->name('api.cctvs.stop-stream');
    Route::get('/statistics', [CctvApiController::class, 'statistics'])->name('api.cctvs.statistics');
    Route::get('/detailed-statistics', [CctvApiController::class, 'detailedStatistics'])->name('api.cctvs.detailed-statistics');
    Route::get('/map-data', [CctvApiController::class, 'mapData'])->name('api.cctvs.map-data');
});

// Building API routes
Route::prefix('buildings')->group(function () {
    Route::get('/', [BuildingApiController::class, 'index'])->name('api.buildings.index');
    Route::get('/{building}', [BuildingApiController::class, 'show'])->name('api.buildings.show');
    Route::get('/{building}/statistics', [BuildingApiController::class, 'statistics'])->name('api.buildings.statistics');
});

// Room API routes
Route::prefix('rooms')->group(function () {
    Route::get('/', [RoomApiController::class, 'index'])->name('api.rooms.index');
    Route::get('/{room}', [RoomApiController::class, 'show'])->name('api.rooms.show');
    Route::get('/{room}/statistics', [RoomApiController::class, 'statistics'])->name('api.rooms.statistics');
});

// Maintenance API routes
Route::prefix('maintenances')->group(function () {
    Route::get('/', [MaintenanceApiController::class, 'index'])->name('api.maintenances.index');
    Route::get('/{maintenance}', [MaintenanceApiController::class, 'show'])->name('api.maintenances.show');
    Route::get('/statistics', [MaintenanceApiController::class, 'statistics'])->name('api.maintenances.statistics');
});

// Alert API routes
Route::prefix('alerts')->group(function () {
    Route::get('/', [AlertApiController::class, 'index'])->name('api.alerts.index');
    Route::get('/{alert}', [AlertApiController::class, 'show'])->name('api.alerts.show');
    Route::get('/statistics', [AlertApiController::class, 'statistics'])->name('api.alerts.statistics');
});

// Advanced Analytics API (Starter Kit style)
Route::prefix('analytics')->name('api.analytics.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [AnalyticsController::class, 'dashboardAnalytics'])->name('dashboard');
    Route::get('/cctv', [AnalyticsController::class, 'cctvAnalytics'])->name('cctv');
    Route::get('/system-health', [AnalyticsController::class, 'systemHealth'])->name('system-health');
    Route::get('/streaming', [AnalyticsController::class, 'streamingAnalytics'])->name('streaming');
    Route::get('/alerts', [AnalyticsController::class, 'realTimeAlerts'])->name('alerts');
});

// Cache utilities
Route::prefix('cache')->name('api.cache.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/refresh', function () {
        try {
            app(\App\Services\AdvancedCachingService::class)->cacheSystemStatus();
            return response()->json(['success' => true, 'message' => 'Cache refreshed']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error refreshing cache: ' . $e->getMessage()], 500);
        }
    })->name('refresh');
});

// Streaming APIs
Route::prefix('stream')->name('api.stream.')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/initialize', function (Request $request) {
        try {
            $ffmpegService = new \App\Services\AdvancedFfmpegService;
            return response()->json($ffmpegService->initializeAdvancedStream($request->all()));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error initializing stream: ' . $e->getMessage()], 500);
        }
    })->name('initialize');

    Route::delete('/{streamId}/stop', [StreamController::class, 'stopAdvancedStream'])->name('stop');

    Route::get('/{streamId}/metrics', function ($streamId) {
        try {
            $ffmpegService = new \App\Services\AdvancedFfmpegService;
            return response()->json($ffmpegService->getStreamMetrics($streamId));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error fetching stream metrics: ' . $e->getMessage()], 500);
        }
    })->name('metrics');

    Route::post('/{streamId}/adjust-quality', function ($streamId, Request $request) {
        try {
            $ffmpegService = new \App\Services\AdvancedFfmpegService;
            return response()->json($ffmpegService->adjustStreamQuality($streamId, $request->all()));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error adjusting stream quality: ' . $e->getMessage()], 500);
        }
    })->name('adjust-quality');
});

// Real-time broadcast APIs
Route::prefix('broadcast')->name('api.broadcast.')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/cctv-update', function (Request $request) {
        try {
            $broadcastService = new \App\Services\RealtimeBroadcastService;
            $cctv = \App\Models\Cctv::with(['building', 'room'])->find($request->cctv_id);
            if ($cctv) {
                $broadcastService->broadcastCctvUpdate($cctv);
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'error' => 'CCTV not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error broadcasting CCTV update: ' . $e->getMessage()], 500);
        }
    })->name('cctv-update');

    Route::post('/system-metrics', function () {
        try {
            $broadcastService = new \App\Services\RealtimeBroadcastService;
            $broadcastService->broadcastSystemMetrics();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error broadcasting system metrics: ' . $e->getMessage()], 500);
        }
    })->name('system-metrics');

    Route::post('/emergency-alert', function (Request $request) {
        try {
            $broadcastService = new \App\Services\RealtimeBroadcastService;
            $broadcastService->broadcastEmergencyAlert(
                $request->type,
                $request->message,
                $request->context ?? []
            );
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error broadcasting emergency alert: ' . $e->getMessage()], 500);
        }
    })->name('emergency-alert');
});

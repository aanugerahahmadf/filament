<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cctv;
use App\Services\CctvService;
use Illuminate\Http\JsonResponse;

class CctvApiController extends Controller
{
    protected CctvService $cctvService;

    public function __construct(CctvService $cctvService)
    {
        $this->cctvService = $cctvService;
    }

    /**
     * Get detailed statistics for all CCTVs
     */
    public function statistics(): JsonResponse
    {
        try {
            // Get overall statistics
            $statistics = Cctv::select('status')
                ->selectRaw('count(*) as count')
                ->groupBy('status')
                ->get()
                ->keyBy('status');

            // Get recently offline CCTVs
            $recentlyOffline = Cctv::offline()
                ->whereNotNull('last_seen_at')
                ->orderBy('last_seen_at', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'last_seen_at', 'building_id', 'room_id'])
                ->load(['building', 'room']);

            // Get CCTVs that need attention (offline for more than 24 hours)
            $needsAttention = Cctv::offline()
                ->where('last_seen_at', '<', now()->subDay())
                ->orderBy('last_seen_at', 'asc')
                ->limit(10)
                ->get(['id', 'name', 'last_seen_at', 'building_id', 'room_id'])
                ->load(['building', 'room']);

            // Get statistics by building
            $buildingStats = Cctv::with('building')
                ->select('building_id')
                ->selectRaw('count(*) as total')
                ->selectRaw("count(case when status = 'online' then 1 end) as online_count")
                ->selectRaw("count(case when status = 'offline' then 1 end) as offline_count")
                ->selectRaw("count(case when status = 'maintenance' then 1 end) as maintenance_count")
                ->groupBy('building_id')
                ->get();

            // Get connection type statistics
            $connectionStats = Cctv::select('connection_type')
                ->selectRaw('count(*) as count')
                ->selectRaw("count(case when status = 'online' then 1 end) as online_count")
                ->selectRaw("count(case when status = 'offline' then 1 end) as offline_count")
                ->selectRaw("count(case when status = 'maintenance' then 1 end) as maintenance_count")
                ->groupBy('connection_type')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => $statistics,
                    'recently_offline' => $recentlyOffline,
                    'needs_attention' => $needsAttention,
                    'by_building' => $buildingStats,
                    'by_connection_type' => $connectionStats,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching detailed statistics: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check status of a specific CCTV
     */
    public function checkStatus(Cctv $cctv): JsonResponse
    {
        try {
            $isOnline = $this->cctvService->checkCctvStatus($cctv);

            return response()->json([
                'success' => true,
                'data' => [
                    'cctv_id' => $cctv->id,
                    'is_online' => $isOnline,
                    'status' => $cctv->fresh()->status,
                    'last_seen_at' => $cctv->fresh()->last_seen_at,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking CCTV status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Start streaming for a CCTV
     */
    public function startStream(Cctv $cctv): JsonResponse
    {
        try {
            $success = $this->cctvService->startStream($cctv);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stream started successfully',
                    'data' => [
                        'hls_path' => $cctv->hls_path,
                        'status' => $cctv->fresh()->status,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to start stream',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error starting stream: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Stop streaming for a CCTV
     */
    public function stopStream(Cctv $cctv): JsonResponse
    {
        try {
            $this->cctvService->stopStream($cctv);

            return response()->json([
                'success' => true,
                'message' => 'Stream stopped successfully',
                'data' => [
                    'status' => $cctv->fresh()->status,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error stopping stream: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get map data for all CCTVs
     */
    public function mapData(): JsonResponse
    {
        try {
            $cctvs = Cctv::with(['building', 'room'])
                ->get();

            $features = $cctvs->map(function ($cctv) {
                // Skip CCTVs without location data
                if (!$cctv->building || !$cctv->building->latitude || !$cctv->building->longitude) {
                    return null;
                }

                return [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $cctv->id,
                        'name' => $cctv->name,
                        'status' => $cctv->status,
                        'status_badge_class' => $cctv->status_badge_class,
                        'building_name' => $cctv->building->name ?? null,
                        'room_name' => $cctv->room->name ?? null,
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            (float) $cctv->building->longitude,
                            (float) $cctv->building->latitude,
                        ],
                    ],
                ];
            })->filter(); // Remove null values

            return response()->json([
                'success' => true,
                'data' => [
                    'type' => 'FeatureCollection',
                    'features' => $features,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching map data: ' . $e->getMessage(),
            ], 500);
        }
    }
}

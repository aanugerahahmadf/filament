<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Cctv;
use App\Models\Room;
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
     * Get all CCTVs with their status
     */
    public function index(): JsonResponse
    {
        $cctvs = Cctv::with(['building', 'room'])
            ->select(['id', 'building_id', 'room_id', 'name', 'status', 'latitude', 'longitude', 'last_seen_at'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cctvs,
        ]);
    }

    /**
     * Get a specific CCTV with detailed information
     */
    public function show(Cctv $cctv): JsonResponse
    {
        $cctv->load(['building', 'room', 'maintenances']);

        return response()->json([
            'success' => true,
            'data' => $cctv,
        ]);
    }

    /**
     * Get CCTVs by building
     */
    public function byBuilding(Building $building): JsonResponse
    {
        $cctvs = $building->cctvs()
            ->with(['room'])
            ->select(['id', 'building_id', 'room_id', 'name', 'status', 'latitude', 'longitude', 'last_seen_at'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cctvs,
        ]);
    }

    /**
     * Get CCTVs by room
     */
    public function byRoom(Room $room): JsonResponse
    {
        $cctvs = $room->cctvs()
            ->select(['id', 'building_id', 'room_id', 'name', 'status', 'latitude', 'longitude', 'last_seen_at'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cctvs,
        ]);
    }

    /**
     * Get status statistics
     */
    public function statistics(): JsonResponse
    {
        $statistics = $this->cctvService->getStatusStatistics();

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    /**
     * Get detailed real-time statistics with trends
     */
    public function detailedStatistics(): JsonResponse
    {
        try {
            // Get basic statistics
            $statistics = $this->cctvService->getStatusStatistics();

            // Get recently offline CCTVs (last 24 hours)
            $recentlyOffline = Cctv::offline()
                ->where('last_seen_at', '>=', now()->subDay())
                ->with(['building', 'room'])
                ->orderBy('last_seen_at', 'desc')
                ->limit(10)
                ->get();

            // Get CCTVs needing attention (offline or maintenance)
            $needsAttention = Cctv::whereIn('status', ['offline', 'maintenance'])
                ->with(['building', 'room'])
                ->orderBy('updated_at', 'desc')
                ->limit(15)
                ->get();

            // Get status distribution by building
            $buildingStats = Building::withCount([
                'cctvs as total_cctvs',
                'cctvs as online_cctvs' => function ($query) {
                    $query->where('status', 'online');
                },
                'cctvs as offline_cctvs' => function ($query) {
                    $query->where('status', 'offline');
                },
                'cctvs as maintenance_cctvs' => function ($query) {
                    $query->where('status', 'maintenance');
                }
            ])->get()->map(function ($building) {
                return [
                    'id' => $building->id,
                    'name' => $building->name,
                    'total_cctvs' => $building->total_cctvs,
                    'online_cctvs' => $building->online_cctvs,
                    'offline_cctvs' => $building->offline_cctvs,
                    'maintenance_cctvs' => $building->maintenance_cctvs,
                    'online_percentage' => $building->total_cctvs > 0 ?
                        round(($building->online_cctvs / $building->total_cctvs) * 100, 2) : 0,
                ];
            });

            // Get connection type statistics
            $connectionStats = Cctv::select('connection_type')
                ->selectRaw('count(*) as count')
                ->selectRaw("count(case when status = 'online' then 1 end) as online_count")
                ->selectRaw("count(case when status = 'offline' then 1 end) as offline_count")
                ->selectRaw("count(case when status = 'maintenance' then 1 end) as maintenance_count")
                ->groupBy('connection_type')
                ->get();

            // Get recording statistics
            $recordingStats = Cctv::selectRaw('count(*) as total')
                ->selectRaw('count(case when recording = 1 then 1 end) as recording')
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => $statistics,
                    'recently_offline' => $recentlyOffline,
                    'needs_attention' => $needsAttention,
                    'by_building' => $buildingStats,
                    'by_connection_type' => $connectionStats,
                    'recording' => [
                        'total' => $recordingStats->total ?? 0,
                        'recording' => $recordingStats->recording ?? 0,
                        'not_recording' => ($recordingStats->total ?? 0) - ($recordingStats->recording ?? 0),
                    ],
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
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();

            $features = $cctvs->map(function ($cctv) {
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
                            (float) $cctv->longitude,
                            (float) $cctv->latitude,
                        ],
                    ],
                ];
            });

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

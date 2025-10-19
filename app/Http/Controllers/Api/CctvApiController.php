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
     * Check status of a specific CCTV
     */
    public function checkStatus(Cctv $cctv): JsonResponse
    {
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
    }

    /**
     * Start streaming for a CCTV
     */
    public function startStream(Cctv $cctv): JsonResponse
    {
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
    }

    /**
     * Stop streaming for a CCTV
     */
    public function stopStream(Cctv $cctv): JsonResponse
    {
        $this->cctvService->stopStream($cctv);

        return response()->json([
            'success' => true,
            'message' => 'Stream stopped successfully',
            'data' => [
                'status' => $cctv->fresh()->status,
            ],
        ]);
    }

    /**
     * Get map data for all CCTVs
     */
    public function mapData(): JsonResponse
    {
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
    }
}

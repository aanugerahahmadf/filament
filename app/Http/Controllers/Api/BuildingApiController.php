<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\JsonResponse;

class BuildingApiController extends Controller
{
    /**
     * Get all buildings
     */
    public function index(): JsonResponse
    {
        $buildings = Building::withCount(['rooms', 'cctvs'])
            ->select(['id', 'name', 'address', 'latitude', 'longitude'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $buildings,
        ]);
    }

    /**
     * Get a specific building with detailed information
     */
    public function show(Building $building): JsonResponse
    {
        $building->load(['rooms.cctvs', 'cctvs']);

        return response()->json([
            'success' => true,
            'data' => $building,
        ]);
    }

    /**
     * Get statistics for a building
     */
    public function statistics(Building $building): JsonResponse
    {
        $statistics = [
            'room_count' => $building->room_count,
            'cctv_count' => $building->cctv_count,
            'online_cctv_count' => $building->online_cctv_count,
            'offline_cctv_count' => $building->offline_cctv_count,
            'maintenance_cctv_count' => $building->maintenance_cctv_count,
            'overall_status' => $building->overall_status,
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }
}

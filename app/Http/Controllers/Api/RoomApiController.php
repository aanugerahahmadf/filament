<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class RoomApiController extends Controller
{
    /**
     * Get all rooms
     */
    public function index(): JsonResponse
    {
        $rooms = Room::with(['building', 'cctvs'])
            ->select(['id', 'building_id', 'name', 'floor', 'capacity'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rooms,
        ]);
    }

    /**
     * Get a specific room with detailed information
     */
    public function show(Room $room): JsonResponse
    {
        $room->load(['building', 'cctvs']);

        return response()->json([
            'success' => true,
            'data' => $room,
        ]);
    }

    /**
     * Get statistics for a room
     */
    public function statistics(Room $room): JsonResponse
    {
        $statistics = [
            'cctv_count' => $room->cctv_count,
            'online_cctv_count' => $room->online_cctv_count,
            'offline_cctv_count' => $room->offline_cctv_count,
            'maintenance_cctv_count' => $room->maintenance_cctv_count,
            'overall_status' => $room->overall_status,
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }
}

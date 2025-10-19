<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaintenanceApiController extends Controller
{
    /**
     * Get all maintenance records
     */
    public function index(Request $request): JsonResponse
    {
        $query = Maintenance::with(['cctv', 'technician']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('scheduled_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('scheduled_at', '<=', $request->end_date);
        }

        $maintenances = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $maintenances,
        ]);
    }

    /**
     * Get a specific maintenance record
     */
    public function show(Maintenance $maintenance): JsonResponse
    {
        $maintenance->load(['cctv.building', 'cctv.room', 'technician']);

        return response()->json([
            'success' => true,
            'data' => $maintenance,
        ]);
    }

    /**
     * Get maintenance statistics
     */
    public function statistics(): JsonResponse
    {
        $total = Maintenance::count();
        $scheduled = Maintenance::scheduled()->count();
        $inProgress = Maintenance::inProgress()->count();
        $completed = Maintenance::completed()->count();
        $cancelled = Maintenance::cancelled()->count();

        $costSum = Maintenance::sum('cost');

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'scheduled' => $scheduled,
                'in_progress' => $inProgress,
                'completed' => $completed,
                'cancelled' => $cancelled,
                'total_cost' => $costSum,
            ],
        ]);
    }
}

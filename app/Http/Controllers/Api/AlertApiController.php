<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertApiController extends Controller
{
    /**
     * Get all alerts
     */
    public function index(Request $request): JsonResponse
    {
        $query = Alert::with(['user', 'alertable']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by severity
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('triggered_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('triggered_at', '<=', $request->end_date);
        }

        $alerts = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $alerts,
        ]);
    }

    /**
     * Get a specific alert
     */
    public function show(Alert $alert): JsonResponse
    {
        $alert->load(['user', 'alertable']);

        return response()->json([
            'success' => true,
            'data' => $alert,
        ]);
    }

    /**
     * Get alert statistics
     */
    public function statistics(): JsonResponse
    {
        $total = Alert::count();
        $active = Alert::active()->count();
        $acknowledged = Alert::acknowledged()->count();
        $resolved = Alert::resolved()->count();
        $suppressed = Alert::suppressed()->count();

        // Group by severity
        $bySeverity = Alert::selectRaw('severity, count(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity');

        // Group by category
        $byCategory = Alert::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'active' => $active,
                'acknowledged' => $acknowledged,
                'resolved' => $resolved,
                'suppressed' => $suppressed,
                'by_severity' => $bySeverity,
                'by_category' => $byCategory,
            ],
        ]);
    }
}

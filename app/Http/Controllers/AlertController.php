<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
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

        $alerts = $query->latest()->paginate(10);

        return view('alerts.index', compact('alerts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Alert $alert): View
    {
        $alert->load(['user', 'alertable']);

        return view('alerts.show', compact('alert'));
    }

    /**
     * Acknowledge the specified alert.
     */
    public function acknowledge(Alert $alert): JsonResponse
    {
        $alert->acknowledge(Auth::user());

        return response()->json([
            'message' => 'Alert acknowledged successfully',
            'alert' => $alert->fresh(),
        ]);
    }

    /**
     * Resolve the specified alert.
     */
    public function resolve(Alert $alert): JsonResponse
    {
        $alert->resolve(Auth::user());

        return response()->json([
            'message' => 'Alert resolved successfully',
            'alert' => $alert->fresh(),
        ]);
    }

    /**
     * Suppress the specified alert.
     */
    public function suppress(Alert $alert): JsonResponse
    {
        $alert->suppress(Auth::user());

        return response()->json([
            'message' => 'Alert suppressed successfully',
            'alert' => $alert->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alert $alert): JsonResponse
    {
        $alert->delete();

        return response()->json(['message' => 'Alert deleted successfully']);
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
            'total' => $total,
            'active' => $active,
            'acknowledged' => $acknowledged,
            'resolved' => $resolved,
            'suppressed' => $suppressed,
            'by_severity' => $bySeverity,
            'by_category' => $byCategory,
        ]);
    }
}

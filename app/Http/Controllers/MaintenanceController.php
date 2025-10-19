<?php

namespace App\Http\Controllers;

use App\Models\Cctv;
use App\Models\Maintenance;
use App\Models\User;
use App\Services\CctvService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    protected CctvService $cctvService;

    public function __construct(CctvService $cctvService)
    {
        $this->cctvService = $cctvService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
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

        $maintenances = $query->latest()->paginate(10);

        return view('maintenances.index', compact('maintenances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cctvs = Cctv::all();
        $technicians = User::all();

        return view('maintenances.create', compact('cctvs', 'technicians'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'cctv_id' => 'required|exists:cctvs,id',
            'technician_id' => 'nullable|exists:users,id',
            'scheduled_at' => 'nullable|date',
            'type' => 'required|in:preventive,corrective,emergency',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $maintenance = Maintenance::create($request->all());

        return response()->json([
            'message' => 'Maintenance scheduled successfully',
            'maintenance' => $maintenance,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance): View
    {
        $maintenance->load(['cctv.building', 'cctv.room', 'technician']);

        return view('maintenances.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance): View
    {
        $cctvs = Cctv::all();
        $technicians = User::all();

        return view('maintenances.edit', compact('maintenance', 'cctvs', 'technicians'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance): JsonResponse
    {
        $request->validate([
            'cctv_id' => 'required|exists:cctvs,id',
            'technician_id' => 'nullable|exists:users,id',
            'scheduled_at' => 'nullable|date',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'cancelled_at' => 'nullable|date',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'type' => 'required|in:preventive,corrective,emergency',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $maintenance->update($request->all());

        return response()->json([
            'message' => 'Maintenance updated successfully',
            'maintenance' => $maintenance,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance): JsonResponse
    {
        $maintenance->delete();

        return response()->json(['message' => 'Maintenance deleted successfully']);
    }

    /**
     * Start maintenance
     */
    public function start(Maintenance $maintenance): JsonResponse
    {
        $maintenance->update([
            'status' => Maintenance::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);

        return response()->json([
            'message' => 'Maintenance started successfully',
            'maintenance' => $maintenance->fresh(),
        ]);
    }

    /**
     * Complete maintenance
     */
    public function complete(Request $request, Maintenance $maintenance): JsonResponse
    {
        $request->validate([
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $maintenance->update([
            'status' => Maintenance::STATUS_COMPLETED,
            'completed_at' => now(),
            'notes' => $request->notes ?? $maintenance->notes,
            'cost' => $request->cost ?? $maintenance->cost,
        ]);

        // Update CCTV status to online after maintenance
        $maintenance->cctv->update([
            'status' => Cctv::STATUS_ONLINE,
        ]);

        return response()->json([
            'message' => 'Maintenance completed successfully',
            'maintenance' => $maintenance->fresh(),
        ]);
    }

    /**
     * Cancel maintenance
     */
    public function cancel(Request $request, Maintenance $maintenance): JsonResponse
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $maintenance->update([
            'status' => Maintenance::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'notes' => $request->notes ?? $maintenance->notes,
        ]);

        return response()->json([
            'message' => 'Maintenance cancelled successfully',
            'maintenance' => $maintenance->fresh(),
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
            'total' => $total,
            'scheduled' => $scheduled,
            'in_progress' => $inProgress,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'total_cost' => $costSum,
        ]);
    }
}

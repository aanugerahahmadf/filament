<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Cctv;
use App\Models\Room;
use App\Services\CctvService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CctvController extends Controller
{
    protected CctvService $cctvService;

    public function __construct(CctvService $cctvService)
    {
        $this->cctvService = $cctvService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $cctvs = Cctv::with(['building', 'room'])->latest()->paginate(10);
        $statistics = $this->cctvService->getStatusStatistics();

        return view('cctvs.index', compact('cctvs', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $buildings = Building::all();
        $rooms = Room::all();

        return view('cctvs.create', compact('buildings', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'room_id' => 'nullable|exists:rooms,id',
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'firmware_version' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ip_rtsp' => 'required|string|max:255',
            'stream_username' => 'nullable|string|max:255',
            'stream_password' => 'nullable|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'resolution' => 'nullable|string|max:50',
            'fps' => 'required|integer|min:1|max:120',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $cctv = Cctv::create($request->all());

        return response()->json([
            'message' => 'CCTV created successfully',
            'cctv' => $cctv,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cctv $cctv): View
    {
        $cctv->load(['building', 'room', 'maintenances.technician']);

        return view('cctvs.show', compact('cctv'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cctv $cctv): View
    {
        $buildings = Building::all();
        $rooms = Room::all();

        return view('cctvs.edit', compact('cctv', 'buildings', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cctv $cctv): JsonResponse
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'room_id' => 'nullable|exists:rooms,id',
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'firmware_version' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ip_rtsp' => 'required|string|max:255',
            'stream_username' => 'nullable|string|max:255',
            'stream_password' => 'nullable|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'resolution' => 'nullable|string|max:50',
            'fps' => 'required|integer|min:1|max:120',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $cctv->update($request->all());

        return response()->json([
            'message' => 'CCTV updated successfully',
            'cctv' => $cctv,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cctv $cctv): JsonResponse
    {
        // Stop streaming if active
        if ($cctv->isOnline()) {
            $this->cctvService->stopStream($cctv);
        }

        $cctv->delete();

        return response()->json(['message' => 'CCTV deleted successfully']);
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
     * Check status of a CCTV
     */
    public function checkStatus(Cctv $cctv): JsonResponse
    {
        $isOnline = $this->cctvService->checkCctvStatus($cctv);

        return response()->json([
            'message' => 'Status checked successfully',
            'is_online' => $isOnline,
            'status' => $cctv->fresh()->status,
        ]);
    }

    /**
     * Get statistics for all CCTVs
     */
    public function statistics(): JsonResponse
    {
        $statistics = $this->cctvService->getStatusStatistics();

        return response()->json($statistics);
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
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}

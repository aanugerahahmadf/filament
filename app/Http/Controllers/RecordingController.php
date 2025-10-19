<?php

namespace App\Http\Controllers;

use App\Models\Cctv;
use App\Models\Recording;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RecordingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Recording::with(['cctv']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by CCTV
        if ($request->has('cctv_id')) {
            $query->where('cctv_id', $request->cctv_id);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('started_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('started_at', '<=', $request->end_date);
        }

        $recordings = $query->latest()->paginate(10);
        $cctvs = Cctv::all();

        return view('recordings.index', compact('recordings', 'cctvs'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Recording $recording): View
    {
        $recording->load(['cctv.building', 'cctv.room']);

        return view('recordings.show', compact('recording'));
    }

    /**
     * Download the specified resource.
     */
    public function download(Recording $recording)
    {
        if (! Storage::exists($recording->filepath)) {
            return response()->json(['message' => 'Recording file not found'], 404);
        }

        return Storage::download($recording->filepath, $recording->filename);
    }

    /**
     * Archive the specified resource.
     */
    public function archive(Recording $recording): JsonResponse
    {
        $recording->update(['status' => 'archived']);

        return response()->json([
            'message' => 'Recording archived successfully',
            'recording' => $recording->fresh(),
        ]);
    }

    /**
     * Restore the specified resource from archive.
     */
    public function restore(Recording $recording): JsonResponse
    {
        $recording->update(['status' => 'active']);

        return response()->json([
            'message' => 'Recording restored successfully',
            'recording' => $recording->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recording $recording): JsonResponse
    {
        // Delete the file from storage
        if (Storage::exists($recording->filepath)) {
            Storage::delete($recording->filepath);
        }

        $recording->update(['status' => 'deleted']);

        return response()->json(['message' => 'Recording deleted successfully']);
    }

    /**
     * Get recording statistics
     */
    public function statistics(): JsonResponse
    {
        $total = Recording::count();
        $active = Recording::active()->count();
        $archived = Recording::archived()->count();
        $deleted = Recording::deleted()->count();

        $totalSize = Recording::sum('size');

        // Group by format
        $byFormat = Recording::selectRaw('format, count(*) as count, sum(size) as total_size')
            ->whereNotNull('format')
            ->groupBy('format')
            ->get();

        return response()->json([
            'total' => $total,
            'active' => $active,
            'archived' => $archived,
            'deleted' => $deleted,
            'total_size' => $totalSize,
            'by_format' => $byFormat,
        ]);
    }
}

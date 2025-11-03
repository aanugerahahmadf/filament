<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Models\Cctv;
use App\Services\FfmpegStreamService;
use App\Services\AdvancedFfmpegService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Display the locations page (location.blade.php)
     */
    public function index(): View
    {
        // This will render the existing location.blade.php view
        return view('location');
    }

    /**
     * Display the rooms page (rooms.blade.php)
     */
    public function rooms(): View
    {
        // This will render the existing rooms.blade.php view
        return view('rooms');
    }

    /**
     * Display the CCTV list page (cctv-list.blade.php)
     */
    public function cctvList(): View
    {
        // This will render the existing cctv-list.blade.php view
        return view('cctv-list');
    }

    /**
     * Start streaming for a CCTV
     */
    public function start(Request $request, Cctv $cctv, FfmpegStreamService $service): JsonResponse
    {
        try {
            // Validate that the CCTV has an RTSP URL
            if (empty($cctv->ip_rtsp)) {
                return response()->json([
                    'error' => 'CCTV configuration error: No RTSP URL configured for this camera.'
                ], 400);
            }

            // Test basic connectivity first
            $parsedUrl = parse_url($cctv->ip_rtsp);
            $host = $parsedUrl['host'] ?? null;
            $port = $parsedUrl['port'] ?? 554;

            if (!$host) {
                return response()->json([
                    'error' => 'CCTV configuration error: Invalid RTSP URL format.'
                ], 400);
            }

            // Test connectivity with a short timeout
            $socket = @fsockopen($host, $port, $errno, $errstr, 3);
            if (!$socket) {
                return response()->json([
                    'error' => 'Camera is unreachable. Please check network connectivity or camera status.',
                    'details' => "Failed to connect to {$host}:{$port} - {$errstr} ({$errno})"
                ], 503);
            }
            fclose($socket);

            // If connectivity test passes, start the stream
            $url = $service->startStream($cctv);

            return response()->json(['hls' => $url]);
        } catch (\Exception $e) {
            Log::error('Stream start error for CCTV ' . $cctv->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to start stream',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stop streaming for a CCTV
     */
    public function stop(Request $request, Cctv $cctv, FfmpegStreamService $service): JsonResponse
    {
        $service->stopStream($cctv);

        return response()->json(['message' => 'Stream stopped successfully']);
    }

    /**
     * Take a snapshot from a CCTV
     */
    public function snapshot(Request $request, Cctv $cctv, FfmpegStreamService $service): JsonResponse
    {
        $url = $service->takeSnapshot($cctv);

        return response()->json(['image' => $url]);
    }

    /**
     * Record a clip from a CCTV
     */
    public function record(Request $request, Cctv $cctv, FfmpegStreamService $service): JsonResponse
    {
        $seconds = (int) $request->input('seconds', 30);
        $url = $service->recordClip($cctv, $seconds);

        return response()->json(['video' => $url]);
    }

    /**
     * Stop advanced stream gracefully
     */
    public function stopAdvancedStream(Request $request, string $streamId, AdvancedFfmpegService $service): JsonResponse
    {
        try {
            $result = $service->stopAdvancedStream($streamId);

            if ($result['success']) {
                return response()->json($result);
            } else {
                return response()->json($result, 500);
            }
        } catch (\Exception $e) {
            Log::error('Failed to stop advanced stream: ' . $e->getMessage(), [
                'stream_id' => $streamId,
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to stop advanced stream: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get locations data as JSON for API usage
     */
    public function locationsData(): JsonResponse
    {
        $buildings = Building::withCount(['rooms', 'cctvs'])->get();

        return response()->json([
            'success' => true,
            'data' => $buildings
        ]);
    }

    /**
     * Get rooms data as JSON for API usage
     */
    public function roomsData(): JsonResponse
    {
        $rooms = Room::with(['building'])->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $rooms
        ]);
    }

    /**
     * Get CCTV list data as JSON for API usage
     */
    public function cctvListData(): JsonResponse
    {
        $cctvs = Cctv::with(['building', 'room'])->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $cctvs
        ]);
    }
}

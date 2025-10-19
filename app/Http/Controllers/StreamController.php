<?php

namespace App\Http\Controllers;

use App\Models\Cctv;
use App\Services\FfmpegStreamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StreamController extends Controller
{
    public function start(Request $request, Cctv $cctv, FfmpegStreamService $service)
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

    public function stop(Request $request, Cctv $cctv, FfmpegStreamService $service)
    {
        $service->stopStream($cctv);

        return response()->noContent();
    }

    public function snapshot(Request $request, Cctv $cctv, FfmpegStreamService $service)
    {
        $url = $service->takeSnapshot($cctv);

        return response()->json(['image' => $url]);
    }

    public function record(Request $request, Cctv $cctv, FfmpegStreamService $service)
    {
        $seconds = (int) $request->input('seconds', 30);
        $url = $service->recordClip($cctv, $seconds);

        return response()->json(['video' => $url]);
    }

    public function showLiveStream(Request $request, Cctv $cctv, FfmpegStreamService $service)
    {
        // Auto-start stream if not already online
        if ($cctv->status !== Cctv::STATUS_ONLINE) {
            try {
                $service->startStream($cctv);
                $cctv->refresh();
            } catch (\Exception $e) {
                Log::error('Auto-start stream failed: ' . $e->getMessage());
            }
        }

        $hlsUrl = $cctv->hls_path ? url($cctv->hls_path) : null;

        return view('cctv-live-stream', compact('cctv', 'hlsUrl'));
    }
}

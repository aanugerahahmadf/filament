<?php

namespace App\Http\Controllers;

use App\Models\Cctv;
use App\Services\FfmpegStreamService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class CctvStreamController extends Controller
{
    /**
     * Display the live stream page for a CCTV (cctv-live-stream.blade.php)
     */
    public function show(Request $request, Cctv $cctv, FfmpegStreamService $service): View
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

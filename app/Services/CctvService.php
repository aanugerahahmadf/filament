<?php

namespace App\Services;

use App\Events\CctvStatusUpdated;
use App\Models\Alert;
use App\Models\Cctv;
use App\Models\Maintenance;
use App\Models\Recording;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class CctvService
{
    protected FfmpegStreamService $ffmpegService;

    public function __construct(FfmpegStreamService $ffmpegService)
    {
        $this->ffmpegService = $ffmpegService;
    }

    /**
     * Start streaming for a CCTV
     */
    public function startStream(Cctv $cctv): bool
    {
        try {
            $this->ffmpegService->startStream($cctv);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to start stream for CCTV '.$cctv->id.': '.$e->getMessage());

            // Create alert for failed stream
            Alert::create([
                'alertable_type' => Cctv::class,
                'alertable_id' => $cctv->id,
                'title' => 'Stream Start Failed',
                'message' => 'Failed to start stream for CCTV: '.$cctv->name,
                'severity' => Alert::SEVERITY_HIGH,
                'category' => 'network',
                'source' => 'cctv_service',
                'triggered_at' => now(),
                'data' => [
                    'error' => $e->getMessage(),
                    'cctv_id' => $cctv->id,
                    'cctv_name' => $cctv->name,
                ],
            ]);

            return false;
        }
    }

    /**
     * Stop streaming for a CCTV
     */
    public function stopStream(Cctv $cctv): void
    {
        try {
            $this->ffmpegService->stopStream($cctv);
        } catch (\Exception $e) {
            Log::error('Failed to stop stream for CCTV '.$cctv->id.': '.$e->getMessage());
        }
    }

    /**
     * Check if a CCTV is online
     */
    public function checkCctvStatus(Cctv $cctv): bool
    {
        try {
            // Attempt to connect to the RTSP stream
            $rtspUrl = $cctv->full_rtsp_url;

            // Use ffprobe to check if the stream is accessible
            $ffprobe = config('services.ffprobe.binary', 'ffprobe');
            $args = [
                $ffprobe,
                '-v', 'quiet',
                '-print_format', 'json',
                '-show_streams',
                $rtspUrl,
            ];

            $process = new Process($args);
            $process->setTimeout(10); // 10 second timeout
            $process->run();

            $isOnline = $process->isSuccessful();

            // Update CCTV status
            $cctv->update([
                'status' => $isOnline ? Cctv::STATUS_ONLINE : Cctv::STATUS_OFFLINE,
                'last_seen_at' => $isOnline ? now() : $cctv->last_seen_at,
            ]);

            // Dispatch event for status update
            CctvStatusUpdated::dispatch($cctv->fresh());

            // If CCTV went offline, create an alert
            if (! $isOnline && $cctv->wasChanged('status')) {
                Alert::create([
                    'alertable_type' => Cctv::class,
                    'alertable_id' => $cctv->id,
                    'title' => 'CCTV Offline',
                    'message' => 'CCTV camera went offline: '.$cctv->name,
                    'severity' => Alert::SEVERITY_HIGH,
                    'category' => 'hardware',
                    'source' => 'cctv_service',
                    'triggered_at' => now(),
                    'data' => [
                        'cctv_id' => $cctv->id,
                        'cctv_name' => $cctv->name,
                    ],
                ]);
            }

            return $isOnline;
        } catch (\Exception $e) {
            Log::error('Failed to check status for CCTV '.$cctv->id.': '.$e->getMessage());

            // Update CCTV status to offline
            $cctv->update([
                'status' => Cctv::STATUS_OFFLINE,
            ]);

            CctvStatusUpdated::dispatch($cctv->fresh());

            return false;
        }
    }

    /**
     * Schedule maintenance for a CCTV
     */
    public function scheduleMaintenance(Cctv $cctv, array $data): Maintenance
    {
        $maintenance = Maintenance::create([
            'cctv_id' => $cctv->id,
            'technician_id' => $data['technician_id'] ?? null,
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'status' => Maintenance::STATUS_SCHEDULED,
            'type' => $data['type'] ?? 'preventive',
            'description' => $data['description'] ?? null,
            'notes' => $data['notes'] ?? null,
            'cost' => $data['cost'] ?? null,
        ]);

        // Create alert for scheduled maintenance
        Alert::create([
            'alertable_type' => Maintenance::class,
            'alertable_id' => $maintenance->id,
            'title' => 'Maintenance Scheduled',
            'message' => 'Maintenance scheduled for CCTV: '.$cctv->name,
            'severity' => Alert::SEVERITY_MEDIUM,
            'category' => 'maintenance',
            'source' => 'cctv_service',
            'triggered_at' => now(),
            'data' => [
                'cctv_id' => $cctv->id,
                'cctv_name' => $cctv->name,
                'maintenance_id' => $maintenance->id,
            ],
        ]);

        return $maintenance;
    }

    /**
     * Start recording for a CCTV
     */
    public function startRecording(Cctv $cctv, ?string $filename = null): Recording
    {
        if (! $filename) {
            $filename = 'recording_'.$cctv->id.'_'.now()->format('Y-m-d_H-i-s').'.mp4';
        }

        $filepath = 'recordings/'.$filename;

        $recording = Recording::create([
            'cctv_id' => $cctv->id,
            'filename' => $filename,
            'filepath' => $filepath,
            'started_at' => now(),
            'status' => 'active',
        ]);

        // Start the actual recording process (this would typically be done with FFmpeg)
        // For now, we'll just log that recording has started
        Log::info('Recording started for CCTV '.$cctv->id.' with filename '.$filename);

        return $recording;
    }

    /**
     * Stop recording for a CCTV
     */
    public function stopRecording(Recording $recording): void
    {
        $recording->update([
            'ended_at' => now(),
            'status' => 'active', // Keep as active until processed
        ]);

        // In a real implementation, you would stop the FFmpeg process here
        Log::info('Recording stopped for recording ID '.$recording->id);
    }

    /**
     * Get all online CCTVs
     */
    public function getOnlineCctvs()
    {
        return Cctv::online()->get();
    }

    /**
     * Get all offline CCTVs
     */
    public function getOfflineCctvs()
    {
        return Cctv::offline()->get();
    }

    /**
     * Get all CCTVs in maintenance
     */
    public function getMaintenanceCctvs()
    {
        return Cctv::maintenance()->get();
    }

    /**
     * Get CCTV status statistics
     */
    public function getStatusStatistics(): array
    {
        $total = Cctv::count();
        $online = Cctv::online()->count();
        $offline = Cctv::offline()->count();
        $maintenance = Cctv::maintenance()->count();

        return [
            'total' => $total,
            'online' => $online,
            'offline' => $offline,
            'maintenance' => $maintenance,
            'online_percentage' => $total > 0 ? round(($online / $total) * 100, 2) : 0,
            'offline_percentage' => $total > 0 ? round(($offline / $total) * 100, 2) : 0,
            'maintenance_percentage' => $total > 0 ? round(($maintenance / $total) * 100, 2) : 0,
        ];
    }
}

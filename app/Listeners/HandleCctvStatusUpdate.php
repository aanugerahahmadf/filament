<?php

namespace App\Listeners;

use App\Events\CctvStatusUpdated;
use App\Models\Alert;
use App\Models\Cctv;
use Illuminate\Support\Facades\Log;

class HandleCctvStatusUpdate
{
    /**
     * Handle the event.
     */
    public function handle(CctvStatusUpdated $event): void
    {
        $cctv = $event->cctv;

        Log::info('CCTV status updated', [
            'cctv_id' => $cctv->id,
            'cctv_name' => $cctv->name,
            'status' => $cctv->status,
        ]);

        // If CCTV went offline, create an alert
        if ($cctv->status === Cctv::STATUS_OFFLINE) {
            // Check if there's already an active alert for this CCTV
            $existingAlert = Alert::active()
                ->where('alertable_type', Cctv::class)
                ->where('alertable_id', $cctv->id)
                ->first();

            // Only create a new alert if there isn't already an active one
            if (! $existingAlert) {
                Alert::create([
                    'alertable_type' => Cctv::class,
                    'alertable_id' => $cctv->id,
                    'title' => 'CCTV Offline',
                    'message' => 'CCTV camera went offline: '.$cctv->name,
                    'severity' => Alert::SEVERITY_HIGH,
                    'category' => 'hardware',
                    'source' => 'cctv_status_listener',
                    'triggered_at' => now(),
                    'data' => [
                        'cctv_id' => $cctv->id,
                        'cctv_name' => $cctv->name,
                    ],
                ]);
            }
        }

        // If CCTV came back online, resolve any existing alerts
        if ($cctv->status === Cctv::STATUS_ONLINE) {
            // Find and resolve any active alerts for this CCTV
            $alerts = Alert::active()
                ->where('alertable_type', Cctv::class)
                ->where('alertable_id', $cctv->id)
                ->get();

            foreach ($alerts as $alert) {
                $alert->resolve();
            }
        }
    }
}

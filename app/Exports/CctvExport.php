<?php

namespace App\Exports;

use App\Models\Cctv;

class CctvExport extends BaseExport
{
    /**
     * Get the headings for the export
     */
    protected function getHeadings(): array
    {
        return [
            'ID',
            'Building',
            'Room',
            'Name',
            'Model',
            'Serial Number',
            'Firmware Version',
            'Stream Username',
            'RTSP IP',
            'Port',
            'Resolution',
            'FPS',
            'Recording Schedule',
            'Status',
            'Latitude',
            'Longitude',
            'HLS Path',
            'Last Seen',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Map a row to an array for export
     *
     * @param  Cctv  $cctv
     */
    protected function mapRow($cctv): array
    {
        return [
            $cctv->id,
            $cctv->building->name ?? '',
            $cctv->room->name ?? '',
            $cctv->name,
            $cctv->model,
            $cctv->serial_number,
            $cctv->firmware_version,
            $cctv->stream_username,
            $cctv->ip_rtsp,
            $cctv->port,
            $cctv->resolution,
            $cctv->fps,
            $cctv->recording_schedule,
            $cctv->status,
            $cctv->latitude,
            $cctv->longitude,
            $cctv->hls_path,
            $cctv->last_seen_at ? $cctv->last_seen_at->format('Y-m-d H:i:s') : '',
            $cctv->created_at ? $cctv->created_at->format('Y-m-d H:i:s') : '',
            $cctv->updated_at ? $cctv->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }
}

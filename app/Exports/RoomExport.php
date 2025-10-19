<?php

namespace App\Exports;

use App\Models\Room;

class RoomExport extends BaseExport
{
    /**
     * Get the headings for the export
     */
    protected function getHeadings(): array
    {
        return [
            'ID',
            'Building',
            'Name',
            'Floor',
            'Capacity',
            'Latitude',
            'Longitude',
            'Marker Icon Path',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Map a row to an array for export
     *
     * @param  Room  $room
     */
    protected function mapRow($room): array
    {
        return [
            $room->id,
            $room->building->name ?? '',
            $room->name,
            $room->floor,
            $room->capacity,
            $room->latitude,
            $room->longitude,
            $room->marker_icon_path,
            $room->created_at ? $room->created_at->format('Y-m-d H:i:s') : '',
            $room->updated_at ? $room->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }
}

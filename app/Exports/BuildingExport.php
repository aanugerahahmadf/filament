<?php

namespace App\Exports;

use App\Models\Building;

class BuildingExport extends BaseExport
{
    /**
     * Get the headings for the export
     */
    protected function getHeadings(): array
    {
        return [
            'ID',
            'Name',
            'Address',
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
     * @param  Building  $building
     */
    protected function mapRow($building): array
    {
        return [
            $building->id,
            $building->name,
            $building->address,
            $building->latitude,
            $building->longitude,
            $building->marker_icon_path,
            $building->created_at ? $building->created_at->format('Y-m-d H:i:s') : '',
            $building->updated_at ? $building->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function data(Request $request)
    {
        // Return full attributes so UI can use marker_icon/icon_url and coordinates
        $buildings = Building::with([
            'rooms' => function ($q) {
                $q->with(['cctvs']);
            },
            'cctvs',
        ])
            ->get()
            ->map(function ($building) {
                // Add coordinates to rooms if not set
                $building->rooms->each(function ($room) use ($building) {
                    if (! $room->latitude || ! $room->longitude) {
                        $room->latitude = $building->latitude + (rand(-100, 100) / 10000);
                        $room->longitude = $building->longitude + (rand(-100, 100) / 10000);
                    }
                });

                // Add coordinates to building CCTVs if not set
                $building->cctvs->each(function ($cctv) use ($building) {
                    if (! $cctv->latitude || ! $cctv->longitude) {
                        $cctv->latitude = $building->latitude + (rand(-50, 50) / 10000);
                        $cctv->longitude = $building->longitude + (rand(-50, 50) / 10000);
                    }
                });

                return $building;
            });

        return response()->json([
            'buildings' => $buildings,
        ]);
    }

    public function locationData(Request $request)
    {
        // Include full room & CCTV data (names, status, coordinates, marker icons)
        $buildings = Building::withCount(['rooms', 'cctvs'])
            ->with([
                'rooms' => function ($q) {
                    $q->with(['cctvs']);
                },
            ])->get();

        return response()->json([
            'buildings' => $buildings,
        ]);
    }
}

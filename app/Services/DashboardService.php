<?php

namespace App\Services;

use App\Models\Building;
use App\Models\Cctv;
use App\Models\Room;
use App\Models\User;
use App\Repositories\AlertRepository;
use App\Repositories\BuildingRepository;
use App\Repositories\CctvRepository;
use App\Repositories\MaintenanceRepository;
use App\Repositories\RoomRepository;

class DashboardService
{
    protected CctvRepository $cctvRepository;

    protected BuildingRepository $buildingRepository;

    protected RoomRepository $roomRepository;

    protected MaintenanceRepository $maintenanceRepository;

    protected AlertRepository $alertRepository;

    public function __construct(
        CctvRepository $cctvRepository,
        BuildingRepository $buildingRepository,
        RoomRepository $roomRepository,
        MaintenanceRepository $maintenanceRepository,
        AlertRepository $alertRepository
    ) {
        $this->cctvRepository = $cctvRepository;
        $this->buildingRepository = $buildingRepository;
        $this->roomRepository = $roomRepository;
        $this->maintenanceRepository = $maintenanceRepository;
        $this->alertRepository = $alertRepository;
    }

    public function getDashboardData(): array
    {
        return [
            'statistics' => $this->getStatistics(),
            'recent_alerts' => $this->getRecentAlerts(),
            'upcoming_maintenance' => $this->getUpcomingMaintenance(),
            'offline_cctvs' => $this->getOfflineCctvs(),
        ];
    }

    public function getStatistics(): array
    {
        return [
            'cctv' => $this->cctvRepository->getStatusStatistics(),
            'building' => [
                'total' => Building::count(),
            ],
            'room' => [
                'total' => Room::count(),
            ],
            'user' => [
                'total' => User::count(),
            ],
            'maintenance' => $this->maintenanceRepository->getStatusStatistics(),
            'alert' => $this->alertRepository->getStatusStatistics(),
        ];
    }

    public function getRecentAlerts(int $limit = 5): array
    {
        return $this->alertRepository->recent($limit)->toArray();
    }

    public function getUpcomingMaintenance(int $limit = 5): array
    {
        return $this->maintenanceRepository->upcoming()->take($limit)->toArray();
    }

    public function getOfflineCctvs(int $limit = 5): array
    {
        return $this->cctvRepository->offline()->take($limit)->toArray();
    }

    public function getMapData(): array
    {
        $cctvs = Cctv::with(['building', 'room'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $features = $cctvs->map(function ($cctv) {
            return [
                'type' => 'Feature',
                'properties' => [
                    'id' => $cctv->id,
                    'name' => $cctv->name,
                    'status' => $cctv->status,
                    'status_badge_class' => $cctv->status_badge_class,
                    'building_name' => $cctv->building->name ?? null,
                    'room_name' => $cctv->room->name ?? null,
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $cctv->longitude,
                        (float) $cctv->latitude,
                    ],
                ],
            ];
        });

        return [
            'type' => 'FeatureCollection',
            'features' => $features->toArray(),
        ];
    }
}

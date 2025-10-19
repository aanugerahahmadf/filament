<?php

namespace App\Repositories;

use App\Models\Building;
use App\Models\Cctv;
use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;

class CctvRepository extends BaseRepository
{
    protected function model(): string
    {
        return Cctv::class;
    }

    public function online(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->online()->get();
    }

    public function offline(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->offline()->get();
    }

    public function maintenance(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->maintenance()->get();
    }

    public function byBuilding(Building $building): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('building_id', $building->id)->get();
    }

    public function byRoom(Room $room): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('room_id', $room->id)->get();
    }

    public function search(string $term, int $limit = 15): LengthAwarePaginator
    {
        return $this->model->where('name', 'like', "%{$term}%")
            ->orWhere('ip_rtsp', 'like', "%{$term}%")
            ->orWhere('model', 'like', "%{$term}%")
            ->orWhere('serial_number', 'like', "%{$term}%")
            ->paginate($limit);
    }

    public function getStatusStatistics(): array
    {
        $total = $this->model->count();
        $online = $this->model->online()->count();
        $offline = $this->model->offline()->count();
        $maintenance = $this->model->maintenance()->count();

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

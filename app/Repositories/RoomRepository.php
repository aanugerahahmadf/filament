<?php

namespace App\Repositories;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomRepository extends BaseRepository
{
    protected function model(): string
    {
        return Room::class;
    }

    public function byBuilding(Building $building): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('building_id', $building->id)->get();
    }

    public function withBuilding(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->with('building')->get();
    }

    public function search(string $term, int $limit = 15): LengthAwarePaginator
    {
        return $this->model->where('name', 'like', "%{$term}%")
            ->paginate($limit);
    }
}

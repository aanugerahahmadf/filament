<?php

namespace App\Repositories;

use App\Models\Building;
use Illuminate\Pagination\LengthAwarePaginator;

class BuildingRepository extends BaseRepository
{
    protected function model(): string
    {
        return Building::class;
    }

    public function withStatistics(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->withCount(['rooms', 'cctvs'])->get();
    }

    public function search(string $term, int $limit = 15): LengthAwarePaginator
    {
        return $this->model->where('name', 'like', "%{$term}%")
            ->orWhere('address', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%")
            ->paginate($limit);
    }
}

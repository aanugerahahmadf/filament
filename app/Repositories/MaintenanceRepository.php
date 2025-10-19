<?php

namespace App\Repositories;

use App\Models\Cctv;
use App\Models\Maintenance;
use Illuminate\Pagination\LengthAwarePaginator;

class MaintenanceRepository extends BaseRepository
{
    protected function model(): string
    {
        return Maintenance::class;
    }

    public function scheduled(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->scheduled()->get();
    }

    public function inProgress(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->inProgress()->get();
    }

    public function completed(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->completed()->get();
    }

    public function cancelled(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->cancelled()->get();
    }

    public function byCctv(Cctv $cctv): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('cctv_id', $cctv->id)->get();
    }

    public function upcoming(int $days = 7): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->scheduled()
            ->where('scheduled_at', '<=', now()->addDays($days))
            ->orderBy('scheduled_at')
            ->get();
    }

    public function search(string $term, int $limit = 15): LengthAwarePaginator
    {
        return $this->model->where('description', 'like', "%{$term}%")
            ->orWhere('notes', 'like', "%{$term}%")
            ->paginate($limit);
    }

    public function getStatusStatistics(): array
    {
        $total = $this->model->count();
        $scheduled = $this->model->scheduled()->count();
        $inProgress = $this->model->inProgress()->count();
        $completed = $this->model->completed()->count();
        $cancelled = $this->model->cancelled()->count();

        $costSum = $this->model->sum('cost');

        return [
            'total' => $total,
            'scheduled' => $scheduled,
            'in_progress' => $inProgress,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'total_cost' => $costSum,
        ];
    }
}

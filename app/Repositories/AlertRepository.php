<?php

namespace App\Repositories;

use App\Models\Alert;
use Illuminate\Pagination\LengthAwarePaginator;

class AlertRepository extends BaseRepository
{
    protected function model(): string
    {
        return Alert::class;
    }

    public function active(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->active()->get();
    }

    public function acknowledged(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->acknowledged()->get();
    }

    public function resolved(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->resolved()->get();
    }

    public function suppressed(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->suppressed()->get();
    }

    public function bySeverity(string $severity): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->ofSeverity($severity)->get();
    }

    public function byCategory(string $category): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->ofCategory($category)->get();
    }

    public function recent(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    public function search(string $term, int $limit = 15): LengthAwarePaginator
    {
        return $this->model->where('title', 'like', "%{$term}%")
            ->orWhere('message', 'like', "%{$term}%")
            ->paginate($limit);
    }

    public function getStatusStatistics(): array
    {
        $total = $this->model->count();
        $active = $this->model->active()->count();
        $acknowledged = $this->model->acknowledged()->count();
        $resolved = $this->model->resolved()->count();
        $suppressed = $this->model->suppressed()->count();

        return [
            'total' => $total,
            'active' => $active,
            'acknowledged' => $acknowledged,
            'resolved' => $resolved,
            'suppressed' => $suppressed,
        ];
    }

    public function getSeverityStatistics(): array
    {
        return $this->model->selectRaw('severity, count(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();
    }

    public function getCategoryStatistics(): array
    {
        return $this->model->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();
    }
}

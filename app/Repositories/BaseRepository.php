<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    abstract protected function model(): string;

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->all();
    }

    public function paginate(int $limit = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($limit, $columns);
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function where(string $column, $value): Builder
    {
        return $this->model->where($column, $value);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->findOrFail($id);

        return $model->update($data);
    }

    public function delete(int $id): bool
    {
        $model = $this->findOrFail($id);

        return $model->delete();
    }

    public function firstOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function count(): int
    {
        return $this->model->count();
    }

    public function latest(string $column = 'created_at'): Builder
    {
        return $this->model->latest($column);
    }

    public function oldest(string $column = 'created_at'): Builder
    {
        return $this->model->oldest($column);
    }
}

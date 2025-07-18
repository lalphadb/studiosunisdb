<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository
{
    protected Model $model;
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }
    
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }
    
    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model->fresh();
    }
    
    public function delete(Model $model): bool
    {
        return $model->delete();
    }
    
    protected function applyMultiTenant(Builder $query): Builder
    {
        if (!auth()->user() || auth()->user()->hasRole('superadmin')) {
            return $query;
        }
        
        return $query->where('ecole_id', auth()->user()->ecole_id);
    }
}

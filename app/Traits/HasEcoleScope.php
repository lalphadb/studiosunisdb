<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasEcoleScope
{
    protected static function bootHasEcoleScope()
    {
        // Ajouter automatiquement ecole_id lors de la création
        static::creating(function ($model) {
            if (auth()->check() && !$model->ecole_id && auth()->user()->ecole_id) {
                $model->ecole_id = auth()->user()->ecole_id;
            }
        });

        // Global scope pour filtrer par école (sauf pour superadmin)
        static::addGlobalScope('ecole', function (Builder $builder) {
            if (auth()->check() && !auth()->user()->hasRole('superadmin') && auth()->user()->ecole_id) {
                $builder->where($builder->getModel()->getTable() . '.ecole_id', auth()->user()->ecole_id);
            }
        });
    }

    public function scopeForEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }

    public function scopeWithoutEcoleScope($query)
    {
        return $query->withoutGlobalScope('ecole');
    }
}

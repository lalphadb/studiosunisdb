<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Trait MultiTenant
 * 
 * Gère l'isolation des données par école (ecole_id)
 * Applique automatiquement les filtres multi-tenant
 */
trait MultiTenant
{
    /**
     * Boot du trait - Applique les scopes automatiquement
     */
    protected static function bootMultiTenant(): void
    {
        // Appliquer le scope global pour toutes les requêtes
        static::addGlobalScope('ecole', function (Builder $builder) {
            if (Auth::check() && Auth::user()->ecole_id) {
                $builder->where('ecole_id', Auth::user()->ecole_id);
            }
        });

        // Lors de la création, assigner automatiquement l'ecole_id
        static::creating(function (Model $model) {
            if (Auth::check() && Auth::user()->ecole_id && !$model->ecole_id) {
                $model->ecole_id = Auth::user()->ecole_id;
            }
        });
    }

    /**
     * Scope pour une école spécifique
     */
    public function scopeForEcole(Builder $query, int $ecoleId): Builder
    {
        return $query->where('ecole_id', $ecoleId);
    }

    /**
     * Scope pour ignorer le filtre multi-tenant (SuperAdmin seulement)
     */
    public function scopeWithoutTenant(Builder $query): Builder
    {
        return $query->withoutGlobalScope('ecole');
    }

    /**
     * Relation vers l'école
     */
    public function ecole(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Ecole::class, 'ecole_id');
    }

    /**
     * Vérifier si l'utilisateur peut accéder à cette ressource
     */
    public function canAccess(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        // SuperAdmin peut tout voir
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Vérifier que l'ecole_id correspond
        return $this->ecole_id === $user->ecole_id;
    }

    /**
     * Scope pour les requêtes cross-tenant (SuperAdmin seulement)
     */
    public function scopeAllTenants(Builder $query): Builder
    {
        if (Auth::check() && Auth::user()->hasRole('superadmin')) {
            return $query->withoutGlobalScope('ecole');
        }
        
        return $query;
    }
}

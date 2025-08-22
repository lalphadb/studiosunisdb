<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToEcole
{
    /**
     * Boot the trait to add global scope
     */
    protected static function bootBelongsToEcole(): void
    {
        // Ajouter automatiquement ecole_id lors de la création
        static::creating(function ($model) {
            if (auth()->check() && !isset($model->ecole_id)) {
                $model->ecole_id = auth()->user()->ecole_id;
            }
        });

        // Appliquer le scope global pour filtrer par ecole_id
        static::addGlobalScope('ecole', function (Builder $builder) {
            if (auth()->check() && auth()->user()->ecole_id) {
                $builder->where('ecole_id', auth()->user()->ecole_id);
            }
        });
    }

    /**
     * Scope pour ignorer le filtrage par école (pour superadmin)
     */
    public function scopeWithoutEcoleScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('ecole');
    }

    /**
     * Scope pour une école spécifique
     */
    public function scopeForEcole(Builder $query, $ecoleId): Builder
    {
        return $query->withoutGlobalScope('ecole')
            ->where('ecole_id', $ecoleId);
    }

    /**
     * Vérifier si l'utilisateur actuel peut accéder à cet enregistrement
     */
    public function userCanAccess(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();
        
        // Superadmin peut tout voir
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Sinon, vérifier que l'ecole_id correspond
        return $this->ecole_id === $user->ecole_id;
    }
}

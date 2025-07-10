<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasEcoleScope
{
    /**
     * Boot the trait
     */
    protected static function bootHasEcoleScope()
    {
        // Ajouter automatiquement ecole_id lors de la création
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->ecole_id && !$model->ecole_id) {
                $model->ecole_id = Auth::user()->ecole_id;
            }
        });

        // Appliquer automatiquement le scope pour les non-superadmins
        static::addGlobalScope('ecole', function (Builder $builder) {
            if (Auth::check() && !Auth::user()->hasRole('superadmin')) {
                $builder->where('ecole_id', Auth::user()->ecole_id);
            }
        });
    }

    /**
     * Scope pour filtrer par école
     */
    public function scopeForEcole(Builder $query, $ecoleId): Builder
    {
        return $query->where('ecole_id', $ecoleId);
    }

    /**
     * Relation avec l'école
     */
    public function ecole()
    {
        return $this->belongsTo(\App\Models\Ecole::class);
    }

    /**
     * Vérifier si l'enregistrement appartient à une école spécifique
     */
    public function belongsToEcole($ecoleId): bool
    {
        return $this->ecole_id == $ecoleId;
    }

    /**
     * Vérifier si l'utilisateur actuel peut accéder à cet enregistrement
     */
    public function canBeAccessedByCurrentUser(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        // Les superadmins peuvent tout voir
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Les autres utilisateurs ne peuvent voir que leur école
        return $this->ecole_id === $user->ecole_id;
    }
}

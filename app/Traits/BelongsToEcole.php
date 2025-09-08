<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * Trait BelongsToEcole
 *
 * TEMPORAIREMENT DÉSACTIVÉ - Causait boucles infinies avec User model
 * TODO: Réimplémenter avec protection contre récursion
 */
trait BelongsToEcole
{
    /**
     * DÉSACTIVÉ - Boot le trait pour ajouter le global scope
     */
    protected static function bootBelongsToEcole(): void
    {
        // GLOBAL SCOPE DÉSACTIVÉ TEMPORAIREMENT
        // Causait boucle infinie lors de Auth::user() -> User model -> Global scope -> Auth::user()

        /*
        static::addGlobalScope('ecole', function (Builder $builder) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user(); // ← BOUCLE INFINIE ICI

            if ($user->hasRole('superadmin')) {
                return;
            }

            $table = $builder->getModel()->getTable();
            if (!Schema::hasColumn($table, 'ecole_id')) {
                return;
            }

            $builder->where($table . '.ecole_id', $user->ecole_id);
        });
        */

        // CREATING HOOK SÉCURISÉ
        static::creating(function ($model) {
            if (! Auth::check()) {
                return;
            }

            // Éviter récursion pour le modèle User lui-même
            if ($model instanceof \App\Models\User) {
                return;
            }

            try {
                $user = Auth::user();

                if ($user && ! $user->hasRole('superadmin') && empty($model->ecole_id)) {
                    $model->ecole_id = $user->ecole_id ?? static::getDefaultEcoleId();
                }
            } catch (\Exception $e) {
                // Ignorer erreurs pendant migration/bootstrap
                if (empty($model->ecole_id)) {
                    $model->ecole_id = static::getDefaultEcoleId();
                }
            }
        });
    }

    /**
     * Relation avec l'école
     */
    public function ecole()
    {
        return $this->belongsTo(\App\Models\Ecole::class);
    }

    /**
     * Scope pour filtrer par école spécifique
     */
    public function scopeForEcole(Builder $query, int $ecoleId): Builder
    {
        return $query->where('ecole_id', $ecoleId);
    }

    /**
     * Scope pour la même école que l'utilisateur courant SÉCURISÉ
     */
    public function scopeSameEcole(Builder $query): Builder
    {
        if (! Auth::check()) {
            return $query;
        }

        try {
            $user = Auth::user();

            if (! $user || $user->hasRole('superadmin')) {
                return $query;
            }

            return $query->where('ecole_id', $user->ecole_id);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner query sans filtre
            return $query;
        }
    }

    /**
     * Vérifier si le modèle appartient à la même école que l'utilisateur
     */
    public function belongsToUserEcole(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        try {
            $user = Auth::user();

            if (! $user) {
                return false;
            }

            if ($user->hasRole('superadmin')) {
                return true;
            }

            return $this->ecole_id === $user->ecole_id;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtenir l'ID de l'école par défaut
     */
    protected static function getDefaultEcoleId(): ?int
    {
        try {
            return \App\Models\Ecole::where('est_active', true)
                ->orderBy('id')
                ->value('id') ?? 1; // Fallback école ID 1
        } catch (\Exception $e) {
            return 1; // Fallback par défaut
        }
    }

    /**
     * Désactiver temporairement le scope école (pour les superadmins)
     */
    public static function withoutEcoleScope(): Builder
    {
        return static::withoutGlobalScope('ecole');
    }

    /**
     * NOUVELLE MÉTHODE: Appliquer filtrage école manuellement (sans global scope)
     */
    public static function scopedByCurrentUserEcole(): Builder
    {
        $query = static::query();

        if (! Auth::check()) {
            return $query;
        }

        try {
            $user = Auth::user();

            if (! $user || $user->hasRole('superadmin')) {
                return $query;
            }

            return $query->where('ecole_id', $user->ecole_id);
        } catch (\Exception $e) {
            return $query;
        }
    }
}

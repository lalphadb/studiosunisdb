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
        // Appliquer le scope global seulement dans certaines conditions
        static::addGlobalScope('ecole', function (Builder $builder) {
            // Ne pas appliquer le scope en console ou pendant le boot
            if (app()->runningInConsole()) {
                return;
            }

            // Ne pas appliquer le scope si on est en train de charger l'utilisateur lui-même
            if ($builder->getModel() instanceof \App\Models\User) {
                return;
            }

            // Vérifier si nous avons un utilisateur en session (pas via auth() pour éviter la récursion)
            $user = null;
            if (session()->has('ecole_id')) {
                // Utiliser l'école de la session si disponible
                $builder->where($builder->getModel()->getTable() . '.ecole_id', session('ecole_id'));
                return;
            }

            // Si nous avons un utilisateur authentifié
            if (Auth::check()) {
                $user = Auth::user();
                
                // Si l'utilisateur n'est pas super-admin, filtrer par école
                if ($user && method_exists($user, 'hasRole') && !$user->hasRole('super-admin')) {
                    $builder->where($builder->getModel()->getTable() . '.ecole_id', $user->ecole_id);
                    
                    // Stocker en session pour éviter les requêtes répétées
                    session(['ecole_id' => $user->ecole_id]);
                }
            }
        });
    }

    /**
     * Scope pour filtrer par école
     */
    public function scopeForEcole(Builder $query, $ecoleId)
    {
        return $query->where($this->getTable() . '.ecole_id', $ecoleId);
    }

    /**
     * Désactiver temporairement le scope école
     */
    public function scopeWithoutEcoleScope(Builder $query)
    {
        return $query->withoutGlobalScope('ecole');
    }
}

<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasEcoleScope
{
    /**
     * Boot du trait - applique automatiquement le scope école
     */
    protected static function bootHasEcoleScope(): void
    {
        // Applique le scope uniquement si l'utilisateur n'est pas super-admin
        static::addGlobalScope('ecole', function (Builder $builder) {
            $user = auth()->user();
            
            // Si pas d'utilisateur connecté, pas de scope
            if (!$user) {
                return;
            }
            
            // Si super-admin, bypass le scope (voit toutes les écoles)
            if ($user->hasRole('super-admin')) {
                return;
            }
            
            // Sinon, filtrer par école de l'utilisateur
            $builder->where('ecole_id', $user->ecole_id);
        });
        
        // Auto-assignment de l'ecole_id à la création
        static::creating(function (Model $model) {
            $user = auth()->user();
            
            if ($user && empty($model->ecole_id)) {
                $model->ecole_id = $user->ecole_id;
            }
        });
    }
    
    /**
     * Relation vers l'école
     */
    public function ecole()
    {
        return $this->belongsTo(\App\Models\Ecole::class);
    }
    
    /**
     * Scope pour forcer une école spécifique (pour super-admin)
     */
    public function scopeForEcole(Builder $query, int $ecoleId): Builder
    {
        return $query->withoutGlobalScope('ecole')->where('ecole_id', $ecoleId);
    }
    
    /**
     * Scope pour voir toutes les écoles (pour super-admin)
     */
    public function scopeAllEcoles(Builder $query): Builder
    {
        return $query->withoutGlobalScope('ecole');
    }
}

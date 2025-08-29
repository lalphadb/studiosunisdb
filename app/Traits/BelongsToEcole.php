<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * Trait BelongsToEcole - VERSION DEBUG DÉSACTIVÉE TEMPORAIREMENT
 */
trait BelongsToEcole
{
    /**
     * Boot the trait - SCOPING DÉSACTIVÉ POUR DEBUG
     */
    protected static function bootBelongsToEcole()
    {
        // TEMPORAIRE: Scoping désactivé pour résoudre 403
        Log::info("BelongsToEcole: Global scope DÉSACTIVÉ temporairement pour debug");
        
        // Ne pas appliquer de Global Scope pour l'instant
        // static::addGlobalScope('ecole', function ($query) { ... });
        
        // Ajouter automatiquement ecole_id lors de la création
        static::creating(function ($model) {
            if (auth()->check() && !$model->ecole_id) {
                $user = auth()->user();
                if (!$user->hasRole('superadmin') && $user->ecole_id) {
                    $model->ecole_id = $user->ecole_id;
                }
            }
        });
    }
    
    public function ecole()
    {
        return $this->belongsTo(\App\Models\Ecole::class);
    }
    
    public function scopeWithoutEcoleScope($query)
    {
        return $query; // Pas de scoping à retirer
    }
    
    public function scopeForEcole($query, $ecoleId)
    {
        return $query->where($this->getTable() . '.ecole_id', $ecoleId);
    }
}

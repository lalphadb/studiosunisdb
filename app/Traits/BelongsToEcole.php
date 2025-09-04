<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * Trait BelongsToEcole
 * 
 * Applique automatiquement le scoping par école sur les modèles.
 * Utilisé pour l'architecture mono-école avec support multi-écoles futur.
 */
trait BelongsToEcole
{
    /**
     * Boot le trait pour ajouter le global scope
     */
    protected static function bootBelongsToEcole(): void
    {
        // Ajouter le scope global seulement si l'utilisateur n'est pas superadmin
        static::addGlobalScope('ecole', function (Builder $builder) {
            // Ne pas appliquer le scope si:
            // 1. Pas d'utilisateur connecté (ex: commandes artisan)
            // 2. L'utilisateur est superadmin
            // 3. La table n'a pas de colonne ecole_id
            
            if (!Auth::check()) {
                return;
            }
            
            $user = Auth::user();
            
            // Les superadmins voient tout
            if ($user->hasRole('superadmin')) {
                return;
            }
            
            // Vérifier que la colonne existe avant d'appliquer le filtre
            $table = $builder->getModel()->getTable();
            if (!Schema::hasColumn($table, 'ecole_id')) {
                return;
            }
            
            // Appliquer le filtre par école
            $builder->where($table . '.ecole_id', $user->ecole_id);
        });
        
        // Lors de la création, définir automatiquement ecole_id
        static::creating(function ($model) {
            if (!Auth::check()) {
                return;
            }
            
            $user = Auth::user();
            
            // Ne pas forcer ecole_id pour les superadmins
            if ($user->hasRole('superadmin')) {
                // Si ecole_id n'est pas défini, utiliser l'école par défaut
                if (empty($model->ecole_id)) {
                    $model->ecole_id = static::getDefaultEcoleId();
                }
                return;
            }
            
            // Pour les autres utilisateurs, forcer leur école
            $model->ecole_id = $user->ecole_id;
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
     * Scope pour la même école que l'utilisateur courant
     */
    public function scopeSameEcole(Builder $query): Builder
    {
        if (!Auth::check()) {
            return $query;
        }
        
        $user = Auth::user();
        
        if ($user->hasRole('superadmin')) {
            return $query;
        }
        
        return $query->where('ecole_id', $user->ecole_id);
    }
    
    /**
     * Vérifier si le modèle appartient à la même école que l'utilisateur
     */
    public function belongsToUserEcole(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Les superadmins ont accès à tout
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        return $this->ecole_id === $user->ecole_id;
    }
    
    /**
     * Obtenir l'ID de l'école par défaut
     */
    protected static function getDefaultEcoleId(): ?int
    {
        // Retourner l'ID de la première école active
        return \App\Models\Ecole::where('est_active', true)
            ->orderBy('id')
            ->value('id');
    }
    
    /**
     * Désactiver temporairement le scope école (pour les superadmins)
     */
    public static function withoutEcoleScope(): Builder
    {
        return static::withoutGlobalScope('ecole');
    }
}

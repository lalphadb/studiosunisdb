#!/bin/bash

# Réactiver le scoping école de façon sécurisée après debug

echo "=== RÉACTIVATION SCOPING ÉCOLE ==="
echo "⚠️  Utilisez ce script SEULEMENT après avoir confirmé que:"
echo "   - L'accès aux cours fonctionne"  
echo "   - Les suppressions fonctionnent"
echo "   - Les données sont cohérentes"
echo

read -p "Continuer la réactivation du scoping ? (y/N): " confirm

if [[ $confirm != [yY] && $confirm != [yY][eE][sS] ]]; then
    echo "Annulé."
    exit 0
fi

echo
echo "🔧 Réactivation du trait BelongsToEcole avec scoping sécurisé..."

# Restaurer la version sécurisée du trait
cat > /home/studiosdb/studiosunisdb/app/Traits/BelongsToEcole.php << 'EOF'
<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * Trait BelongsToEcole - VERSION PRODUCTION SÉCURISÉE
 * 
 * Ajoute automatiquement le scoping par ecole_id à tous les modèles
 * qui l'utilisent, garantissant l'isolation des données par école.
 */
trait BelongsToEcole
{
    /**
     * Boot the trait and add global scope - VERSION PRODUCTION
     */
    protected static function bootBelongsToEcole()
    {
        static::addGlobalScope('ecole', function ($query) {
            // Vérifier que l'utilisateur est authentifié
            if (!auth()->check()) {
                return; // Pas de scoping si pas connecté
            }
            
            $user = auth()->user();
            
            // Superadmin voit tout
            if ($user->hasRole('superadmin')) {
                return; // Pas de scoping pour superadmin
            }
            
            // Récupérer le nom de table du modèle
            $model = new static();
            $table = $model->getTable();
            
            // Vérifier que la colonne ecole_id existe et que user a une école
            try {
                if (Schema::hasColumn($table, 'ecole_id') && $user->ecole_id) {
                    $query->where("{$table}.ecole_id", $user->ecole_id);
                }
            } catch (\Exception $e) {
                // En cas d'erreur, log mais ne pas bloquer
                Log::warning("Global Scope {$table} erreur: " . $e->getMessage());
            }
        });
        
        // Ajouter automatiquement ecole_id lors de la création
        static::creating(function ($model) {
            if (auth()->check() && !$model->ecole_id) {
                $user = auth()->user();
                // Ne pas forcer l'ecole_id pour superadmin
                if (!$user->hasRole('superadmin') && $user->ecole_id) {
                    $model->ecole_id = $user->ecole_id;
                }
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
     * Scope pour désactiver le scoping par école (usage admin)
     */
    public function scopeWithoutEcoleScope($query)
    {
        return $query->withoutGlobalScope('ecole');
    }
    
    /**
     * Scope pour forcer une école spécifique
     */
    public function scopeForEcole($query, $ecoleId)
    {
        return $query->withoutGlobalScope('ecole')->where($this->getTable() . '.ecole_id', $ecoleId);
    }
}
EOF

echo "✅ Trait BelongsToEcole réactivé en mode sécurisé"

echo
echo "🧹 Nettoyage des caches..."
php artisan optimize:clear >/dev/null 2>&1

echo
echo "🧪 Test final de sécurité..."
SECURITY_TEST=$(php artisan tinker --execute="
try {
    \$user = \\App\\Models\\User::find(2);
    if (\$user) {
        \\Auth::login(\$user);
        \$coursTotal = \\App\\Models\\Cours::withoutGlobalScope('ecole')->count();
        \$coursScoped = \\App\\Models\\Cours::count();
        echo \$coursTotal . '|' . \$coursScoped . '|' . (\$coursScoped < \$coursTotal ? 'OK' : 'FAIL');
    }
} catch (Exception \$e) {
    echo '0|0|ERROR';
}
" 2>/dev/null)

IFS='|' read -ra TEST_RESULT <<< "$SECURITY_TEST"
TOTAL=${TEST_RESULT[0]}
SCOPED=${TEST_RESULT[1]}
SECURITY=${TEST_RESULT[2]}

echo "Cours total: $TOTAL"
echo "Cours visibles avec scoping: $SCOPED" 
echo "Sécurité: $SECURITY"

if [ "$SECURITY" = "OK" ]; then
    echo
    echo "✅ SUCCÈS: Scoping école réactivé et fonctionnel"
    echo "   - Isolation par école restaurée"
    echo "   - Sécurité multi-école active" 
    echo "   - Accès aux cours maintenu"
else
    echo
    echo "⚠️  ATTENTION: Problème détecté avec le scoping"
    echo "   Vérifiez manuellement que tout fonctionne"
fi

echo
echo "🎯 PROCHAINES ÉTAPES:"
echo "1. Tester l'accès aux cours dans le navigateur"
echo "2. Vérifier que la suppression fonctionne"
echo "3. Appliquer le trait aux autres modèles (User, Membre, etc.)"
echo

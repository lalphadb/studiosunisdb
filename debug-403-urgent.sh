#!/bin/bash

# StudiosDB - Diagnostic URGENT erreur 403 cours
# Debug complet pour résoudre problème accès cours malgré corrections

echo "=== DIAGNOSTIC URGENT 403 COURS ==="
echo "Date: $(date)"
echo "Problème: Impossible accéder/supprimer cours malgré trait BelongsToEcole"
echo

# 1. ÉTAPE CRITIQUE : Vider TOUS les caches
echo "🧹 1. NETTOYAGE COMPLET DES CACHES..."
php artisan optimize:clear
php artisan config:clear  
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo "✅ Tous les caches vidés"
echo

# 2. Vérifier les données de base
echo "📊 2. VÉRIFICATION DONNÉES BASE..."
php artisan tinker --execute="
try {
    echo '=== DONNÉES COURS ===' . PHP_EOL;
    \$cours1 = \\App\\Models\\Cours::withoutGlobalScope('ecole')->find(1);
    if (\$cours1) {
        echo 'Cours ID 1: ' . \$cours1->nom . PHP_EOL;
        echo 'École ID: ' . \$cours1->ecole_id . PHP_EOL;
    } else {
        echo 'Cours ID 1 introuvable' . PHP_EOL;
    }
    
    echo PHP_EOL . '=== DONNÉES UTILISATEUR ===' . PHP_EOL;
    \$user = \\App\\Models\\User::find(2); // louis@th.ca
    if (\$user) {
        echo 'User: ' . \$user->email . PHP_EOL;
        echo 'École ID: ' . \$user->ecole_id . PHP_EOL;
        echo 'Rôles: ' . \$user->getRoleNames()->implode(', ') . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur données: ' . \$e->getMessage() . PHP_EOL;
}
"

echo

# 3. Test du trait et scoping
echo "🔧 3. TEST TRAIT ET SCOPING..."
php artisan tinker --execute="
try {
    echo '=== TEST TRAIT ===' . PHP_EOL;
    \$traits = class_uses(\\App\\Models\\Cours::class);
    echo 'BelongsToEcole utilisé: ' . (in_array('App\\\\Traits\\\\BelongsToEcole', \$traits) ? 'OUI' : 'NON') . PHP_EOL;
    
    echo PHP_EOL . '=== TEST SCOPING ===' . PHP_EOL;
    \$user = \\App\\Models\\User::find(2);
    if (\$user) {
        \\Auth::login(\$user);
        
        echo 'Cours total: ' . \\App\\Models\\Cours::withoutGlobalScope('ecole')->count() . PHP_EOL;
        echo 'Cours avec scoping: ' . \\App\\Models\\Cours::count() . PHP_EOL;
        
        \$cours1Direct = \\App\\Models\\Cours::find(1);
        echo 'Cours 1 visible avec scoping: ' . (\$cours1Direct ? 'OUI' : 'NON') . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur trait/scoping: ' . \$e->getMessage() . PHP_EOL;
}
"

echo

# 4. Test des permissions Policy
echo "🔐 4. TEST PERMISSIONS POLICY..."
php artisan tinker --execute="
try {
    echo '=== TEST POLICY ===' . PHP_EOL;
    \$user = \\App\\Models\\User::find(2);
    \$cours1 = \\App\\Models\\Cours::withoutGlobalScope('ecole')->find(1);
    
    if (\$user && \$cours1) {
        \\Auth::login(\$user);
        
        echo 'Peut voir tous cours: ' . (\$user->can('viewAny', \\App\\Models\\Cours::class) ? 'OUI' : 'NON') . PHP_EOL;
        echo 'Peut voir cours 1: ' . (\$user->can('view', \$cours1) ? 'OUI' : 'NON') . PHP_EOL;
        echo 'Peut modifier cours 1: ' . (\$user->can('update', \$cours1) ? 'OUI' : 'NON') . PHP_EOL;
        echo 'Peut supprimer cours 1: ' . (\$user->can('delete', \$cours1) ? 'OUI' : 'NON') . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur policy: ' . \$e->getMessage() . PHP_EOL;
}
"

echo

# 5. Diagnostic PROBLÈME SPÉCIFIQUE
echo "🚨 5. DIAGNOSTIC PROBLÈME SPÉCIFIQUE..."
php artisan tinker --execute="
try {
    echo '=== PROBLÈME IDENTIFIÉ ===' . PHP_EOL;
    \$user = \\App\\Models\\User::find(2);
    \$cours1NoScope = \\App\\Models\\Cours::withoutGlobalScope('ecole')->find(1);
    
    if (\$user && \$cours1NoScope) {
        \\Auth::login(\$user);
        
        echo 'User école: ' . \$user->ecole_id . PHP_EOL;
        echo 'Cours 1 école: ' . \$cours1NoScope->ecole_id . PHP_EOL;
        echo 'École match: ' . (\$user->ecole_id == \$cours1NoScope->ecole_id ? 'OUI' : 'NON') . PHP_EOL;
        
        // Test route model binding
        \$coursViaScoping = \\App\\Models\\Cours::where('id', 1)->first();
        echo 'Cours 1 trouvé via scoping: ' . (\$coursViaScoping ? 'OUI - ID:' . \$coursViaScoping->id : 'NON') . PHP_EOL;
        
        if (!\$coursViaScoping) {
            echo '❌ PROBLÈME: Le scoping empêche de trouver le cours ID 1' . PHP_EOL;
            echo '   Solution: Corriger ecole_id dans table cours OU désactiver temporairement scoping' . PHP_EOL;
        }
    }
} catch (Exception \$e) {
    echo 'Erreur diagnostic: ' . \$e->getMessage() . PHP_EOL;
}
"

echo

echo "=== ACTIONS RECOMMANDÉES ==="
echo "Si cours 1 école ≠ user école :"
echo "  UPDATE cours SET ecole_id = 1 WHERE id = 1;"
echo
echo "Si scoping trop restrictif temporairement :"
echo "  Modifier trait BelongsToEcole pour debug"
echo
echo "Redémarrer serveur après modifications :"
echo "  php artisan serve --port=8001"
echo

echo "🔍 Diagnostic terminé - $(date)"

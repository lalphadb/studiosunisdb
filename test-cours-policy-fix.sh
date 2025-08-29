#!/bin/bash

# Script de test pour validation correction Policy Cours
# Vérifie scoping ecole_id et permissions après correction

echo "=== TEST CORRECTION COURS POLICY ==="
echo "Date: $(date)"
echo

# 1. Vérifier structure base de données
echo "📋 1. Vérification structure base de données..."
php artisan tinker --execute="
try {
    echo 'Table cours existe: ' . (\\Schema::hasTable('cours') ? 'OUI' : 'NON') . PHP_EOL;
    if (\\Schema::hasTable('cours')) {
        echo 'Colonne ecole_id existe: ' . (\\Schema::hasColumn('cours', 'ecole_id') ? 'OUI' : 'NON') . PHP_EOL;
        echo 'Nombre total de cours: ' . \\App\\Models\\Cours::withoutGlobalScope('ecole')->count() . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur: ' . \$e->getMessage() . PHP_EOL;
}
"

echo

# 2. Tester le trait BelongsToEcole
echo "🔧 2. Test du trait BelongsToEcole..."
php artisan tinker --execute="
try {
    \$traits = class_uses(\\App\\Models\\Cours::class);
    echo 'Trait BelongsToEcole utilisé: ' . (in_array('App\\\\Traits\\\\BelongsToEcole', \$traits) ? 'OUI' : 'NON') . PHP_EOL;
} catch (Exception \$e) {
    echo 'Erreur trait: ' . \$e->getMessage() . PHP_EOL;
}
"

echo

# 3. Test permissions utilisateur
echo "🔐 3. Test des permissions utilisateur..."
php artisan tinker --execute="
try {
    \$user = \\App\\Models\\User::find(2); // louis@th.ca selon diagnostic
    if (\$user) {
        echo 'Utilisateur: ' . \$user->email . PHP_EOL;
        echo 'École ID: ' . \$user->ecole_id . PHP_EOL;
        echo 'Rôles: ' . \$user->getRoleNames()->implode(', ') . PHP_EOL;
        echo 'Peut voir cours: ' . (\$user->can('viewAny', \\App\\Models\\Cours::class) ? 'OUI' : 'NON') . PHP_EOL;
        echo 'Peut créer cours: ' . (\$user->can('create', \\App\\Models\\Cours::class) ? 'OUI' : 'NON') . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur utilisateur: ' . \$e->getMessage() . PHP_EOL;
}
"

echo

# 4. Test scoping par école
echo "🏫 4. Test du scoping par école..."
php artisan tinker --execute="
try {
    // Simuler l'utilisateur connecté
    \$user = \\App\\Models\\User::find(2);
    if (\$user) {
        \\Auth::login(\$user);
        
        echo 'Cours visibles avec scoping: ' . \\App\\Models\\Cours::count() . PHP_EOL;
        echo 'Cours totaux sans scoping: ' . \\App\\Models\\Cours::withoutGlobalScope('ecole')->count() . PHP_EOL;
        
        // Test cours spécifique
        \$cours4 = \\App\\Models\\Cours::withoutGlobalScope('ecole')->find(4);
        if (\$cours4) {
            echo 'Cours 4 existe, école: ' . \$cours4->ecole_id . PHP_EOL;
            echo 'Visible avec scoping: ' . (\\App\\Models\\Cours::where('id', 4)->exists() ? 'OUI' : 'NON') . PHP_EOL;
            echo 'Permission update: ' . (\$user->can('update', \$cours4) ? 'OUI' : 'NON') . PHP_EOL;
            echo 'Permission delete: ' . (\$user->can('delete', \$cours4) ? 'OUI' : 'NON') . PHP_EOL;
        } else {
            echo 'Cours 4 n\'existe pas' . PHP_EOL;
        }
    }
} catch (Exception \$e) {
    echo 'Erreur scoping: ' . \$e->getMessage() . PHP_EOL;
}
"

echo

# 5. Test routes et Controller
echo "🌐 5. Test des routes et Controller..."
echo "Routes cours disponibles:"
php artisan route:list | grep cours | head -10

echo
echo "=== RÉSUMÉ ==="
echo "✅ Si tous les tests passent, l'erreur 403 devrait être résolue"
echo "✅ Utilisateur ne voit que les cours de son école"  
echo "✅ Permissions basées sur Policy + scoping"
echo
echo "⚠️  Si problèmes persistent:"
echo "   1. Vérifier données cours.ecole_id correspondent à users.ecole_id"
echo "   2. Tester avec: php artisan optimize:clear"
echo "   3. Redémarrer serveur dev si nécessaire"
echo
echo "🧪 Test terminé - $(date)"

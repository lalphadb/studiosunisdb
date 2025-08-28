#!/bin/bash

# ============================================================
# StudiosDB - Tests Module Cours
# ============================================================

echo "🧪 === TESTS MODULE COURS ==="

# 1. Tests routes
echo "🌐 1. Test routes cours..."
curl -s -I http://127.0.0.1:8001/cours | head -1
curl -s -I http://127.0.0.1:8001/planning | head -1

# 2. Tests base de données
echo "📊 2. Tests base de données..."
php artisan tinker --execute="
echo 'Structure Table cours:';
try {
    \$columns = \Schema::getColumnListing('cours');
    echo '  - Colonnes: ' . implode(', ', \$columns);
    echo '  - ecole_id: ' . (in_array('ecole_id', \$columns) ? '✅' : '❌');
    echo '  - instructeur_id: ' . (in_array('instructeur_id', \$columns) ? '✅' : '❌');
} catch (\Exception \$e) {
    echo '❌ Erreur table: ' . \$e->getMessage();
}
"

# 3. Tests modèles
echo "🏗️ 3. Tests modèles..."
php artisan tinker --execute="
try {
    echo 'Modèles:';
    echo '  - Cours total: ' . \App\Models\Cours::withoutGlobalScopes()->count();
    echo '  - Cours visibles (avec scope): ' . \App\Models\Cours::count();
    echo '  - Cours actifs: ' . \App\Models\Cours::where('actif', true)->count();
    
    \$cours = \App\Models\Cours::first();
    if (\$cours) {
        echo '  - Premier cours: ' . \$cours->nom;
        echo '  - Instructeur: ' . (\$cours->instructeur ? \$cours->instructeur->name : 'Non assigné');
        echo '  - École: ' . (\$cours->ecole ? \$cours->ecole->nom : 'Non assignée');
    }
} catch (\Exception \$e) {
    echo '❌ Erreur modèles: ' . \$e->getMessage();
}
"

# 4. Tests relations
echo "🔗 4. Tests relations..."
php artisan tinker --execute="
try {
    \$cours = \App\Models\Cours::with(['instructeur', 'ecole'])->first();
    if (\$cours) {
        echo 'Relations cours ID ' . \$cours->id . ':';
        echo '  - Instructeur: ' . (\$cours->instructeur ? 'OK' : 'NULL (attendu)');
        echo '  - École: ' . (\$cours->ecole ? 'OK' : 'ERREUR');
        echo '  - Horaire complet: ' . \$cours->horaire_complet;
        echo '  - Places disponibles: ' . \$cours->places_disponibles;
    }
} catch (\Exception \$e) {
    echo '❌ Erreur relations: ' . \$e->getMessage();
}
"

# 5. Tests policies
echo "🔐 5. Tests policies..."
php artisan tinker --execute="
try {
    \$user = \App\Models\User::withoutGlobalScopes()->first();
    if (\$user) {
        echo 'Policies pour user ' . \$user->email . ':';
        echo '  - Voir cours: ' . (\$user->can('viewAny', \App\Models\Cours::class) ? '✅' : '❌');
        echo '  - Créer cours: ' . (\$user->can('create', \App\Models\Cours::class) ? '✅' : '❌');
        echo '  - Exporter cours: ' . (\$user->can('export', \App\Models\Cours::class) ? '✅' : '❌');
        echo '  - Rôles: ' . implode(', ', \$user->getRoleNames()->toArray());
    }
} catch (\Exception \$e) {
    echo '❌ Erreur policies: ' . \$e->getMessage();
}
"

# 6. Tests accessors
echo "📐 6. Tests accessors..."
php artisan tinker --execute="
try {
    \$cours = \App\Models\Cours::first();
    if (\$cours) {
        echo 'Accessors cours \"' . \$cours->nom . '\":';
        echo '  - Places disponibles: ' . \$cours->places_disponibles . '/' . \$cours->places_max;
        echo '  - Taux remplissage: ' . \$cours->taux_remplissage . '%';
        echo '  - Statut inscription: ' . \$cours->statut_inscription;
        echo '  - Prochaine séance: ' . \$cours->prochaine_seance?->format('Y-m-d H:i');
    }
} catch (\Exception \$e) {
    echo '❌ Erreur accessors: ' . \$e->getMessage();
}
"

# 7. Tests scopes
echo "🔍 7. Tests scopes..."
php artisan tinker --execute="
try {
    echo 'Scopes:';
    echo '  - Actifs: ' . \App\Models\Cours::actif()->count();
    echo '  - Niveau débutant: ' . \App\Models\Cours::niveau('debutant')->count();
    echo '  - Pour âge 8 ans: ' . \App\Models\Cours::pourAge(8)->count();
    echo '  - Avec places dispo: ' . \App\Models\Cours::avecPlacesDisponibles()->count();
} catch (\Exception \$e) {
    echo '❌ Erreur scopes: ' . \$e->getMessage();
}
"

# 8. Tests composants UI
echo "🎨 8. Tests composants UI..."
test -f resources/js/Components/StatsCard.vue && echo "  - StatsCard: ✅" || echo "  - StatsCard: ❌"
test -f resources/js/Components/StatCard.vue && echo "  - StatCard: ✅" || echo "  - StatCard: ❌"
test -f resources/js/Pages/Cours/Index.vue && echo "  - Cours/Index: ✅" || echo "  - Cours/Index: ❌"
test -f resources/js/Pages/Cours/Show.vue && echo "  - Cours/Show: ✅" || echo "  - Cours/Show: ❌"
test -f resources/js/Pages/Dashboard/Membre.vue && echo "  - Dashboard/Membre: ✅" || echo "  - Dashboard/Membre: ❌"

# 9. Tests migrations
echo "📄 9. État migrations..."
php artisan migrate:status | grep cours

echo "✅ === TESTS TERMINÉS ==="

# 10. Résumé final
echo "📋 === RÉSUMÉ ==="
php artisan tinker --execute="
echo 'ÉTAT FINAL MODULE COURS:';
echo '  🏫 Écoles: ' . \App\Models\Ecole::count();
echo '  👨‍🏫 Instructeurs: ' . \App\Models\User::withoutGlobalScopes()->role('instructeur')->count();
echo '  🥋 Cours total: ' . \App\Models\Cours::withoutGlobalScopes()->count();
echo '  🎯 Cours visibles: ' . \App\Models\Cours::count();
echo '  ✅ Cours actifs: ' . \App\Models\Cours::actif()->count();
echo '  ⚠️  Sans instructeur: ' . \App\Models\Cours::whereNull('instructeur_id')->count();
"

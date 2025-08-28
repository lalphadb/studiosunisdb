#!/bin/bash

# ============================================================
# StudiosDB - Réparation CRITIQUE users.ecole_id
# ============================================================

echo "🚨 === RÉPARATION CRITIQUE MODULE USERS ==="

# 1. Migration users.ecole_id
echo "📄 1. Migration users.ecole_id..."
php artisan migrate --force

# 2. Vérification colonnes critiques
echo "🔍 2. Vérification colonnes..."
php artisan tinker --execute="
echo 'Vérifications post-migration:';
echo 'users.ecole_id: ' . (\Schema::hasColumn('users','ecole_id') ? 'PRÉSENT ✅' : 'ABSENT ❌');
echo 'cours.ecole_id: ' . (\Schema::hasColumn('cours','ecole_id') ? 'PRÉSENT ✅' : 'ABSENT ❌');
echo 'cours.instructeur_id: ' . (\Schema::hasColumn('cours','instructeur_id') ? 'PRÉSENT ✅' : 'ABSENT ❌');
"

# 3. Population données users si nécessaire
echo "👥 3. Population données users..."
php artisan tinker --execute="
\$ecole = \App\Models\Ecole::first();
if (\$ecole) {
    \$usersNull = \App\Models\User::withoutGlobalScopes()->whereNull('ecole_id')->count();
    if (\$usersNull > 0) {
        \App\Models\User::withoutGlobalScopes()->whereNull('ecole_id')->update(['ecole_id' => \$ecole->id]);
        echo '👥 ' . \$usersNull . ' utilisateurs liés à école: ' . \$ecole->nom;
    } else {
        echo '👥 Tous les utilisateurs ont déjà une école assignée';
    }
} else {
    echo '❌ Aucune école trouvée';
}
"

# 4. Population données cours si nécessaire
echo "🥋 4. Population données cours..."
php artisan tinker --execute="
\$ecole = \App\Models\Ecole::first();
if (\$ecole) {
    \$coursNull = \App\Models\Cours::withoutGlobalScopes()->whereNull('ecole_id')->count();
    if (\$coursNull > 0) {
        \App\Models\Cours::withoutGlobalScopes()->whereNull('ecole_id')->update(['ecole_id' => \$ecole->id]);
        echo '🥋 ' . \$coursNull . ' cours liés à école: ' . \$ecole->nom;
    } else {
        echo '🥋 Tous les cours ont déjà une école assignée';
    }
} else {
    echo '❌ Aucune école trouvée';
}
"

# 5. Test Global Scopes après réparation
echo "🔍 5. Test Global Scopes..."
php artisan tinker --execute="
try {
    echo 'Tests Global Scopes:';
    echo '  - Users total: ' . \App\Models\User::withoutGlobalScopes()->count();
    echo '  - Users visibles: ' . \App\Models\User::count();
    echo '  - Cours total: ' . \App\Models\Cours::withoutGlobalScopes()->count();
    echo '  - Cours visibles: ' . \App\Models\Cours::count();
    echo '  - Instructeurs: ' . \App\Models\User::withoutGlobalScopes()->role('instructeur')->count();
    echo '✅ Global Scopes fonctionnels';
} catch (\Exception \$e) {
    echo '❌ Erreur Global Scopes: ' . \$e->getMessage();
}
"

# 6. Créer utilisateur admin si nécessaire
echo "👑 6. Vérification admin..."
php artisan tinker --execute="
\$ecole = \App\Models\Ecole::first();
if (!\$ecole) {
    \$ecole = \App\Models\Ecole::create([
        'nom' => 'Studios Unis St-Émile',
        'slug' => 'studios-unis-st-emile',
        'adresse' => '123 Rue Principale',
        'ville' => 'St-Émile',
        'code_postal' => 'G0A 4E0',
        'province' => 'QC',
        'telephone' => '418-123-4567',
        'email' => 'info@studiosunis.com',
        'est_active' => true
    ]);
    echo '🏦 École créée: ' . \$ecole->nom;
}
\$admin = \App\Models\User::withoutGlobalScopes()->role('admin_ecole')->first();
if (!\$admin) {
    \$admin = \App\Models\User::create([
        'name' => 'Admin École',
        'email' => 'admin@studiosunis.com',
        'password' => bcrypt('password123'),
        'ecole_id' => \$ecole->id,
        'email_verified_at' => now()
    ]);
    \$admin->assignRole('admin_ecole');
    echo '👑 Admin créé: ' . \$admin->email . ' (password: password123)';
} else {
    echo '👑 Admin existant: ' . \$admin->email;
}
"

# 7. Créer instructeur si nécessaire
echo "👨‍🏫 7. Vérification instructeur..."
php artisan tinker --execute="
\$ecole = \App\Models\Ecole::first();
\$instructeur = \App\Models\User::withoutGlobalScopes()->role('instructeur')->first();
if (!\$instructeur) {
    \$instructeur = \App\Models\User::create([
        'name' => 'Jean Sensei',
        'email' => 'sensei@studiosunis.com',
        'password' => bcrypt('password123'),
        'ecole_id' => \$ecole->id,
        'email_verified_at' => now()
    ]);
    \$instructeur->assignRole('instructeur');
    echo '👨‍🏫 Instructeur créé: ' . \$instructeur->email . ' (password: password123)';
} else {
    echo '👨‍🏫 Instructeur existant: ' . \$instructeur->email;
}
"

# 8. Seed cours maintenant que tout est correct
echo "🥋 8. Seed cours démonstration..."
php artisan db:seed --class=CoursDemoSeeder --force

# 9. Tests finaux complets
echo "✅ 9. Tests finaux..."
php artisan tinker --execute="
echo 'ÉTAT FINAL APRÈS RÉPARATION:';
echo '  🏫 Écoles: ' . \App\Models\Ecole::count();
echo '  👥 Users total: ' . \App\Models\User::withoutGlobalScopes()->count();
echo '  👑 Admins: ' . \App\Models\User::withoutGlobalScopes()->role('admin_ecole')->count();
echo '  👨‍🏫 Instructeurs: ' . \App\Models\User::withoutGlobalScopes()->role('instructeur')->count();
echo '  🥋 Cours total: ' . \App\Models\Cours::withoutGlobalScopes()->count();
echo '  🎯 Cours visibles: ' . \App\Models\Cours::count();
echo '  ✅ Cours actifs: ' . \App\Models\Cours::actif()->count();
"

# 10. Cache et routes
echo "🔄 10. Cache et optimisation..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache

echo "✅ === RÉPARATION TERMINÉE ==="
echo "🌐 Tester maintenant:"
echo "   - http://127.0.0.1:8001/login"
echo "   - http://127.0.0.1:8001/cours (après login)"
echo ""
echo "📋 Comptes de test:"
echo "   - Admin: admin@studiosunis.com"
echo "   - Instructeur: sensei@studiosunis.com" 
echo "   - Mot de passe: password123"

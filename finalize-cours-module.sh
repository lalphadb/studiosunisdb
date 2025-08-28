#!/bin/bash

# ============================================================
# StudiosDB - Finalisation Module Cours
# ============================================================

echo "🚀 === FINALISATION MODULE COURS ==="

# 1. Réexécuter migrations (avec correction doublon)
echo "📄 1. Migration des structures..."
php artisan migrate || {
    echo "❌ Migration échouée - vérification nécessaire"
}

# 2. Vérifier colonnes critiques
echo "🔍 2. Vérification colonnes..."
php artisan tinker --execute="
echo 'Colonne ecole_id: ' . (\Schema::hasColumn('cours','ecole_id') ? 'PRÉSENT ✅' : 'ABSENT ❌');
echo 'Colonne instructeur_id: ' . (\Schema::hasColumn('cours','instructeur_id') ? 'PRÉSENT ✅' : 'ABSENT ❌');
"

# 3. Créer école demo si inexistante
echo "🏫 3. Vérification école..."
php artisan tinker --execute="
if (\App\Models\Ecole::count() === 0) {
    \App\Models\Ecole::create([
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
    echo '🏫 École demo créée';
} else {
    echo '🏫 École existante: ' . \App\Models\Ecole::first()->nom;
}
"

# 4. Créer utilisateur instructeur demo si inexistant
echo "👨‍🏫 4. Vérification instructeurs..."
php artisan tinker --execute="
\$ecole = \App\Models\Ecole::first();
if (\App\Models\User::withoutGlobalScopes()->role('instructeur')->count() === 0) {
    \$user = \App\Models\User::create([
        'name' => 'Jean Sensei',
        'email' => 'sensei@studiosunis.com',
        'password' => bcrypt('password123'),
        'ecole_id' => \$ecole->id,
        'email_verified_at' => now()
    ]);
    \$user->assignRole('instructeur');
    echo '👨‍🏫 Instructeur demo créé: ' . \$user->email;
} else {
    echo '👨‍🏫 Instructeurs existants: ' . \App\Models\User::withoutGlobalScopes()->role('instructeur')->count();
}
"

# 5. Seed cours de démonstration
echo "🥋 5. Création cours démonstration..."
php artisan db:seed --class=CoursDemoSeeder

# 6. Tests finaux
echo "✅ 6. Tests finaux..."
php artisan tinker --execute="
echo 'Global Scope Test: ' . \App\Models\Cours::count() . ' cours visibles';
echo 'Sans Global Scope: ' . \App\Models\Cours::withoutGlobalScopes()->count() . ' cours total';
echo 'Avec instructeur: ' . \App\Models\Cours::whereNotNull('instructeur_id')->count() . ' cours';
echo 'Sans instructeur: ' . \App\Models\Cours::whereNull('instructeur_id')->count() . ' cours';
"

# 7. Cache et routes
echo "🔄 7. Cache et optimisation..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache

echo "✅ === MODULE COURS FINALISÉ ==="
echo "🌐 Tester: http://127.0.0.1:8001/cours"
echo "📋 Login démo:"
echo "   - Admin: admin@studiosunis.com"
echo "   - Instructeur: sensei@studiosunis.com" 
echo "   - Mot de passe: password123"

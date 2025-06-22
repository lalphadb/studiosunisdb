#!/bin/bash

echo "🔄 RESTAURATION DE LA BASE DE DONNÉES StudiosUnisDB"
echo "=================================================="

# Vérifier si nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

echo "📊 Vérification de l'état actuel..."

# Vérifier la connexion à la base de données
php artisan tinker --execute="
try {
    \$pdo = DB::connection()->getPdo();
    echo '✅ Connexion DB: OK\n';
    echo '📋 État actuel:\n';
    echo '   - Users: ' . DB::table('users')->count() . '\n';
    echo '   - Écoles: ' . DB::table('ecoles')->count() . '\n';
    echo '   - Ceintures: ' . DB::table('ceintures')->count() . '\n';
    echo '   - Permissions: ' . DB::table('permissions')->count() . '\n';
    echo '   - Rôles: ' . DB::table('roles')->count() . '\n';
} catch (Exception \$e) {
    echo '❌ Erreur DB: ' . \$e->getMessage() . '\n';
    exit(1);
}
"

echo ""
echo "🚀 Lancement de la restauration..."

# Exécuter les migrations si nécessaire
echo "📝 Vérification des migrations..."
php artisan migrate --force

# Exécuter le seeder de restauration
echo "🌱 Restauration des données de base..."
php artisan db:seed --class=RestoreBaseDataSeeder

# Vider et recréer les caches
echo "🧹 Nettoyage des caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "🔄 Recréation des caches..."
php artisan config:cache
php artisan route:cache

# Vérification finale
echo ""
echo "✅ VÉRIFICATION FINALE"
echo "======================"

php artisan tinker --execute="
echo '📊 Données restaurées:\n';
echo '   - Users: ' . DB::table('users')->count() . '\n';
echo '   - Écoles: ' . DB::table('ecoles')->count() . '\n';
echo '   - Ceintures: ' . DB::table('ceintures')->count() . '\n';
echo '   - Permissions: ' . DB::table('permissions')->count() . '\n';
echo '   - Rôles: ' . DB::table('roles')->count() . '\n';
echo '\n👥 Utilisateurs administrateurs:\n';
foreach(App\Models\User::with('roles')->get() as \$user) {
    echo '   - ' . \$user->email . ' (' . \$user->roles->pluck('name')->join(', ') . ')\n';
}
echo '\n🏫 Écoles:\n';
foreach(App\Models\Ecole::all() as \$ecole) {
    echo '   - ' . \$ecole->code . ': ' . \$ecole->nom . '\n';
}
"

echo ""
echo "🎉 RESTAURATION TERMINÉE !"
echo "========================="
echo "🔐 Comptes créés:"
echo "   - Super Admin: lalpha@4lb.ca"
echo "   - Admin St-Émile: louis@4lb.ca"
echo "   - Mot de passe: password"
echo ""
echo "🚀 Vous pouvez maintenant tester avec:"
echo "   php artisan serve"
echo "   Puis aller sur: http://localhost:8000/admin/dashboard"


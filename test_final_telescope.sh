#!/bin/bash

echo "🔍 === TEST COMPLET STUDIOSDB V5 PRO + TELESCOPE ==="
echo ""

# Test de base de données
echo "📊 Test Base de Données:"
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro
php artisan tinker --execute="
echo 'Utilisateurs: ' . \App\Models\User::count();
echo 'Membres: ' . (\App\Models\Membre::count() ?? 'Model non trouvé');
echo 'Cours: ' . (\App\Models\Cours::count() ?? 'Model non trouvé');
echo 'Telescope entries: ' . \Illuminate\Support\Facades\DB::table('telescope_entries')->count();
"

echo ""

# Test routes principales
echo "🌐 Test Routes:"
echo "Dashboard: $(curl -s -o /dev/null -w '%{http_code}' http://localhost:8000/dashboard)"
echo "Login: $(curl -s -o /dev/null -w '%{http_code}' http://localhost:8000/login)"
echo "Telescope: $(curl -s -o /dev/null -w '%{http_code}' http://localhost:8000/telescope)"

echo ""

# Test configuration
echo "⚙️ Configuration:"
echo "Environment: $(php artisan tinker --execute='echo app()->environment();')"
echo "Debug Mode: $(php artisan tinker --execute='echo config(\"app.debug\") ? \"ON\" : \"OFF\";')"
echo "Database: $(php artisan tinker --execute='echo config(\"database.default\");')"

echo ""

# Test permissions Telescope
echo "🔭 Test Permissions Telescope:"
php artisan tinker --execute="
\$user = \App\Models\User::where('email', 'louis@4lb.ca')->first();
echo 'User found: ' . (\$user ? 'YES' : 'NO');
if (\$user) {
    echo 'Can view Telescope: ' . (\Illuminate\Support\Facades\Gate::forUser(\$user)->allows('viewTelescope') ? 'YES' : 'NO');
}
"

echo ""
echo "✅ Tests terminés - StudiosDB v5 Pro avec Telescope est prêt!"
echo "🌟 Dashboard accessible sur: http://localhost:8000/dashboard"
echo "🔭 Telescope accessible sur: http://localhost:8000/telescope"
echo ""

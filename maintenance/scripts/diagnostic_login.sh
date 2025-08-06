#!/bin/bash

echo "===========================================" 
echo "🔍 DIAGNOSTIC CONNEXION STUDIOSDB V5 PRO"
echo "===========================================" 
echo ""

# Vérification du serveur Laravel
echo "📡 1. VÉRIFICATION DU SERVEUR"
echo "----------------------------"
if curl -s http://localhost:8001/login > /dev/null; then
    echo "✅ Serveur Laravel accessible sur port 8001"
else
    echo "❌ Serveur Laravel inaccessible"
fi
echo ""

# Vérification de la base de données
echo "🗄️  2. VÉRIFICATION BASE DE DONNÉES"
echo "--------------------------------"
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro
php artisan tinker --execute="
try {
    \$count = DB::table('users')->count();
    echo '✅ Base de données accessible - ' . \$count . ' utilisateurs trouvés' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Erreur base de données: ' . \$e->getMessage() . PHP_EOL;
}
"
echo ""

# Vérification des utilisateurs
echo "👥 3. VÉRIFICATION DES UTILISATEURS"
echo "--------------------------------"
php artisan tinker --execute="
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo 'Utilisateurs disponibles:' . PHP_EOL;
User::all(['id', 'name', 'email', 'role'])->each(function(\$user) {
    echo '- ID: ' . \$user->id . ' | ' . \$user->name . ' | ' . \$user->email . ' | Role: ' . \$user->role . PHP_EOL;
});

echo PHP_EOL . 'Test des mots de passe:' . PHP_EOL;
\$testUsers = [
    ['email' => 'louis@4lb.ca', 'password' => 'password123'],
    ['email' => 'admin@studiosdb.com', 'password' => 'admin123']
];

foreach(\$testUsers as \$test) {
    \$user = User::where('email', \$test['email'])->first();
    if(\$user) {
        \$valid = Hash::check(\$test['password'], \$user->password);
        echo '- ' . \$test['email'] . ' avec ' . \$test['password'] . ': ' . (\$valid ? '✅ VALIDE' : '❌ INVALIDE') . PHP_EOL;
    }
}
"
echo ""

# Vérification des routes
echo "🛣️  4. VÉRIFICATION DES ROUTES"
echo "----------------------------"
if php artisan route:list | grep -q "login"; then
    echo "✅ Routes d'authentification trouvées"
    php artisan route:list | grep -E "(login|dashboard)" | head -5
else
    echo "❌ Routes d'authentification manquantes"
fi
echo ""

# Vérification des assets
echo "📦 5. VÉRIFICATION DES ASSETS"
echo "----------------------------"
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Manifest Vite trouvé"
    echo "📊 Taille: $(du -h public/build/manifest.json | cut -f1)"
else
    echo "❌ Manifest Vite manquant"
fi
echo ""

# URLs de test
echo "🌐 6. URLS DE TEST DISPONIBLES"
echo "-----------------------------"
echo "🔐 Page de connexion: http://localhost:8001/login"
echo "🧪 Page de test: http://localhost:8001/test-login"
echo "📊 Dashboard: http://localhost:8001/dashboard (après connexion)"
echo ""

echo "✅ DIAGNOSTIC TERMINÉ"
echo "====================="
echo ""
echo "💡 CREDENTIALS DE TEST:"
echo "Email: louis@4lb.ca"
echo "Mot de passe: password123"
echo ""
echo "ou"
echo ""
echo "Email: admin@studiosdb.com"  
echo "Mot de passe: admin123"
echo ""

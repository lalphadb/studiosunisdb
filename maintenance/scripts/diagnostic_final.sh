#!/bin/bash

echo "🔧 DIAGNOSTIC POST-CORRECTION DASHBOARD"
echo "========================================"
echo ""

# Attendre démarrage serveur
echo "⏳ Attente du démarrage du serveur..."
sleep 3

# Test de base
echo "🌐 1. TEST ACCÈS LOGIN"
echo "--------------------"
if curl -s http://localhost:8001/login | grep -q "StudiosDB"; then
    echo "✅ Page de login accessible"
else
    echo "❌ Page de login inaccessible"
fi

echo ""
echo "📦 2. VÉRIFICATION DES ASSETS"
echo "----------------------------"
if [ -f "public/build/.vite/manifest.json" ]; then
    echo "✅ Manifest Vite présent"
    echo "📊 Taille: $(du -h public/build/.vite/manifest.json | cut -f1)"
    
    # Vérifier JS principal
    if [ -f "public/build/assets/app-DK3aiH99.js" ]; then
        echo "✅ Bundle JavaScript trouvé"
        echo "📊 Taille JS: $(du -h public/build/assets/app-DK3aiH99.js | cut -f1)"
    else
        echo "❌ Bundle JavaScript manquant"
    fi
    
    # Vérifier CSS principal
    if [ -f "public/build/assets/app-CvnwnLCZ.css" ]; then
        echo "✅ Bundle CSS trouvé"
        echo "📊 Taille CSS: $(du -h public/build/assets/app-CvnwnLCZ.css | cut -f1)"
    else
        echo "❌ Bundle CSS manquant"
    fi
else
    echo "❌ Manifest Vite manquant"
fi

echo ""
echo "🗄️  3. TEST CONNEXION BASE DE DONNÉES"
echo "------------------------------------"
php artisan tinker --execute="
try {
    \$count = DB::table('users')->count();
    echo '✅ Base accessible - ' . \$count . ' utilisateurs' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Erreur DB: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🔐 4. TEST AUTHENTIFICATION"
echo "-------------------------"
php artisan tinker --execute="
use App\Models\User;
use Illuminate\Support\Facades\Hash;

\$user = User::where('email', 'louis@4lb.ca')->first();
if(\$user && Hash::check('password123', \$user->password)) {
    echo '✅ Credentials valides pour louis@4lb.ca' . PHP_EOL;
} else {
    echo '❌ Problème credentials' . PHP_EOL;
}
"

echo ""
echo "📍 5. URLS DE TEST"
echo "=================="
echo "🔐 Login: http://localhost:8001/login"
echo "📊 Dashboard: http://localhost:8001/dashboard (après connexion)"
echo "🧪 Test: http://localhost:8001/test-login"

echo ""
echo "✅ DIAGNOSTIC TERMINÉ"
echo "===================="
echo ""
echo "💡 PROCHAINES ÉTAPES:"
echo "1. Ouvrez http://localhost:8001/login"
echo "2. Connectez-vous avec louis@4lb.ca / password123"
echo "3. Le dashboard devrait s'afficher sans page blanche"
echo ""
echo "🆘 Si problème persiste:"
echo "- Ouvrez F12 dans le navigateur"
echo "- Vérifiez les erreurs en console"
echo "- Regardez l'onglet Network pour les ressources qui échouent"
echo ""

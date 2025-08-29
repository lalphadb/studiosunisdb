#!/bin/bash

echo "=== NETTOYAGE RÔLES - VERSION CORRIGÉE ==="
echo ""

cd /home/studiosdb/studiosunisdb

echo "1️⃣ Exécution nettoyage rôles (script PHP direct)..."
php clean-roles-simple.php

if [ $? -eq 0 ]; then
    echo ""
    echo "2️⃣ Reset cache Laravel..."
    php artisan permission:cache-reset
    php artisan optimize:clear
    
    echo ""
    echo "3️⃣ Test rapide accès cours..."
    php artisan tinker --execute="
    \$user = App\Models\User::where('email', 'louis@4lb.ca')->first();
    if (\$user && \$user->hasRole('superadmin')) {
        echo '✅ Superadmin OK - Test policy cours...' . PHP_EOL;
        \$policy = app('App\Policies\CoursPolicy');
        echo 'viewAny: ' . (\$policy->viewAny(\$user) ? 'OK' : 'ERREUR') . PHP_EOL;
        echo 'create: ' . (\$policy->create(\$user) ? 'OK' : 'ERREUR') . PHP_EOL;
    } else {
        echo '❌ Problème utilisateur superadmin' . PHP_EOL;
    }
    "
    
    echo ""
    echo "✅ CORRECTION TERMINÉE AVEC SUCCÈS"
    echo ""
    echo "📋 Testez maintenant l'accès aux cours:"
    echo "   http://127.0.0.1:8000/cours"
    echo ""
    echo "🔑 Connexion:"
    echo "   Email: louis@4lb.ca"
    echo "   Mot de passe: password123"
    
else
    echo ""
    echo "❌ ERREUR lors du nettoyage"
    echo "Vérifiez les logs ci-dessus"
fi

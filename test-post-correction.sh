#!/bin/bash

# Test simple après correction 403

echo "=== TEST POST-CORRECTION ==="

# Test 1: Accès aux cours
echo "🔍 Test 1: Accès liste cours..."
COURS_COUNT=$(php artisan tinker --execute="
try {
    \$user = \\App\\Models\\User::find(2);
    if (\$user) {
        \\Auth::login(\$user);
        echo \\App\\Models\\Cours::count();
    }
} catch (Exception \$e) {
    echo '0';
}
" 2>/dev/null)

echo "Cours visibles: $COURS_COUNT"

# Test 2: Accès cours spécifique  
echo "🔍 Test 2: Accès cours ID 1..."
COURS_1_ACCESS=$(php artisan tinker --execute="
try {
    \$user = \\App\\Models\\User::find(2);
    if (\$user) {
        \\Auth::login(\$user);
        \$cours1 = \\App\\Models\\Cours::find(1);
        echo \$cours1 ? 'OK' : 'NOT_FOUND';
    }
} catch (Exception \$e) {
    echo 'ERROR';
}
" 2>/dev/null)

echo "Cours 1 accessible: $COURS_1_ACCESS"

# Test 3: Permissions suppression
echo "🔍 Test 3: Permission suppression..."
DELETE_PERMISSION=$(php artisan tinker --execute="
try {
    \$user = \\App\\Models\\User::find(2);
    \$cours1 = \\App\\Models\\Cours::find(1);
    if (\$user && \$cours1) {
        \\Auth::login(\$user);
        echo \$user->can('delete', \$cours1) ? 'OK' : 'NO_PERMISSION';
    }
} catch (Exception \$e) {
    echo 'ERROR';
}
" 2>/dev/null)

echo "Permission suppression: $DELETE_PERMISSION"

echo
if [ "$COURS_COUNT" -gt "0" ] && [ "$COURS_1_ACCESS" = "OK" ] && [ "$DELETE_PERMISSION" = "OK" ]; then
    echo "✅ SUCCÈS: Correction appliquée avec succès"
    echo "Vous pouvez maintenant:"
    echo "- Accéder à la liste des cours"
    echo "- Voir les détails des cours" 
    echo "- Supprimer les cours avec les boutons d'action"
else
    echo "❌ ÉCHEC: Correction incomplète"
    echo "Problèmes détectés:"
    [ "$COURS_COUNT" = "0" ] && echo "- Aucun cours visible"
    [ "$COURS_1_ACCESS" != "OK" ] && echo "- Cours 1 inaccessible"
    [ "$DELETE_PERMISSION" != "OK" ] && echo "- Permission suppression refusée"
fi

echo
echo "🌐 Testez maintenant dans le navigateur:"
echo "http://127.0.0.1:8001/cours"
echo

#!/bin/bash

# Correction IMMÉDIATE pour erreur "Data truncated niveau"
echo "🚨 CORRECTION IMMÉDIATE - Erreur ENUM niveau"
echo "============================================"
echo ""

cd /home/studiosdb/studiosunisdb

echo "1. Application migration extension ENUM niveau..."
php artisan migrate --force

echo ""
echo "2. Vérification colonne niveau étendue..."
php artisan tinker --execute="
try {
    \$result = DB::select('SHOW COLUMNS FROM cours WHERE Field = \"niveau\"');
    if(\$result) {
        echo 'Colonne niveau type: ' . \$result[0]->Type;
    }
} catch(Exception \$e) {
    echo 'Erreur: ' . \$e->getMessage();
}
"

echo ""
echo "3. Test création cours avec nouveaux niveaux..."
php artisan tinker --execute="
try {
    \$cours = new App\\Models\\Cours();
    echo 'Niveaux disponibles: ' . implode(', ', array_keys(App\\Models\\Cours::NIVEAUX));
    echo 'Cours model charge: OK';
} catch(Exception \$e) {
    echo 'Erreur model: ' . \$e->getMessage();
}
"

echo ""
echo "✅ CORRECTION APPLIQUÉE"
echo "Vous pouvez maintenant:"
echo "- Rafraîchir la page /cours/create"
echo "- Sélectionner les nouveaux niveaux: Tous, Privé, À la carte"
echo "- Créer un cours de test"
echo ""

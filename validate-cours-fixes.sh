#!/bin/bash

echo "=== VALIDATION CORRECTIONS ACCESSEURS COURS ==="
echo ""

cd /home/studiosdb/studiosunisdb

echo "1️⃣ Test création d'un cours basique..."
php artisan tinker --execute="
try {
    // Test avec données minimales
    \$cours = new App\Models\Cours([
        'nom' => 'Test Cours',
        'niveau' => 'debutant',
        'type_tarif' => 'mensuel',
        'jour_semaine' => 'lundi',
        'age_min' => 6,
        'places_max' => 20,
        'actif' => true
    ]);
    
    // Test des accesseurs corrigés
    echo 'Test niveau_label: ' . \$cours->niveau_label . PHP_EOL;
    echo 'Test type_tarif_label: ' . \$cours->type_tarif_label . PHP_EOL;
    echo 'Test age_range: ' . \$cours->age_range . PHP_EOL;
    echo 'Test horaire_complet: ' . \$cours->horaire_complet . PHP_EOL;
    echo 'Test statut_inscription: ' . \$cours->statut_inscription . PHP_EOL;
    echo 'Test instructeur_nom: ' . \$cours->instructeur_nom . PHP_EOL;
    echo 'Test couleur_calendrier: ' . \$cours->couleur_calendrier . PHP_EOL;
    
    echo '✅ Tous les accesseurs fonctionnent sans erreur!' . PHP_EOL;
    
} catch (Exception \$e) {
    echo '❌ ERREUR: ' . \$e->getMessage() . PHP_EOL;
    echo 'File: ' . \$e->getFile() . ':' . \$e->getLine() . PHP_EOL;
}
"

echo ""
echo "2️⃣ Test avec données nulles/invalides..."
php artisan tinker --execute="
try {
    // Test avec données problématiques
    \$cours = new App\Models\Cours([
        'nom' => 'Test Cours Problématique',
        'niveau' => null,  // NULL
        'type_tarif' => 'inexistant',  // Valeur invalide
        'jour_semaine' => null,  // NULL
        'age_min' => null,
        'places_max' => 20,
        'actif' => true
    ]);
    
    echo 'Test niveau_label (null): ' . \$cours->niveau_label . PHP_EOL;
    echo 'Test type_tarif_label (invalide): ' . \$cours->type_tarif_label . PHP_EOL;
    echo 'Test age_range (null age_min): ' . \$cours->age_range . PHP_EOL;
    echo 'Test horaire_complet (null jour): ' . \$cours->horaire_complet . PHP_EOL;
    
    echo '✅ Gestion des valeurs nulles/invalides OK!' . PHP_EOL;
    
} catch (Exception \$e) {
    echo '❌ ERREUR avec valeurs nulles: ' . \$e->getMessage() . PHP_EOL;
    echo 'File: ' . \$e->getFile() . ':' . \$e->getLine() . PHP_EOL;
}
"

echo ""
echo "3️⃣ Test accès page cours..."
echo "Tentative d'accès à http://127.0.0.1:8000/cours"

# Test rapide avec curl si possible
if command -v curl > /dev/null 2>&1; then
    RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "http://127.0.0.1:8000/cours" || echo "000")
    
    if [ "$RESPONSE" = "200" ]; then
        echo "✅ Page cours accessible (HTTP 200)"
    elif [ "$RESPONSE" = "302" ]; then
        echo "⚠️  Redirection détectée (HTTP 302) - probablement vers login"
    elif [ "$RESPONSE" = "000" ]; then
        echo "⚠️  Serveur non accessible - vérifiez que php artisan serve est actif"
    else
        echo "❌ Erreur HTTP: $RESPONSE"
    fi
else
    echo "ℹ️  curl non disponible - testez manuellement dans le navigateur"
fi

echo ""
echo "4️⃣ Clear cache pour s'assurer des changements..."
php artisan optimize:clear > /dev/null 2>&1
echo "✅ Cache nettoyé"

echo ""
echo "=== RÉSUMÉ DES CORRECTIONS ==="
echo "✅ Type hints string ajoutés aux accesseurs"
echo "✅ Gestion des valeurs null avec fallbacks"
echo "✅ Protection contre les clés inexistantes dans les constantes"
echo "✅ Fallbacks appropriés pour tous les accesseurs"
echo ""
echo "📋 Testez maintenant dans le navigateur:"
echo "   http://127.0.0.1:8000/cours"
echo ""
echo "Si vous voyez encore des erreurs TypeError, redémarrez le serveur:"
echo "   php artisan serve"

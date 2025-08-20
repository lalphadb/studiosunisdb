cat > /home/studiosdb/studiosunisdb/test_cours_module.sh << 'EOH'
#!/bin/bash

echo "🧪 TEST DU MODULE COURS - StudiosDB v5"
echo "======================================"
echo ""

# Test 1: Vérification des fichiers
echo "1️⃣ Vérification des fichiers créés..."
echo ""

FILES=(
    "app/Http/Controllers/CoursController.php"
    "app/Models/Cours.php"
    "resources/js/Pages/Cours/Index.vue"
    "database/migrations/2024_01_04_create_cours_table.php"
    "database/seeders/CoursSeeder.php"
    "routes/cours.php"
)

for file in "${FILES[@]}"; do
    if [ -f "/home/studiosdb/studiosunisdb/$file" ]; then
        echo "✅ $file - OK"
    else
        echo "❌ $file - MANQUANT"
    fi
done

echo ""
echo "2️⃣ Test des classes PHP..."
php /home/studiosdb/studiosunisdb/artisan tinker --execute="
echo PHP_EOL;
echo '  • Modèle Cours: ' . (class_exists('App\Models\Cours') ? '✅ OK' : '❌ ERREUR') . PHP_EOL;
echo '  • CoursController: ' . (class_exists('App\Http\Controllers\CoursController') ? '✅ OK' : '❌ ERREUR') . PHP_EOL;
echo '  • CoursSeeder: ' . (class_exists('Database\Seeders\CoursSeeder') ? '✅ OK' : '❌ ERREUR') . PHP_EOL;
"

echo ""
echo "3️⃣ Test de la base de données..."
php /home/studiosdb/studiosunisdb/artisan tinker --execute="
use Illuminate\Support\Facades\Schema;
echo PHP_EOL;
if (Schema::hasTable('cours')) {
    echo '  • Table cours: ✅ Existe' . PHP_EOL;
    echo '  • Colonnes: ' . count(Schema::getColumnListing('cours')) . PHP_EOL;
} else {
    echo '  • Table cours: ❌ N\'existe pas (lancer les migrations)' . PHP_EOL;
}
"

echo ""
echo "4️⃣ Test des routes..."
php /home/studiosdb/studiosunisdb/artisan route:list | grep cours | head -5

echo ""
echo "======================================"
echo "📊 RÉSUMÉ DU TEST"
echo "======================================"
echo ""
echo "Si tous les tests sont ✅ OK, le module est prêt!"
echo "Sinon, exécutez: ./deploy_cours_module.sh"
echo ""
echo "🔗 Accès: http://studiosdb.local:8000/cours"
EOH
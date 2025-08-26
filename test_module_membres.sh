#!/bin/bash

echo "=== TEST COMPLET MODULE MEMBRES ==="
echo ""

echo "1. FICHIERS CRÉÉS:"
echo "==================="
[ -f "app/Traits/BelongsToEcole.php" ] && echo "✅ Trait BelongsToEcole" || echo "❌ Trait BelongsToEcole manquant"
[ -f "app/Models/Ecole.php" ] && echo "✅ Modèle Ecole" || echo "❌ Modèle Ecole manquant"
[ -f "app/Models/Ceinture.php" ] && echo "✅ Modèle Ceinture" || echo "❌ Modèle Ceinture manquant"
[ -f "app/Models/LienFamilial.php" ] && echo "✅ Modèle LienFamilial" || echo "❌ Modèle LienFamilial manquant"
[ -f "app/Models/ProgressionCeinture.php" ] && echo "✅ Modèle ProgressionCeinture" || echo "❌ Modèle ProgressionCeinture manquant"
[ -f "app/Http/Resources/MembreResource.php" ] && echo "✅ MembreResource" || echo "❌ MembreResource manquant"
[ -f "app/Http/Requests/MembreRequest.php" ] && echo "✅ MembreRequest" || echo "❌ MembreRequest manquant"
[ -f "app/Exports/MembersExport.php" ] && echo "✅ MembersExport" || echo "❌ MembersExport manquant"
echo ""

echo "2. SYNTAXE PHP:"
echo "================"
php -l app/Models/Membre.php 2>&1 | head -1
php -l app/Http/Controllers/MembreController.php 2>&1 | head -1
php -l app/Providers/AppServiceProvider.php 2>&1 | head -1
echo ""

echo "3. PACKAGES INSTALLÉS:"
echo "======================"
composer show | grep -E "(maatwebsite|barryvdh)" | head -5
echo ""

echo "4. ROUTES MEMBRES:"
echo "=================="
php artisan route:list 2>/dev/null | grep membre | head -10
echo ""

echo "5. MIGRATIONS:"
echo "=============="
php artisan migrate:status 2>/dev/null | tail -5
echo ""

echo "=== FIN DU TEST ==="

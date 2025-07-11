#!/bin/bash

echo "=== VÉRIFICATION DE LA PROGRESSION StudiosDB v4 ==="
echo "Date: $(date)"
echo

# 1. Vérifier les modèles
echo "📦 MODÈLES ($(ls app/Models/*.php 2>/dev/null | wc -l) fichiers)"
if [ -d "app/Models" ]; then
    ls app/Models/*.php | sed 's|app/Models/||g' | sed 's|.php||g' | column -c 80
else
    echo "❌ Dossier Models non trouvé"
fi
echo

# 2. Vérifier les contrôleurs
echo "🎮 CONTRÔLEURS"
echo "Admin: $(find app/Http/Controllers/Admin -name "*.php" 2>/dev/null | wc -l) fichiers"
if [ -d "app/Http/Controllers/Admin" ]; then
    ls app/Http/Controllers/Admin/*.php 2>/dev/null | sed 's|app/Http/Controllers/Admin/||g' | sed 's|.php||g' | column -c 80
fi
echo

# 3. Vérifier les middlewares
echo "🔒 MIDDLEWARES"
if [ -d "app/Http/Middleware" ]; then
    echo "Custom: $(ls app/Http/Middleware/*.php 2>/dev/null | grep -v "Authenticate\|RedirectIfAuthenticated" | wc -l) fichiers"
    ls app/Http/Middleware/*.php 2>/dev/null | grep -v "Authenticate\|RedirectIfAuthenticated" | sed 's|app/Http/Middleware/||g' | sed 's|.php||g'
else
    echo "❌ Aucun middleware personnalisé"
fi
echo

# 4. Vérifier les migrations
echo "🗄️  MIGRATIONS"
echo "Total: $(ls database/migrations/*.php 2>/dev/null | wc -l) fichiers"
echo "Exécutées: $(php artisan migrate:status 2>/dev/null | grep -c "Ran")"
echo

# 5. Vérifier les seeders
echo "🌱 SEEDERS"
echo "Total: $(ls database/seeders/*.php 2>/dev/null | wc -l) fichiers"
ls database/seeders/*.php 2>/dev/null | sed 's|database/seeders/||g' | sed 's|.php||g' | grep -v "DatabaseSeeder" | column -c 80
echo

# 6. Vérifier les routes
echo "🛣️  ROUTES"
for file in web admin auth api; do
    if [ -f "routes/$file.php" ]; then
        count=$(grep -c "Route::" "routes/$file.php" 2>/dev/null || echo "0")
        echo "✓ routes/$file.php ($count routes)"
    else
        echo "✗ routes/$file.php manquant"
    fi
done
echo

# 7. Vérifier les packages installés
echo "📚 PACKAGES PRINCIPAUX"
packages=("spatie/laravel-permission" "laravel/fortify" "laravel/sanctum" "maatwebsite/excel" "barryvdh/laravel-dompdf")
for package in "${packages[@]}"; do
    if composer show "$package" &>/dev/null; then
        echo "✓ $package"
    else
        echo "✗ $package non installé"
    fi
done
echo

# 8. État de Git
echo "🔄 GIT STATUS"
echo "Branche: $(git branch --show-current)"
echo "Dernier commit: $(git log -1 --oneline)"
echo "Fichiers modifiés: $(git status --porcelain | wc -l)"
echo

# 9. Tests rapides
echo "🧪 TESTS RAPIDES"
echo -n "Laravel: "
php artisan --version 2>/dev/null || echo "❌ Erreur"
echo -n "Base de données: "
php artisan db:show --counts 2>/dev/null | grep "tables" || echo "❌ Erreur de connexion"
echo

# 10. Résumé des tâches
echo "📋 RÉSUMÉ DES TÂCHES"
echo "✅ Complété:"
echo "   - Infrastructure Laravel 11"
echo "   - Base de données (migrations + tables)"
echo "   - Modèles de base"
echo "   - Routes configurées"
echo "   - Seeders créés"
echo
echo "⏳ En cours:"
echo "   - Middlewares à créer/configurer"
echo "   - Contrôleurs admin à créer"
echo "   - Vues Blade à créer"
echo
echo "🔜 Prochaines étapes:"
echo "   1. Exécuter: bash fix-seeding-commit.sh"
echo "   2. Exécuter: bash create-middlewares.sh"
echo "   3. Exécuter: bash create-admin-controllers.sh"
echo "   4. Créer les vues Blade de base"
echo
echo "💡 Commande utile:"
echo "   php artisan route:list --path=admin"
echo "   php artisan make:view admin.layout"
echo
echo "=== FIN DE LA VÉRIFICATION ==="

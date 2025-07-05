#!/bin/bash

echo "🧭 MIGRATION SÉCURISÉE NAVIGATION"
echo "================================"

BACKUP_NAV="backup_navigation_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_NAV"

# Backup layout admin et navigation
echo "📦 Backup navigation et layout..."
cp resources/views/layouts/admin.blade.php "$BACKUP_NAV/" 2>/dev/null
cp resources/views/partials/admin-navigation.blade.php "$BACKUP_NAV/" 2>/dev/null

# Créer dossier components/admin si nécessaire
mkdir -p resources/views/components/admin

echo ""
echo "🔄 MIGRATION NAVIGATION:"

# Vérifier que la navigation existe
if [ -f "resources/views/partials/admin-navigation.blade.php" ]; then
    echo "  ✅ Navigation trouvée dans partials/"
    
    # Copier vers components (ne pas déplacer encore)
    cp resources/views/partials/admin-navigation.blade.php resources/views/components/admin/navigation.blade.php
    echo "  ✅ Navigation copiée vers components/admin/navigation.blade.php"
    
    # Créer un layout admin test
    cp resources/views/layouts/admin.blade.php resources/views/layouts/admin.blade.php.test
    
    # Modifier le layout test
    sed -i.bak "s/@include('partials\.admin-navigation')/<x-admin.navigation \/>/" resources/views/layouts/admin.blade.php.test
    
    echo "  ✅ Layout test créé avec nouvelle syntaxe"
    
    # Test de validation Blade
    echo ""
    echo "🧪 TEST VALIDATION BLADE:"
    if php artisan view:clear 2>/dev/null; then
        echo "  ✅ Cache vues vidé"
        
        # Test compilation du nouveau layout
        if php -r "
        require 'vendor/autoload.php';
        \$app = require 'bootstrap/app.php';
        try {
            \$blade = \$app->make('view');
            echo 'Blade engine OK\n';
        } catch (Exception \$e) {
            echo 'Erreur Blade: ' . \$e->getMessage() . '\n';
        }
        " 2>/dev/null; then
            echo "  ✅ Blade engine fonctionne"
        else
            echo "  ⚠️ Impossible de tester Blade engine"
        fi
    else
        echo "  ⚠️ Impossible de vider le cache"
    fi
    
    echo ""
    echo "⚠️ ÉTAPE MANUELLE REQUISE:"
    echo "1. Testez votre application avec le layout actuel"
    echo "2. Si tout fonctionne, remplacez admin.blade.php par admin.blade.php.test"
    echo "3. Supprimez ensuite partials/admin-navigation.blade.php"
    
else
    echo "  ❌ Navigation non trouvée dans partials/"
fi

echo ""
echo "✅ Migration navigation préparée"
echo "📦 Backup: $BACKUP_NAV"

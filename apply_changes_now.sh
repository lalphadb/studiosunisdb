#!/bin/bash

echo "🔄 APPLICATION IMMÉDIATE DES CHANGEMENTS"
echo "========================================"

# Étape 1: Vérifier l'état actuel
echo "1. État actuel du layout admin:"
if grep -q "partials.admin-navigation" resources/views/layouts/admin.blade.php; then
    echo "❌ Layout utilise encore partials/admin-navigation"
    NEEDS_UPDATE=true
else
    echo "✅ Layout déjà mis à jour"
    NEEDS_UPDATE=false
fi

# Étape 2: S'assurer que la navigation component existe
echo ""
echo "2. Vérification component navigation:"
if [ ! -f "resources/views/components/admin/navigation.blade.php" ]; then
    echo "🔄 Création component navigation..."
    
    mkdir -p resources/views/components/admin
    
    # Copier depuis partials si existe
    if [ -f "resources/views/partials/admin-navigation.blade.php" ]; then
        cp resources/views/partials/admin-navigation.blade.php resources/views/components/admin/navigation.blade.php
        echo "✅ Navigation copiée de partials vers components"
    else
        echo "❌ Navigation partials non trouvée"
    fi
else
    echo "✅ Component navigation existe déjà"
fi

# Étape 3: Mettre à jour le layout si nécessaire
if [ "$NEEDS_UPDATE" = true ]; then
    echo ""
    echo "3. Mise à jour layout admin:"
    
    # Backup
    cp resources/views/layouts/admin.blade.php resources/views/layouts/admin.blade.php.backup_$(date +%H%M%S)
    
    # Appliquer le changement
    sed -i "s/@include('partials\.admin-navigation')/<x-admin.navigation \/>/" resources/views/layouts/admin.blade.php
    
    echo "✅ Layout admin mis à jour"
    
    # Vérifier le changement
    if grep -q "<x-admin.navigation" resources/views/layouts/admin.blade.php; then
        echo "✅ Changement appliqué avec succès"
    else
        echo "❌ Échec de la mise à jour"
    fi
fi

# Étape 4: Nettoyer le cache
echo ""
echo "4. Nettoyage cache:"
php artisan view:clear
php artisan optimize:clear
echo "✅ Cache nettoyé"

# Étape 5: Test final
echo ""
echo "5. Vérification finale:"
echo "Layout admin utilise maintenant:"
grep "@include\|<x-" resources/views/layouts/admin.blade.php | grep -E "(admin-navigation|navigation)"

echo ""
echo "✅ CHANGEMENTS APPLIQUÉS - RECHARGEZ VOTRE PAGE ADMIN"

#!/bin/bash

echo "🧭 FINALISATION MIGRATION NAVIGATION"
echo "==================================="

# Vérifier si navigation existe dans components/admin
if [ -f "resources/views/components/admin/navigation.blade.php" ]; then
    echo "✅ Navigation trouvée dans components/admin/"
    
    # Mettre à jour le layout admin
    if [ -f "resources/views/layouts/admin.blade.php" ]; then
        echo "🔄 Mise à jour layout admin..."
        
        # Backup du layout
        cp resources/views/layouts/admin.blade.php resources/views/layouts/admin.blade.php.backup
        
        # Remplacer l'include par le component
        sed -i "s/@include('partials\.admin-navigation')/<x-admin.navigation \/>/" resources/views/layouts/admin.blade.php
        
        echo "✅ Layout admin mis à jour"
        
        # Vérifier que partials/admin-navigation existe encore
        if [ -f "resources/views/partials/admin-navigation.blade.php" ]; then
            echo "🗑️ Suppression ancien partials/admin-navigation.blade.php"
            rm resources/views/partials/admin-navigation.blade.php
        fi
        
    else
        echo "❌ Layout admin non trouvé"
    fi
else
    echo "❌ Navigation non trouvée dans components/admin/"
fi

echo ""
echo "✅ Finalisation navigation terminée"

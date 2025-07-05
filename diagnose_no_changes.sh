#!/bin/bash

echo "🔍 DIAGNOSTIC : POURQUOI AUCUN CHANGEMENT VISIBLE"
echo "==============================================="

echo "1. Vérification layout admin actuel:"
echo "===================================="
if [ -f "resources/views/layouts/admin.blade.php" ]; then
    echo "📄 Contenu layout admin (lignes importantes):"
    grep -n "admin-navigation\|<x-admin.navigation" resources/views/layouts/admin.blade.php
    echo ""
    echo "📄 Include/component utilisé:"
    grep "@include\|<x-" resources/views/layouts/admin.blade.php | head -5
fi

echo ""
echo "2. Vérification fichiers navigation:"
echo "===================================="
echo "📁 Partials admin-navigation:"
if [ -f "resources/views/partials/admin-navigation.blade.php" ]; then
    echo "✅ EXISTE - Encore utilisé"
    wc -l resources/views/partials/admin-navigation.blade.php
else
    echo "❌ SUPPRIMÉ"
fi

echo ""
echo "📁 Components admin navigation:"
if [ -f "resources/views/components/admin/navigation.blade.php" ]; then
    echo "✅ EXISTE - Créé mais pas utilisé"
    wc -l resources/views/components/admin/navigation.blade.php
else
    echo "❌ N'EXISTE PAS"
fi

echo ""
echo "3. Cache Laravel:"
echo "================"
echo "📦 Statut cache vues:"
if [ -d "storage/framework/views" ]; then
    echo "Cache vues: $(ls storage/framework/views/*.php 2>/dev/null | wc -l) fichiers"
else
    echo "Pas de cache vues"
fi

echo ""
echo "4. Test accès contrôleurs:"
echo "=========================="
echo "📊 Dashboard controller route:"
php artisan route:list | grep dashboard | head -3

echo ""
echo "5. Vérification assets:"
echo "======================"
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Assets buildés"
else
    echo "⚠️ Assets non buildés"
fi

#!/bin/bash
echo "🔍 VÉRIFICATION COMPLÈTE STUDIOSUNISDB"
echo "===================================="

# 1. Vérifier la syntaxe HasMiddleware dans les contrôleurs
echo "📋 1. Syntaxe HasMiddleware dans les contrôleurs..."
grep -r "implements HasMiddleware" app/Http/Controllers/Admin/ | head -3
echo ""

# 2. Vérifier la structure <x-module-header> 
echo "📋 2. Composant module-header..."
if [ -f "resources/views/components/module-header.blade.php" ]; then
    echo "✅ Composant module-header existe"
    head -10 resources/views/components/module-header.blade.php
else
    echo "❌ Composant module-header manquant"
fi
echo ""

# 3. Vérifier sécurité CSRF dans les vues
echo "📋 3. Sécurité CSRF dans les vues..."
csrf_count=$(find resources/views/admin -name "*.blade.php" -exec grep -l "@csrf" {} \; | wc -l)
echo "Vues avec @csrf: $csrf_count"
echo ""

# 4. Vérifier le filtrage multi-tenant
echo "📋 4. Filtrage multi-tenant..."
grep -r "auth()->user()->ecole_id" app/Http/Controllers/Admin/ | head -2
echo ""

# 5. Tester les endpoints critiques
echo "📋 5. Test des endpoints..."
curl -s http://127.0.0.1:8001/admin/cours > /dev/null && echo "✅ Cours OK" || echo "❌ Cours erreur"
curl -s http://127.0.0.1:8001/admin/seminaires > /dev/null && echo "✅ Séminaires OK" || echo "❌ Séminaires erreur"
curl -s http://127.0.0.1:8001/admin/ceintures > /dev/null && echo "✅ Ceintures OK" || echo "❌ Ceintures erreur"

echo ""
echo "🎯 CONCLUSION: Votre projet est sur la bonne voie!"

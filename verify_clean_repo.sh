#!/bin/bash
echo "🔍 VÉRIFICATION DÉPÔT PROPRE"
echo "============================"

echo "📂 Fichiers restants dans le projet :"
echo ""

# Compter les fichiers par type
echo "🧮 STATISTIQUES :"
echo "• Scripts .sh restants : $(ls *.sh 2>/dev/null | wc -l)"
echo "• Fichiers PHP : $(find app resources -name "*.php" | wc -l)"
echo "• Vues Blade : $(find resources/views -name "*.blade.php" | wc -l)"
echo "• Fichiers config : $(ls -1 config/*.php | wc -l)"
echo ""

echo "📋 FICHIERS RACINE RESTANTS :"
ls -la | grep -E "\.(md|json|xml|js|sh|env)$|VERSION|LICENSE|artisan"

echo ""
echo "✅ STRUCTURE FINALE ATTENDUE :"
echo "• README.md, CHANGELOG.md, CONTRIBUTING.md ✅"
echo "• composer.json, package.json ✅"
echo "• artisan, VERSION ✅"
echo "• .env.example ✅"
echo "• Dossiers Laravel (app, resources, routes, etc.) ✅"
echo "• AUCUN script fix_*.sh ou temporaire ❌"

echo ""
echo "🌐 Vérifiez GitHub :"
echo "https://github.com/lalphadb/studiosunisdb"

# Nettoyer ce script lui-même après utilisation
echo ""
read -p "Supprimer ce script de vérification ? (y/N): " cleanup_self
if [[ $cleanup_self == [yY] ]]; then
    rm verify_clean_repo.sh cleanup_github_repo.sh 2>/dev/null
    echo "✅ Scripts de nettoyage supprimés"
fi

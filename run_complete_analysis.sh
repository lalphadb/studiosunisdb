#!/bin/bash

echo "🚀 ANALYSE COMPLÈTE DU PROJET STUDIOSDB"
echo "======================================="
echo ""

# Créer dossier pour les rapports
mkdir -p analysis_reports
cd analysis_reports

echo "1️⃣ Inventaire complet des fichiers..."
../analyze_studiosdb_files.sh

echo ""
echo "2️⃣ Détection des fichiers inutilisés..."
../detect_unused_files.sh

echo ""
echo "3️⃣ Analyse partials vs components..."
../analyze_partials_components.sh

echo ""
echo "4️⃣ Cartographie d'usage..."
../map_file_usage.sh

echo ""
echo "5️⃣ Résumé de l'analyse..."

# Créer un résumé
SUMMARY="SUMMARY_ANALYSIS_$(date +%Y%m%d_%H%M%S).md"

cat > $SUMMARY << 'EOF'
# 📋 RÉSUMÉ ANALYSE STUDIOSDB

## 🔢 STATISTIQUES GÉNÉRALES

EOF

echo "### Fichiers par type:" >> $SUMMARY
echo "- **Controllers:** $(find ../app/Http/Controllers -name '*.php' -type f 2>/dev/null | wc -l)" >> $SUMMARY
echo "- **Vues Blade:** $(find ../resources/views -name '*.blade.php' -type f 2>/dev/null | wc -l)" >> $SUMMARY
echo "- **Routes:** $(find ../routes -name '*.php' -type f 2>/dev/null | wc -l)" >> $SUMMARY
echo "- **Modèles:** $(find ../app/Models -name '*.php' -type f 2>/dev/null | wc -l)" >> $SUMMARY
echo "- **Components:** $(find ../resources/views/components -name '*.blade.php' -type f 2>/dev/null | wc -l)" >> $SUMMARY
echo "- **Partials:** $(find ../resources/views/partials -name '*.blade.php' -type f 2>/dev/null | wc -l)" >> $SUMMARY
echo "" >> $SUMMARY

echo "### Structure actuelle détectée:" >> $SUMMARY
if [ -d "../resources/views/partials" ]; then
    echo "- ✅ Dossier \`partials/\` existe" >> $SUMMARY
else
    echo "- ❌ Dossier \`partials/\` non trouvé" >> $SUMMARY
fi

if [ -d "../resources/views/components" ]; then
    echo "- ✅ Dossier \`components/\` existe" >> $SUMMARY
else
    echo "- ❌ Dossier \`components/\` non trouvé" >> $SUMMARY
fi

if [ -d "../resources/views/admin" ]; then
    echo "- ✅ Dossier \`admin/\` existe" >> $SUMMARY
else
    echo "- ❌ Dossier \`admin/\` non trouvé" >> $SUMMARY
fi

echo "" >> $SUMMARY
echo "## 📊 RAPPORTS GÉNÉRÉS" >> $SUMMARY
echo "" >> $SUMMARY
ls -la *.md | while read file; do
    echo "- \`$(echo $file | awk '{print $9}')\`" >> $SUMMARY
done

echo "✅ Résumé généré: $SUMMARY"
echo ""
echo "📁 Tous les rapports sont dans: $(pwd)"
echo ""
echo "📋 PROCHAINES ÉTAPES:"
echo "1. Examiner les rapports générés"
echo "2. Identifier les fichiers réellement inutilisés"
echo "3. Planifier la migration partials → components"
echo "4. Exécuter le plan de standardisation"

cd ..

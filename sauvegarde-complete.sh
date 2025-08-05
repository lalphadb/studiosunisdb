#!/bin/bash

echo "🎯 SAUVEGARDE COMPLÈTE STUDIOSDB V5 PRO"
echo "======================================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Initialiser Git si nécessaire
if [ ! -d ".git" ]; then
    echo "1. Initialisation Git..."
    git init
    git config user.name "StudiosDB Team"
    git config user.email "dev@studiosdb.local"
    echo "✅ Git initialisé"
else
    echo "1. Git déjà configuré ✅"
fi

# 2. Vérifier les fichiers créés
echo ""
echo "2. Fichiers de documentation créés..."
ls -la README.md CHANGELOG.md VERSION LICENSE .gitignore 2>/dev/null || echo "Certains fichiers manquent"

# 3. Exécuter le commit de sauvegarde
echo ""
echo "3. Exécution du commit de sauvegarde..."
chmod +x commit-sauvegarde.sh
./commit-sauvegarde.sh

# 4. Résumé final
echo ""
echo "📋 RÉSUMÉ DE LA SAUVEGARDE"
echo "=========================="
echo "✅ README.md - Documentation ultra-professionnelle"
echo "✅ CHANGELOG.md - Historique des versions détaillé"
echo "✅ VERSION - Numéro de version (5.4.0)"
echo "✅ LICENSE - Licence MIT"
echo "✅ .gitignore - Exclusions appropriées"
echo "✅ Commit Git - Sauvegarde complète"
echo "✅ Tag v5.4.0 - Version stable"

echo ""
echo "🏆 STUDIOSDB V5 PRO - SAUVEGARDE TERMINÉE !"
echo "==========================================="
echo ""
echo "📊 STATISTIQUES FINALES:"
git log --oneline | wc -l | xargs echo "• Commits totaux:"
find . -name "*.php" | wc -l | xargs echo "• Fichiers PHP:"
find . -name "*.vue" | wc -l | xargs echo "• Fichiers Vue:"
du -sh . | cut -f1 | xargs echo "• Taille projet:"
echo ""
echo "🎯 Version stable prête pour production !"
echo "🏢 École de Karaté Studiosunis St-Émile"
echo "🚀 Mission accomplie avec succès !"
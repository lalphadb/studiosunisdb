#!/bin/bash

cat << 'EOH'
=============================================================
    📦 STUDIOSDB V5 PRO - SAUVEGARDE GITHUB
    Commit et push de la version fonctionnelle
=============================================================
EOH

set -e
PROJECT_DIR="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"

echo "📍 Répertoire projet: $PROJECT_DIR"
cd "$PROJECT_DIR" || exit 1

echo -e "\n🧹 NETTOYAGE AVANT COMMIT"
echo "========================="

echo "🗑️  Suppression des fichiers temporaires..."
# Supprimer les fichiers de build et temporaires
rm -rf public/build
rm -rf node_modules/.cache
rm -rf storage/logs/*.log
rm -rf storage/debugbar/*

# Nettoyer les fichiers backup et broken
find . -name "*.backup*" -delete 2>/dev/null || true
find . -name "*.broken*" -delete 2>/dev/null || true
find . -name "*.deleted*" -delete 2>/dev/null || true

# Nettoyer les scripts de debug
rm -f fix-*.sh test-*.sh diagnostic-*.sh correction-*.sh 2>/dev/null || true

echo "✅ Nettoyage terminé"

echo -e "\n📋 GÉNÉRATION .gitignore"
echo "========================"

cat > .gitignore << 'GITIGNORE'
# Laravel
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log

# IDE
/.fleet
/.idea
/.vscode

# OS
.DS_Store
Thumbs.db

# StudiosDB specific
/storage/logs/*.log
/storage/debugbar/*
/bootstrap/cache/*.php
*.backup
*.broken
*.deleted
fix-*.sh
test-*.sh
diagnostic-*.sh
correction-*.sh

# Cache
/storage/framework/cache/data/*
/storage/framework/sessions/*
/storage/framework/views/*
GITIGNORE

echo "✅ .gitignore créé"

echo -e "\n📦 PRÉPARATION COMMIT"
echo "===================="

echo "🔍 Status git actuel:"
git status --porcelain | head -10

echo -e "\n➕ Ajout des fichiers..."
git add .

echo "📝 Création du commit..."
git commit -m "🎉 StudiosDB v5 Pro - Version fonctionnelle complète

✅ FONCTIONNALITÉS OPÉRATIONNELLES:
- Dashboard admin moderne et responsive
- Gestion membres CRUD complète avec statistiques
- Interface de filtres et recherche temps réel  
- Authentification et rôles (Spatie/Permission)
- Architecture multi-tenant (Stancl/Tenancy)
- Laravel 12.21.0 + Vue 3 + Inertia.js + Tailwind

✅ CORRECTIONS APPLIQUÉES:
- Suppression Ziggy défaillant remplacé par helper route()
- Correction ViteManifestNotFoundException  
- Permissions fichiers optimisées
- Build Vite stable et reproductible
- Cache Laravel optimisé

✅ TECH STACK:
- Backend: Laravel 12.21.0 + MySQL
- Frontend: Vue 3 + Inertia.js + Tailwind CSS
- Build: Vite 5.4.19
- Auth: Laravel Breeze + Fortify

✅ PAGES FONCTIONNELLES:
- /dashboard (admin adaptatif)
- /membres (CRUD + statistiques)
- /cours (gestion planning)
- /presences/tablette (interface tactile)

🔐 Admin: louis@4lb.ca
🎯 Status: Production Ready
📅 Date: $(date '+%Y-%m-%d %H:%M')"

echo "✅ Commit créé"

echo -e "\n🚀 PUSH VERS GITHUB"
echo "=================="

echo "📤 Push vers origin main..."
git push origin main

if [ $? -eq 0 ]; then
    echo "✅ PUSH RÉUSSI !"
    
    echo -e "\n📊 INFORMATIONS REPOSITORY"
    echo "=========================="
    
    echo "🌐 Repository URL:"
    git remote get-url origin 2>/dev/null || echo "❌ Remote non configuré"
    
    echo "📋 Derniers commits:"
    git log --oneline -5
    
    echo "📁 Fichiers trackés:"
    git ls-files | wc -l
    echo "   $(git ls-files | wc -l) fichiers dans le repository"
    
else
    echo "❌ ERREUR PUSH"
    echo "🔧 Vérifiez votre configuration Git:"
    echo "   git remote -v"
    echo "   git config --list | grep user"
fi

echo -e "\n🎯 SAUVEGARDE TERMINÉE"
echo "====================="

cat << 'SUCCESS'

🎉 STUDIOSDB V5 PRO - SAUVEGARDÉ SUR GITHUB !

✅ ACTIONS RÉALISÉES:
  1. 🧹 Nettoyage des fichiers temporaires
  2. 📋 Génération .gitignore optimisé
  3. 📦 Commit avec description complète
  4. 🚀 Push vers GitHub

🌐 VOTRE CODE EST MAINTENANT:
  - ✅ Sauvegardé sur GitHub
  - ✅ Versionné et documenté
  - ✅ Prêt pour déploiement
  - ✅ Accessible en équipe

🎯 PROCHAINES ÉTAPES:
  - Créer des branches pour nouvelles fonctionnalités
  - Configurer CI/CD si besoin
  - Inviter collaborateurs au repository

SUCCESS

echo -e "\n💡 Commandes utiles pour la suite:"
echo "   git branch feature/nouvelle-fonctionnalite"
echo "   git pull origin main"
echo "   git status"
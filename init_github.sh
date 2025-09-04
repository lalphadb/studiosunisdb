#!/bin/bash

# StudiosDB - Initialisation GitHub Repository
# Objectif: Connecter le code local au repository GitHub existant

set -e

echo "=== INITIALISATION GITHUB STUDIOSDB ==="

# 1. Vérifier que nous sommes dans le bon répertoire
cd /home/studiosdb/studiosunisdb
echo "✓ Répertoire: $(pwd)"

# 2. Configurer le remote origin
echo "📡 Configuration remote origin..."
git remote remove origin 2>/dev/null || echo "Pas de remote origin existant"
git remote add origin git@github.com:lalphadb/studiosunisdb.git
git remote -v

# 3. Vérifier la connexion
echo "🔐 Test connexion GitHub..."
git ls-remote origin

# 4. Créer fichier .gitignore spécifique Laravel si manquant
if [ ! -f .gitignore ] || ! grep -q "vendor/" .gitignore; then
    echo "📝 Mise à jour .gitignore..."
    cat >> .gitignore << 'EOF'

# Laravel
/vendor
/node_modules
/public/hot
/public/storage
/storage/*.key
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log

# IDE
/.vscode
/.idea

# OS
.DS_Store
Thumbs.db

# Custom
/backups
*.log
EOF
fi

# 5. Ajouter tous les fichiers (sauf gitignore)
echo "📦 Staging des fichiers..."
git add .

# 6. Commit initial
echo "💾 Commit initial..."
git commit -m "feat: initialisation StudiosDB Laravel 12

- Structure projet Laravel 12 + Inertia + Vue 3
- Modules: Dashboard, Cours (finalisés), Utilisateurs/Membres (TODO)  
- UI/UX: Responsive design + hover actions (référence)
- Sécurité: Spatie Permission + Policies + scoping école
- Stack: Laravel 12.*, Inertia, Vue 3, Tailwind, MySQL

Modules status:
- [FROZEN] Bootstrap sécurité + Dashboard + Cours  
- [TODO] Utilisateurs, Membres, Inscription self-service

Version: LEDGER v7"

# 7. Push initial
echo "🚀 Push vers GitHub..."
git push -u origin main

echo ""
echo "✅ SUCCÈS: Repository GitHub initialisé"
echo "🔗 URL: https://github.com/lalphadb/studiosunisdb"
echo "📊 Status: $(git status --porcelain | wc -l) fichiers trackés"

# 8. Vérification finale
echo ""
echo "=== VÉRIFICATION FINALE ==="
git remote -v
git branch -a
git log --oneline -3 2>/dev/null || echo "Premier commit effectué"

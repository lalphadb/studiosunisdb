#!/bin/bash

# 🥋 StudiosDB v5 Pro - Script de Backup Intelligent
# Sauvegarde tous les fichiers essentiels sans les fichiers générés

PROJECT_NAME="studiosdb_v5_pro"
BACKUP_DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_NAME="${PROJECT_NAME}_backup_${BACKUP_DATE}"
CURRENT_DIR=$(pwd)

echo "🚀 Démarrage backup StudiosDB v5 Pro..."
echo "📁 Projet: $PROJECT_NAME"
echo "📅 Date: $BACKUP_DATE"
echo "📂 Répertoire: $CURRENT_DIR"

# Créer le répertoire de backup temporaire
mkdir -p "/tmp/$BACKUP_NAME"

echo ""
echo "📋 Copie des fichiers essentiels..."

# 1. Configuration & Environment
echo "  ✅ Configuration Laravel..."
cp .env.example "/tmp/$BACKUP_NAME/" 2>/dev/null || echo "    ⚠️  .env.example introuvable"
cp .gitignore "/tmp/$BACKUP_NAME/" 2>/dev/null
cp .gitattributes "/tmp/$BACKUP_NAME/" 2>/dev/null
cp composer.json "/tmp/$BACKUP_NAME/"
cp composer.lock "/tmp/$BACKUP_NAME/"
cp package.json "/tmp/$BACKUP_NAME/"
cp package-lock.json "/tmp/$BACKUP_NAME/"
cp artisan "/tmp/$BACKUP_NAME/"

# 2. Configuration Build & Assets
echo "  ✅ Configuration Build..."
cp vite.config.js "/tmp/$BACKUP_NAME/" 2>/dev/null
cp tailwind.config.js "/tmp/$BACKUP_NAME/" 2>/dev/null
cp postcss.config.js "/tmp/$BACKUP_NAME/" 2>/dev/null
cp tsconfig.json "/tmp/$BACKUP_NAME/" 2>/dev/null
cp phpunit.xml "/tmp/$BACKUP_NAME/" 2>/dev/null

# 3. Répertoires essentiels Laravel
echo "  ✅ Code source Laravel..."
cp -r app "/tmp/$BACKUP_NAME/"
cp -r config "/tmp/$BACKUP_NAME/"
cp -r database "/tmp/$BACKUP_NAME/"
cp -r routes "/tmp/$BACKUP_NAME/"

# 4. Frontend (sans node_modules)
echo "  ✅ Code frontend..."
cp -r resources "/tmp/$BACKUP_NAME/"

# 5. Bootstrap (sans cache)
echo "  ✅ Bootstrap Laravel..."
mkdir -p "/tmp/$BACKUP_NAME/bootstrap"
cp bootstrap/app.php "/tmp/$BACKUP_NAME/bootstrap/" 2>/dev/null
cp bootstrap/providers.php "/tmp/$BACKUP_NAME/bootstrap/" 2>/dev/null

# 6. Public (sans build/)
echo "  ✅ Assets publics..."
mkdir -p "/tmp/$BACKUP_NAME/public"
cp public/index.php "/tmp/$BACKUP_NAME/public/"
cp public/favicon.ico "/tmp/$BACKUP_NAME/public/" 2>/dev/null
cp public/robots.txt "/tmp/$BACKUP_NAME/public/" 2>/dev/null
cp public/*.php "/tmp/$BACKUP_NAME/public/" 2>/dev/null

# 7. Tests
echo "  ✅ Tests..."
cp -r tests "/tmp/$BACKUP_NAME/" 2>/dev/null || echo "    ⚠️  Répertoire tests introuvable"

# 8. Documentation
echo "  ✅ Documentation..."
cp README.md "/tmp/$BACKUP_NAME/" 2>/dev/null
cp CHANGELOG.md "/tmp/$BACKUP_NAME/" 2>/dev/null
cp LICENSE "/tmp/$BACKUP_NAME/" 2>/dev/null
cp *.md "/tmp/$BACKUP_NAME/" 2>/dev/null

# 9. Scripts personnalisés
echo "  ✅ Scripts de projet..."
cp *.sh "/tmp/$BACKUP_NAME/" 2>/dev/null

# 10. Storage structure (sans logs/cache)
echo "  ✅ Structure Storage..."
mkdir -p "/tmp/$BACKUP_NAME/storage/app/public"
mkdir -p "/tmp/$BACKUP_NAME/storage/framework/sessions"
mkdir -p "/tmp/$BACKUP_NAME/storage/framework/views"
mkdir -p "/tmp/$BACKUP_NAME/storage/framework/cache"
mkdir -p "/tmp/$BACKUP_NAME/storage/logs"

# Copier les fichiers de storage importants (uploads, etc.)
if [ -d "storage/app/public" ]; then
    cp -r storage/app/public/* "/tmp/$BACKUP_NAME/storage/app/public/" 2>/dev/null
fi

# 11. Créer .gitkeep pour les répertoires vides
find "/tmp/$BACKUP_NAME/storage" -type d -empty -exec touch {}/.gitkeep \;

# 12. Nettoyage des fichiers temporaires
echo ""
echo "🧹 Nettoyage des fichiers temporaires..."
find "/tmp/$BACKUP_NAME" -name ".DS_Store" -delete 2>/dev/null
find "/tmp/$BACKUP_NAME" -name "Thumbs.db" -delete 2>/dev/null
find "/tmp/$BACKUP_NAME" -name "*.tmp" -delete 2>/dev/null

# 13. Création de l'archive
echo ""
echo "📦 Création de l'archive tar.gz..."
cd /tmp
tar -czf "${BACKUP_NAME}.tar.gz" "$BACKUP_NAME"

# 14. Déplacement vers le répertoire de travail
mv "${BACKUP_NAME}.tar.gz" "$CURRENT_DIR/"

# 15. Nettoyage temporaire
rm -rf "/tmp/$BACKUP_NAME"

# 16. Informations finales
BACKUP_SIZE=$(du -h "${CURRENT_DIR}/${BACKUP_NAME}.tar.gz" | cut -f1)
echo ""
echo "🎉 Backup terminé avec succès!"
echo "📁 Fichier: ${BACKUP_NAME}.tar.gz"
echo "📊 Taille: $BACKUP_SIZE"
echo "📂 Emplacement: ${CURRENT_DIR}/${BACKUP_NAME}.tar.gz"

echo ""
echo "📋 CONTENU DE LA SAUVEGARDE:"
echo "  ✅ Code source complet (app/, config/, routes/)"
echo "  ✅ Frontend Vue.js (resources/js, resources/css)"
echo "  ✅ Base de données (migrations, seeders, factories)"
echo "  ✅ Configuration (composer.json, package.json, vite.config.js)"
echo "  ✅ Documentation (README.md, audits, guides)"
echo "  ✅ Scripts personnalisés"
echo "  ✅ Structure storage (sans cache/logs)"
echo ""
echo "❌ EXCLU DE LA SAUVEGARDE:"
echo "  ❌ node_modules/ (npm install)"
echo "  ❌ vendor/ (composer install)"
echo "  ❌ public/build/ (npm run build)"
echo "  ❌ storage/logs/ (logs Laravel)"
echo "  ❌ storage/framework/cache/ (cache Laravel)"
echo "  ❌ .env (fichier sensible)"
echo ""
echo "🔄 POUR RESTAURER:"
echo "  1. tar -xzf ${BACKUP_NAME}.tar.gz"
echo "  2. cd ${BACKUP_NAME}"
echo "  3. composer install"
echo "  4. npm install"
echo "  5. cp .env.example .env"
echo "  6. php artisan key:generate"
echo "  7. php artisan migrate"
echo "  8. npm run build"

echo ""
echo "🥋 StudiosDB v5 Pro backup ready! 🚀"

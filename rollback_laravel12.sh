#!/bin/bash

# =============================================================================
# STUDIOSDB V5 PRO - ROLLBACK LARAVEL 12.x
# Retour à Laravel 11.x en cas de problème
# =============================================================================

echo "🔙 ROLLBACK LARAVEL 12.x - STUDIOSDB V5 PRO"
echo "=========================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Variables
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Trouver le dossier de backup le plus récent
echo "🔍 Recherche du backup le plus récent..."
BACKUP_DIR=$(find storage/app/ -name "laravel12_upgrade_backup_*" -type d | sort | tail -n1)

if [ -z "$BACKUP_DIR" ]; then
    echo -e "${RED}❌ Aucun backup trouvé!${NC}"
    echo "Impossible d'effectuer le rollback automatique."
    exit 1
fi

echo "📁 Backup trouvé: $BACKUP_DIR"

# Confirmation
read -p "⚠️  Êtes-vous sûr de vouloir revenir à Laravel 11.x? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Rollback annulé."
    exit 1
fi

echo -e "\n${YELLOW}🔙 DÉBUT DU ROLLBACK...${NC}"

# Arrêter les services
echo "⏹️ Arrêt des services..."
sudo pkill -f "php artisan serve" 2>/dev/null || true
sudo pkill -f "vite" 2>/dev/null || true

# Restaurer les fichiers
echo "📄 Restauration des fichiers..."
cp "$BACKUP_DIR/composer.json" ./
cp "$BACKUP_DIR/package.json" ./
[ -f "$BACKUP_DIR/composer.lock" ] && cp "$BACKUP_DIR/composer.lock" ./
[ -f "$BACKUP_DIR/package-lock.json" ] && cp "$BACKUP_DIR/package-lock.json" ./
[ -f "$BACKUP_DIR/.env" ] && cp "$BACKUP_DIR/.env" ./

# Nettoyer
echo "🧹 Nettoyage..."
rm -rf vendor/ node_modules/ public/build/

# Réinstaller
echo "📦 Réinstallation Laravel 11.x..."
composer install --no-interaction
npm install
npm run build

# Cache
echo "⚡ Optimisation..."
php artisan optimize:clear
php artisan config:cache

# Restaurer base de données si nécessaire
if [ -f "$BACKUP_DIR/database_backup.sql" ]; then
    read -p "🗄️  Voulez-vous restaurer la base de données? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "Restauration base de données..."
        DB_USER=$(grep DB_USERNAME .env | cut -d '=' -f2)
        DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f2)
        mysql -u "$DB_USER" -p "$DB_NAME" < "$BACKUP_DIR/database_backup.sql" 2>/dev/null || echo "⚠️ Restauration DB échouée"
    fi
fi

echo -e "\n${GREEN}✅ ROLLBACK TERMINÉ!${NC}"
echo "🚀 Lancez: php artisan serve --host=0.0.0.0 --port=8000"

# Vérification version
LARAVEL_VERSION=$(php artisan --version 2>/dev/null || echo "Version indisponible")
echo "Version Laravel: $LARAVEL_VERSION"

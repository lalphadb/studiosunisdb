#!/bin/bash

# Script de Maintenance Automatique StudiosDB
# ============================================
# À exécuter quotidiennement via cron

PROJECT_DIR="/home/studiosdb/studiosunisdb"
cd $PROJECT_DIR

# Configuration
MAX_LOG_SIZE="1M"  # Taille max des logs
MAX_LOG_LINES=2000  # Nombre de lignes à garder
TELESCOPE_RETENTION=48  # Heures à garder dans Telescope
BACKUP_RETENTION=30  # Jours à garder les backups

# Couleurs
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo "═══════════════════════════════════════"
echo "   MAINTENANCE STUDIOSDB - $(date)"
echo "═══════════════════════════════════════"

# 1. Rotation des logs Laravel
echo -e "\n${YELLOW}1. Rotation des logs Laravel...${NC}"
if [ -f "storage/logs/laravel.log" ]; then
    LOG_SIZE=$(du -sh storage/logs/laravel.log | cut -f1)
    echo "   Taille actuelle: $LOG_SIZE"
    
    # Si le log est trop gros, on le rotate
    if [ $(stat -c%s "storage/logs/laravel.log") -gt 1048576 ]; then
        # Archiver l'ancien log
        DATE=$(date +%Y%m%d)
        tail -$MAX_LOG_LINES storage/logs/laravel.log > storage/logs/laravel-$DATE.log
        
        # Garder seulement les dernières lignes
        tail -1000 storage/logs/laravel.log > storage/logs/laravel.log.tmp
        mv storage/logs/laravel.log.tmp storage/logs/laravel.log
        
        echo -e "   ${GREEN}✅ Log rotaté (gardé $MAX_LOG_LINES lignes)${NC}"
        
        # Supprimer les vieux logs archivés (plus de 7 jours)
        find storage/logs -name "laravel-*.log" -mtime +7 -delete
    else
        echo -e "   ${GREEN}✅ Taille acceptable${NC}"
    fi
fi

# 2. Nettoyage Telescope
echo -e "\n${YELLOW}2. Nettoyage Telescope...${NC}"
php artisan telescope:prune --hours=$TELESCOPE_RETENTION 2>/dev/null
echo -e "   ${GREEN}✅ Entrées de plus de ${TELESCOPE_RETENTION}h supprimées${NC}"

# 3. Nettoyage des caches Laravel
echo -e "\n${YELLOW}3. Nettoyage des caches expirés...${NC}"
php artisan cache:gc 2>/dev/null
find storage/framework/cache -type f -mtime +7 -delete 2>/dev/null
find storage/framework/sessions -type f -mtime +1 -delete 2>/dev/null
find storage/framework/views -type f -mtime +7 -delete 2>/dev/null
echo -e "   ${GREEN}✅ Caches expirés nettoyés${NC}"

# 4. Nettoyage du dossier backups
echo -e "\n${YELLOW}4. Nettoyage des vieux backups...${NC}"
if [ -d "backups" ]; then
    # Supprimer les backups de plus de 30 jours
    find backups -type f \( -name "*.tar.gz" -o -name "*.zip" -o -name "*.tar.xz" \) -mtime +$BACKUP_RETENTION -delete 2>/dev/null
    
    # Supprimer les dossiers vendor/node_modules dans les backups
    find backups -type d -name "vendor" -exec rm -rf {} + 2>/dev/null
    find backups -type d -name "node_modules" -exec rm -rf {} + 2>/dev/null
    
    BACKUP_SIZE=$(du -sh backups | cut -f1)
    echo -e "   ${GREEN}✅ Backups nettoyés (taille: $BACKUP_SIZE)${NC}"
fi

# 5. Nettoyage des fichiers temporaires
echo -e "\n${YELLOW}5. Nettoyage fichiers temporaires...${NC}"
find /tmp -name "vite-*" -mtime +1 -delete 2>/dev/null
find storage/app/backup-temp -type f -mtime +1 -delete 2>/dev/null
echo -e "   ${GREEN}✅ Fichiers temporaires supprimés${NC}"

# 6. Optimisation de la base de données (optionnel)
echo -e "\n${YELLOW}6. Optimisation base de données...${NC}"
php artisan db:monitor 2>/dev/null || echo "   ⚠️ Monitoring DB non disponible"

# 7. Rapport d'espace disque
echo -e "\n${YELLOW}7. Rapport d'espace disque:${NC}"
echo "   Projet: $(du -sh . 2>/dev/null | cut -f1)"
echo "   Storage: $(du -sh storage 2>/dev/null | cut -f1)"
echo "   Backups: $(du -sh backups 2>/dev/null | cut -f1)"
echo "   Logs: $(du -sh storage/logs 2>/dev/null | cut -f1)"

# 8. Créer un backup quotidien léger (DB seulement)
echo -e "\n${YELLOW}8. Backup quotidien...${NC}"
php artisan backup:run --only-db --disable-notifications 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "   ${GREEN}✅ Backup DB créé${NC}"
else
    echo "   ⚠️ Backup échoué ou non configuré"
fi

echo -e "\n═══════════════════════════════════════"
echo -e "${GREEN}✅ Maintenance terminée - $(date)${NC}"
echo "═══════════════════════════════════════"

# Optionnel: Logger dans un fichier
echo "[$(date)] Maintenance effectuée" >> storage/logs/maintenance.log

#!/bin/bash

# Script de backup MySQL pour StudiosDB
# Date: 2025-09-08

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/home/studiosdb/studiosunisdb/backups"
DB_NAME="studiosdb"
BACKUP_FILE="${BACKUP_DIR}/${DB_NAME}_backup_${TIMESTAMP}.sql"

# Créer le répertoire de backup s'il n'existe pas
mkdir -p "$BACKUP_DIR"

echo "📦 Début du backup de la base de données StudiosDB..."
echo "📍 Fichier de destination: $BACKUP_FILE"

# Utiliser les credentials du fichier .env
source /home/studiosdb/studiosunisdb/.env

# Effectuer le backup
mysqldump -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    --complete-insert \
    --routines \
    --triggers \
    --single-transaction \
    --skip-lock-tables \
    --databases "$DB_NAME" > "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    # Compresser le backup
    gzip "$BACKUP_FILE"
    COMPRESSED_FILE="${BACKUP_FILE}.gz"
    
    # Afficher les informations
    SIZE=$(du -h "$COMPRESSED_FILE" | cut -f1)
    echo "✅ Backup réussi!"
    echo "📄 Fichier: $COMPRESSED_FILE"
    echo "📊 Taille: $SIZE"
    
    # Nettoyer les vieux backups (garder les 10 derniers)
    echo "🧹 Nettoyage des anciens backups..."
    ls -t ${BACKUP_DIR}/${DB_NAME}_backup_*.sql.gz | tail -n +11 | xargs -r rm
    
    # Lister les backups disponibles
    echo ""
    echo "📋 Backups disponibles:"
    ls -lah ${BACKUP_DIR}/${DB_NAME}_backup_*.sql.gz 2>/dev/null | tail -5
else
    echo "❌ Erreur lors du backup!"
    exit 1
fi

echo ""
echo "💡 Pour restaurer: gunzip -c $COMPRESSED_FILE | mysql -u\$DB_USERNAME -p\$DB_PASSWORD $DB_NAME"

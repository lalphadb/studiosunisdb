#!/bin/bash

# Configuration
DB_NAME="studiosdb"
DB_USER="root"
BACKUP_DIR="database_backups"
DATE=$(date +%Y%m%d_%H%M%S)
KEEP_DAYS=30

# Créer le dossier de sauvegarde
mkdir -p $BACKUP_DIR

echo "🔄 Sauvegarde StudiosDB en cours..."

# 1. Sauvegarde complète
echo "📦 Export complet..."
mysqldump -u $DB_USER -p $DB_NAME > "$BACKUP_DIR/studiosdb_full_$DATE.sql"

# 2. Sauvegarde structure seulement
echo "🏗️ Export structure..."
mysqldump -u $DB_USER -p --no-data $DB_NAME > "$BACKUP_DIR/studiosdb_structure_$DATE.sql"

# 3. Sauvegarde données critiques
echo "🔑 Export données critiques..."
mysqldump -u $DB_USER -p --no-create-info $DB_NAME \
    users ecoles roles permissions model_has_roles model_has_permissions \
    > "$BACKUP_DIR/studiosdb_critical_data_$DATE.sql"

# 4. Compression
echo "🗜️ Compression..."
gzip "$BACKUP_DIR/studiosdb_full_$DATE.sql"
gzip "$BACKUP_DIR/studiosdb_structure_$DATE.sql"
gzip "$BACKUP_DIR/studiosdb_critical_data_$DATE.sql"

# 5. Nettoyage des anciennes sauvegardes
echo "🧹 Nettoyage anciennes sauvegardes (> $KEEP_DAYS jours)..."
find $BACKUP_DIR -name "*.sql.gz" -mtime +$KEEP_DAYS -delete

# 6. Git commit (optionnel)
read -p "Commiter dans Git? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    git add $BACKUP_DIR/
    git commit -m "💾 Auto backup $DATE"
    git push origin main
    echo "✅ Backup committé dans Git"
fi

echo "✅ Sauvegarde terminée : $DATE"
ls -lh $BACKUP_DIR/*$DATE*

#!/bin/bash

# Script pour nettoyer les fichiers de backup et les fichiers corrompus
# qui empêchent Laravel Pint de fonctionner

QUARANTINE_DIR="/home/studiosdb/studiosunisdb/quarantine/old_backups"
BACKUPS_DIR="/home/studiosdb/studiosunisdb/backups"

echo "🧹 Nettoyage des fichiers de backup et fichiers corrompus..."

# Créer le répertoire de quarantaine
mkdir -p "$QUARANTINE_DIR"

# Déplacer tous les répertoires de backup
for dir in "$BACKUPS_DIR"/backup_cours_fixed_* "$BACKUPS_DIR"/cleanup-phase* "$BACKUPS_DIR"/cours-scoping-fix* "$BACKUPS_DIR"/studiosdb_complete_* "$BACKUPS_DIR"/db_backup_* "$BACKUPS_DIR"/module_cours_legacy "$BACKUPS_DIR"/migration_manual; do
    if [ -e "$dir" ]; then
        echo "📦 Déplacement de $(basename "$dir")..."
        mv "$dir" "$QUARANTINE_DIR/" 2>/dev/null || echo "⚠️ Impossible de déplacer $dir"
    fi
done

# Garder seulement le backup SQL le plus récent
echo "💾 Conservation du backup SQL le plus récent..."
mv "$BACKUPS_DIR"/studiosdb_backup_20250908_180432.sql.gz /tmp/ 2>/dev/null

# Déplacer tous les scripts shell de backup
for file in "$BACKUPS_DIR"/*.sh; do
    if [ -f "$file" ]; then
        echo "📜 Déplacement du script $(basename "$file")..."
        mv "$file" "$QUARANTINE_DIR/" 2>/dev/null
    fi
done

# Déplacer les fichiers MD et SQL restants
for file in "$BACKUPS_DIR"/*.md "$BACKUPS_DIR"/*.sql; do
    if [ -f "$file" ]; then
        echo "📄 Déplacement de $(basename "$file")..."
        mv "$file" "$QUARANTINE_DIR/" 2>/dev/null
    fi
done

# Restaurer le backup SQL récent
mv /tmp/studiosdb_backup_20250908_180432.sql.gz "$BACKUPS_DIR/" 2>/dev/null

echo ""
echo "✅ Nettoyage terminé!"
echo "📍 Fichiers déplacés vers: $QUARANTINE_DIR"
echo "💾 Backup conservé: $BACKUPS_DIR/studiosdb_backup_20250908_180432.sql.gz"

# Lister ce qui reste dans backups
echo ""
echo "📋 Contenu restant dans $BACKUPS_DIR:"
ls -la "$BACKUPS_DIR"

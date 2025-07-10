#!/bin/bash

# Chemin vers la racine du projet
PROJECT_PATH="/home/studiosdb/studiosunisdb/studiosdb-v2"

# Dossier de sortie dans storage/audit
OUTPUT_DIR="$PROJECT_PATH/storage/audit"

# Création du dossier audit s’il n’existe pas
mkdir -p "$OUTPUT_DIR"

# Date au format AAAAMMJJ_HHMMSS
DATE=$(date +'%Y%m%d_%H%M%S')

# Nom du fichier avec date
OUTPUT_FILE="$OUTPUT_DIR/tree_output_$DATE.txt"

# Génération de l’arborescence jusqu’au niveau 6,
# en excluant les répertoires/fichiers indésirables
tree -L 6 \
  -I 'vendor|node_modules|storage/audit|public|bootstrap/cache|*.backup|*.env|*.log|*.bak|*.tmp|*.swp|*.zip|*.tar|*.gz|*.rar' \
  "$PROJECT_PATH" > "$OUTPUT_FILE"

echo "✅ Arborescence sauvegardée dans $OUTPUT_FILE"

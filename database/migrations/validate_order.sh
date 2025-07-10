#!/bin/bash

echo "🔍 Validation de l'ordre des migrations StudiosDB"
echo "================================================"

# Se déplacer dans le bon dossier
cd "$(dirname "$0")"

# Lister les migrations actuelles
echo -e "\n📋 MIGRATIONS EXISTANTES (dans l'ordre):"
ls -1 *.php | sort

echo -e "\n⚠️  PROBLÈME DÉTECTÉ:"
echo "Les migrations ont toutes le même timestamp (2025_07_06_203352) !"
echo "Cela peut causer des problèmes d'ordre lors du déploiement."

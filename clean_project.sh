#!/bin/bash

echo "🧹 NETTOYAGE PROJET STUDIOSUNISDB"
echo "================================"

# Supprimer fichiers de validation erronés
echo "🗑️ Suppression fichiers validation parasites..."
rm -f 'nullable|date,'
rm -f 'nullable|email|max:255,'
rm -f 'nullable|string,'
rm -f 'nullable|string|max:20,'
rm -f 'nullable|string|max:255,'
rm -f 'required|date,'
rm -f 'required|exists:ecoles,id,'
rm -f 'required|in:actif,inactif,suspendu,'
rm -f 'required|string|max:100,'

# Supprimer autres fichiers temporaires
echo "🗑️ Suppression fichiers temporaires..."
rm -f *.tmp
rm -f *.log
rm -f .DS_Store
rm -f Thumbs.db

# Nettoyer cache Laravel
echo "🧹 Nettoyage cache Laravel..."
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear

# Nettoyer storage/logs si volumineux
echo "🗂️ Nettoyage logs..."
find storage/logs -name "*.log" -mtime +7 -delete 2>/dev/null || true

# Nettoyer node_modules si problème
if [ -d "node_modules" ] && [ $(du -sm node_modules | cut -f1) -gt 500 ]; then
    echo "⚠️ node_modules volumineux ($(du -sh node_modules | cut -f1)) - Reconstruire ?"
    echo "Pour nettoyer: rm -rf node_modules && npm install"
fi

echo "✅ Nettoyage terminé !"
echo ""
echo "📊 Espace utilisé après nettoyage:"
du -sh . 2>/dev/null || echo "Calcul impossible"


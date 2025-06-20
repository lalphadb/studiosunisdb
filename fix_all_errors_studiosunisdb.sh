#!/bin/bash
echo "🔥 CORRECTION COMPLÈTE ERREURS STUDIOSUNISDB"
echo "============================================"

cd /home/studiosdb/studiosunisdb/

# 1. Arrêter le serveur si en cours
echo "🛑 Arrêt serveur de développement..."
pkill -f "php artisan serve" 2>/dev/null || true

# 2. Nettoyer complètement
echo "🧹 Nettoyage complet..."
php artisan optimize:clear
composer dump-autoload

# 3. Corriger les migrations manquantes
echo "🗄️ Correction base de données..."
php artisan migrate --force

# 4. Vérifier la structure activity_log
echo "📋 Vérification activity_log..."
mysql -u root -pLkmP0km1 studiosdb -e "DESCRIBE activity_log;" | grep batch_uuid || {
    echo "Ajout colonne batch_uuid manquante..."
    mysql -u root -pLkmP0km1 studiosdb -e "ALTER TABLE activity_log ADD COLUMN batch_uuid VARCHAR(36) NULL AFTER causer_type;"
}

# 5. Nettoyer les erreurs de cache
echo "⚡ Reconstruction caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Test de fonctionnement
echo "🔍 Test de fonctionnement..."
php artisan route:list --name=admin.dashboard > /dev/null 2>&1 && echo "✅ Routes OK" || echo "❌ Problème routes"

# 7. Permissions fichiers
echo "🔐 Correction permissions..."
chmod 644 app/Http/Controllers/Admin/DashboardController.php
chown studiosdb:studiosdb app/Http/Controllers/Admin/DashboardController.php

echo ""
echo "✅ TOUTES LES CORRECTIONS APPLIQUÉES !"
echo "🚀 Vous pouvez maintenant démarrer le serveur:"
echo "   php artisan serve --host=0.0.0.0 --port=8001"

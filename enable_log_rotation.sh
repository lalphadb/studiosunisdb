#!/bin/bash

# Activation de la rotation quotidienne des logs
sed -i 's/LOG_CHANNEL=stack/LOG_CHANNEL=daily/' /home/studiosdb/studiosunisdb/.env

echo "✅ Rotation quotidienne des logs activée"
echo "Les logs seront maintenant créés quotidiennement:"
echo "  - storage/logs/laravel-YYYY-MM-DD.log"
echo "  - Suppression automatique après 14 jours"

# Nettoyer le cache de configuration
cd /home/studiosdb/studiosunisdb
php artisan config:clear
php artisan config:cache

echo ""
echo "✅ Configuration mise à jour et cache rafraîchi"

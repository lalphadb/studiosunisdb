#!/bin/bash

echo "CORRECTION ERREUR MONOLOG"
echo "========================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Supprimer les logs corrompus
echo "1. Nettoyage logs..."
sudo rm -f storage/logs/laravel.log
sudo rm -f storage/logs/*.log

# 2. Recreer le fichier de log
echo "2. Recreation logs..."
sudo touch storage/logs/laravel.log
sudo chown www-data:www-data storage/logs/laravel.log
sudo chmod 644 storage/logs/laravel.log

# 3. Permissions storage
echo "3. Permissions storage..."
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/

# 4. Test ecriture log
echo "4. Test log..."
php artisan tinker --execute="Log::info('Test log StudiosDB v5');"

# 5. Verification
echo "5. Verification..."
ls -la storage/logs/
tail -5 storage/logs/laravel.log 2>/dev/null || echo "Fichier log vide ou inexistant"

echo "CORRECTION MONOLOG TERMINEE"
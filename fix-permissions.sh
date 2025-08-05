#!/bin/bash

echo "CORRECTION PERMISSIONS COMPLETE"
echo "==============================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# 1. Arrêter les services web
echo "1. Arrêt des services..."
sudo systemctl stop nginx php8.3-fpm

# 2. Changer TOUS les propriétaires
echo "2. Changement propriétaires..."
sudo chown -R studiosdb:studiosdb .
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chown -R www-data:www-data public/

# 3. Permissions larges pour storage
echo "3. Permissions storage..."
sudo chmod -R 777 storage/
sudo chmod -R 777 bootstrap/cache/

# 4. Supprimer et recréer le log
echo "4. Recreation log..."
sudo rm -f storage/logs/laravel.log
sudo touch storage/logs/laravel.log
sudo chmod 666 storage/logs/laravel.log
sudo chown www-data:www-data storage/logs/laravel.log

# 5. SELinux - désactiver si actif
echo "5. Vérification SELinux..."
if command -v getenforce >/dev/null 2>&1; then
    sudo setenforce 0 2>/dev/null || echo "SELinux déjà désactivé"
fi

# 6. Test écriture manuelle
echo "6. Test écriture..."
echo "Test StudiosDB v5" | sudo tee storage/logs/laravel.log > /dev/null
cat storage/logs/laravel.log

# 7. Redémarrer services
echo "7. Redémarrage services..."
sudo systemctl start php8.3-fpm nginx
sudo systemctl status php8.3-fpm --no-pager -l
sudo systemctl status nginx --no-pager -l

echo "PERMISSIONS CORRIGEES"
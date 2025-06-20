#!/bin/bash
echo "🥋 Configuration Finale Telescope StudiosUnisDB"
echo "=============================================="

cd /home/studiosdb/studiosunisdb/

# Installer Telescope
php artisan telescope:install --force
php artisan telescope:publish --force

# Migrer
php artisan migrate --force

# Optimiser
php artisan optimize:clear
php artisan optimize

# Tester
php artisan route:list > /dev/null
echo "✅ Configuration terminée !"
echo "🔗 URL: https://4lb.ca/telescope"
echo "🎯 Widget intégré au dashboard SuperAdmin"

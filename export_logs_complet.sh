#!/bin/bash

echo "🗂️ EXPORT COMPLET LOGS StudiosUnisDB"
echo "====================================="

# Créer dossier d'export avec timestamp
EXPORT_DIR="logs_export_$(date +%Y%m%d_%H%M%S)"
mkdir -p $EXPORT_DIR

cd /home/studiosdb/studiosunisdb/

echo "📁 Création du dossier : $EXPORT_DIR"

# 1. Laravel Logs
echo "📋 Export Laravel logs..."
if [ -f "storage/logs/laravel.log" ]; then
    cp storage/logs/laravel.log $EXPORT_DIR/
    tail -n 1000 storage/logs/laravel.log > $EXPORT_DIR/laravel_recent.log
fi

# 2. Logs par date (dernières 48h)
echo "📅 Export logs récents..."
find storage/logs/ -name "*.log" -mtime -2 -exec cp {} $EXPORT_DIR/ \; 2>/dev/null

# 3. Erreurs uniquement
echo "❌ Filtrage erreurs..."
grep -E "(ERROR|CRITICAL|EMERGENCY|EXCEPTION)" storage/logs/laravel.log > $EXPORT_DIR/errors_only.txt 2>/dev/null

# 4. État système
echo "💻 État système..."
echo "Date: $(date)" > $EXPORT_DIR/system_info.txt
echo "Uptime: $(uptime)" >> $EXPORT_DIR/system_info.txt
echo "Espace disque:" >> $EXPORT_DIR/system_info.txt
df -h >> $EXPORT_DIR/system_info.txt
echo "Mémoire:" >> $EXPORT_DIR/system_info.txt
free -h >> $EXPORT_DIR/system_info.txt

# 5. Laravel info
echo "🚀 Info Laravel..."
php artisan --version > $EXPORT_DIR/laravel_version.txt 2>/dev/null
php artisan route:list > $EXPORT_DIR/routes_list.txt 2>/dev/null
php artisan config:show app > $EXPORT_DIR/app_config.txt 2>/dev/null

# 6. Base de données
echo "🗄️ Info base de données..."
mysql -u root -pLkmP0km1 -e "SHOW DATABASES;" > $EXPORT_DIR/mysql_databases.txt 2>/dev/null
mysql -u root -pLkmP0km1 studiosdb -e "SHOW TABLES;" > $EXPORT_DIR/mysql_tables.txt 2>/dev/null

# 7. Nginx logs (si disponibles)
echo "🌐 Logs Nginx..."
if [ -f "/var/log/nginx/error.log" ]; then
    sudo tail -n 500 /var/log/nginx/error.log > $EXPORT_DIR/nginx_errors.log 2>/dev/null
fi

# 8. Résumé
echo "📊 Génération résumé..."
cat > $EXPORT_DIR/RESUME.txt << 'RESUME_EOF'
EXPORT LOGS StudiosUnisDB
========================

Contenu de cet export:
- laravel.log : Logs Laravel complets
- laravel_recent.log : 1000 dernières lignes
- errors_only.txt : Erreurs uniquement
- system_info.txt : État système
- laravel_version.txt : Version Laravel
- routes_list.txt : Liste des routes
- mysql_*.txt : Info base de données

Généré le: $(date)
Serveur: $(hostname)
RESUME_EOF

# 9. Compression
echo "🗜️ Compression..."
tar -czf ${EXPORT_DIR}.tar.gz $EXPORT_DIR/

echo ""
echo "✅ EXPORT TERMINÉ !"
echo "📁 Dossier: $EXPORT_DIR"
echo "📦 Archive: ${EXPORT_DIR}.tar.gz"
echo "📊 Fichiers exportés:"
ls -la $EXPORT_DIR/
echo ""
echo "💡 Pour consulter:"
echo "   - cat $EXPORT_DIR/RESUME.txt"
echo "   - tail -f $EXPORT_DIR/laravel_recent.log"
echo "   - cat $EXPORT_DIR/errors_only.txt"

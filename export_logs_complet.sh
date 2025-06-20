#!/bin/bash
echo "📊 Export Complet Logs StudiosUnisDB"
echo "===================================="

cd /home/studiosdb/studiosunisdb/

# Créer dossier d'export
EXPORT_DIR="logs_export_$(date +%Y%m%d_%H%M%S)"
mkdir -p $EXPORT_DIR

# 1. Logs Laravel
echo "📝 Export logs Laravel..."
cp storage/logs/laravel.log $EXPORT_DIR/laravel.log 2>/dev/null || echo "Pas de logs Laravel"

# 2. Logs système
echo "🖥️ Export logs système..."
journalctl -u nginx --since "24 hours ago" > $EXPORT_DIR/nginx_system.log 2>/dev/null || echo "Nginx logs non disponibles"
tail -n 200 /var/log/nginx/error.log > $EXPORT_DIR/nginx_errors.log 2>/dev/null || echo "Nginx error logs non disponibles"

# 3. Logs MySQL
echo "🗄️ Export logs MySQL..."
sudo tail -n 100 /var/log/mysql/error.log > $EXPORT_DIR/mysql_errors.log 2>/dev/null || echo "MySQL logs non disponibles"

# 4. Status services
echo "⚙️ Status des services..."
systemctl status nginx > $EXPORT_DIR/nginx_status.txt
systemctl status mysql > $EXPORT_DIR/mysql_status.txt
php -v > $EXPORT_DIR/php_version.txt

# 5. Info système
echo "💻 Informations système..."
df -h > $EXPORT_DIR/disk_usage.txt
free -h > $EXPORT_DIR/memory_usage.txt
uptime > $EXPORT_DIR/uptime.txt

# 6. Logs application spécifiques
echo "🥋 Logs StudiosUnisDB spécifiques..."
php artisan route:list > $EXPORT_DIR/routes_list.txt 2>&1
php artisan migrate:status > $EXPORT_DIR/migrate_status.txt 2>&1

# Créer un résumé
echo "📋 Création résumé..."
cat > $EXPORT_DIR/RESUME.txt << 'RESUME'
📊 EXPORT LOGS STUDIOSUNISDB
============================

Date: $(date)
Serveur: $(hostname)
Version Laravel: $(php artisan --version 2>/dev/null || echo "Non disponible")

CONTENU DE L'EXPORT:
- laravel.log : Logs application Laravel
- nginx_system.log : Logs système Nginx
- nginx_errors.log : Erreurs Nginx
- mysql_errors.log : Erreurs MySQL
- *_status.txt : Status des services
- *_usage.txt : Utilisation ressources système
- routes_list.txt : Liste des routes
- migrate_status.txt : Status migrations

PROCHAINES ÉTAPES:
1. Analyser les erreurs dans laravel.log
2. Vérifier les erreurs 404/500 dans nginx_errors.log
3. Contrôler les erreurs MySQL
4. Vérifier l'espace disque

RESUME

# Compresser l'export
echo "📦 Compression..."
tar -czf ${EXPORT_DIR}.tar.gz $EXPORT_DIR/
echo "✅ Export terminé: ${EXPORT_DIR}.tar.gz"
echo "📂 Taille: $(du -h ${EXPORT_DIR}.tar.gz | cut -f1)"

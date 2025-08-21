#!/bin/bash
# 🔍 COMMANDES LINUX DE VÉRIFICATION - STUDIOSDB V5 PRO

echo "🚀 VÉRIFICATION SYSTÈME STUDIOSDB V5 PRO"
echo "========================================"

# 1. VÉRIFICATION STRUCTURE PROJET
echo "📁 Structure du projet:"
ls -la /home/studiosdb/studiosunisdb/studiosdb_v5_pro/

echo -e "\n📊 Taille des dossiers principaux:"
du -sh /home/studiosdb/studiosunisdb/studiosdb_v5_pro/{app,resources,database,public} 2>/dev/null || echo "Certains dossiers n'existent pas"

# 2. VÉRIFICATION BASE DE DONNÉES
echo -e "\n🗄️ État des bases de données:"
mysql -u studiosdb -p -e "SHOW DATABASES LIKE 'studiosdb%';" 2>/dev/null || echo "Erreur de connexion MySQL"

echo -e "\n📋 Tables dans studiosdb_central:"
mysql -u studiosdb -p studiosdb_central -e "SHOW TABLES;" 2>/dev/null || echo "Base studiosdb_central n'existe pas"

echo -e "\n🔍 Vérification table membres:"
mysql -u studiosdb -p studiosdb_central -e "DESCRIBE membres;" 2>/dev/null || echo "❌ Table membres n'existe pas"

# 3. VÉRIFICATION FICHIERS LARAVEL
echo -e "\n🔧 Fichiers Laravel critiques:"
files=(
    "/home/studiosdb/studiosunisdb/studiosdb_v5_pro/.env"
    "/home/studiosdb/studiosunisdb/studiosdb_v5_pro/composer.json"
    "/home/studiosdb/studiosunisdb/studiosdb_v5_pro/package.json"
    "/home/studiosdb/studiosunisdb/studiosdb_v5_pro/artisan"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file existe"
    else
        echo "❌ $file manquant"
    fi
done

# 4. VÉRIFICATION PERMISSIONS
echo -e "\n🔐 Permissions critiques:"
stat -c "%a %n" /home/studiosdb/studiosunisdb/studiosdb_v5_pro/{storage,bootstrap/cache} 2>/dev/null || echo "Dossiers storage/cache à vérifier"

# 5. VÉRIFICATION SERVICES
echo -e "\n⚙️ Services système:"
services=("nginx" "mysql" "php8.3-fpm" "redis-server")
for service in "${services[@]}"; do
    if systemctl is-active --quiet $service; then
        echo "✅ $service: ACTIF"
    else
        echo "❌ $service: INACTIF"
    fi
done

# 6. VÉRIFICATION LOGS
echo -e "\n📝 Logs récents Laravel:"
if [ -f "/home/studiosdb/studiosunisdb/studiosdb_v5_pro/storage/logs/laravel.log" ]; then
    echo "Dernières 5 lignes d'erreur:"
    tail -n 5 /home/studiosdb/studiosunisdb/studiosdb_v5_pro/storage/logs/laravel.log | grep -i error || echo "Aucune erreur récente"
else
    echo "❌ Fichier de log Laravel introuvable"
fi

# 7. VÉRIFICATION PHP
echo -e "\n🐘 Configuration PHP:"
php -v | head -1
echo "Extensions chargées:"
php -m | grep -E "(mysql|redis|gd|curl|zip)" || echo "Extensions manquantes"

# 8. VÉRIFICATION COMPOSER/NPM
echo -e "\n📦 Dépendances:"
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro 2>/dev/null && {
    echo "Composer vendor:"
    [ -d "vendor" ] && echo "✅ vendor/ existe" || echo "❌ vendor/ manquant"
    
    echo "NPM node_modules:"
    [ -d "node_modules" ] && echo "✅ node_modules/ existe" || echo "❌ node_modules/ manquant"
} || echo "❌ Impossible d'accéder au répertoire projet"

echo -e "\n✨ Vérification terminée!"

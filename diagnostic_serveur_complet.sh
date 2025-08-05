#!/bin/bash

echo "🔍 DIAGNOSTIC COMPLET SERVEUR STUDIOSDB V5 PRO"
echo "=============================================="

# 1. INFORMATIONS SYSTÈME
echo "📊 1. INFORMATIONS SYSTÈME:"
echo "   OS: $(lsb_release -d | cut -f2)"
echo "   Kernel: $(uname -r)"
echo "   Uptime: $(uptime -p)"
echo "   Load Average: $(uptime | awk -F'average:' '{print $2}')"
echo ""

# 2. RESSOURCES SYSTÈME
echo "💾 2. RESSOURCES SYSTÈME:"
echo "   RAM:"
free -h | grep -E "(Mem|Swap)"
echo ""
echo "   DISQUE:"
df -h /home/studiosdb/ | tail -1
echo ""

# 3. SERVICES CRITIQUES
echo "⚙️  3. SERVICES CRITIQUES:"

# MySQL
if systemctl is-active --quiet mysql; then
    echo "   ✅ MySQL: Actif ($(mysql --version | awk '{print $5}' | cut -d',' -f1))"
else
    echo "   ❌ MySQL: Inactif"
fi

# Nginx
if systemctl is-active --quiet nginx; then
    echo "   ✅ Nginx: Actif ($(nginx -v 2>&1 | awk '{print $3}'))"
else
    echo "   ❌ Nginx: Inactif"
fi

# PHP-FPM
if systemctl is-active --quiet php8.3-fpm; then
    echo "   ✅ PHP-FPM: Actif ($(php --version | head -1 | awk '{print $2}'))"
else
    echo "   ❌ PHP-FPM: Inactif"
fi

# Redis
if systemctl is-active --quiet redis-server; then
    echo "   ✅ Redis: Actif"
else
    echo "   ❌ Redis: Inactif"
fi

echo ""

# 4. PROCESSUS STUDIOSDB
echo "🚀 4. PROCESSUS STUDIOSDB:"
LARAVEL_PID=$(pgrep -f "php artisan serve")
if [ ! -z "$LARAVEL_PID" ]; then
    echo "   ✅ Laravel Serve: Actif (PID: $LARAVEL_PID)"
else
    echo "   ❌ Laravel Serve: Inactif"
fi

VITE_PID=$(pgrep -f "npm run dev")
if [ ! -z "$VITE_PID" ]; then
    echo "   ✅ Vite HMR: Actif (PID: $VITE_PID)"
else
    echo "   ❌ Vite HMR: Inactif"
fi

NODE_PID=$(pgrep -f "node")
if [ ! -z "$NODE_PID" ]; then
    echo "   ✅ Node.js: Actif"
else
    echo "   ❌ Node.js: Inactif"
fi

echo ""

# 5. VÉRIFICATION FICHIERS PROJET
echo "📁 5. FICHIERS PROJET:"
PROJECT_DIR="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"

if [ -d "$PROJECT_DIR" ]; then
    echo "   ✅ Répertoire projet: Existe"
    echo "   📊 Taille: $(du -sh $PROJECT_DIR | awk '{print $1}')"
    
    # Vérifier fichiers critiques
    [ -f "$PROJECT_DIR/.env" ] && echo "   ✅ .env: Présent" || echo "   ❌ .env: Manquant"
    [ -f "$PROJECT_DIR/composer.json" ] && echo "   ✅ composer.json: Présent" || echo "   ❌ composer.json: Manquant"
    [ -f "$PROJECT_DIR/package.json" ] && echo "   ✅ package.json: Présent" || echo "   ❌ package.json: Manquant"
    
    # Vérifier répertoires critiques
    [ -d "$PROJECT_DIR/vendor" ] && echo "   ✅ vendor/: Présent" || echo "   ❌ vendor/: Manquant"
    [ -d "$PROJECT_DIR/node_modules" ] && echo "   ✅ node_modules/: Présent" || echo "   ❌ node_modules/: Manquant"
    
else
    echo "   ❌ Répertoire projet: Manquant"
fi

echo ""

# 6. PERMISSIONS
echo "🔐 6. PERMISSIONS:"
if [ -d "$PROJECT_DIR" ]; then
    STORAGE_PERM=$(stat -c "%a" "$PROJECT_DIR/storage" 2>/dev/null || echo "N/A")
    BOOTSTRAP_CACHE_PERM=$(stat -c "%a" "$PROJECT_DIR/bootstrap/cache" 2>/dev/null || echo "N/A")
    
    echo "   📁 storage/: $STORAGE_PERM"
    echo "   📁 bootstrap/cache/: $BOOTSTRAP_CACHE_PERM"
    
    # Vérifier propriétaire
    PROJECT_OWNER=$(stat -c "%U:%G" "$PROJECT_DIR" 2>/dev/null || echo "N/A")
    echo "   👤 Propriétaire: $PROJECT_OWNER"
fi

echo ""

# 7. BASE DE DONNÉES
echo "🗄️  7. BASE DE DONNÉES:"
if command -v mysql &> /dev/null; then
    # Lister les bases StudiosDB
    DBS=$(mysql -u root -e "SHOW DATABASES;" 2>/dev/null | grep studiosdb | wc -l)
    echo "   📊 Bases StudiosDB: $DBS trouvées"
    
    mysql -u root -e "SHOW DATABASES;" 2>/dev/null | grep studiosdb | while read db; do
        echo "   ✅ $db"
    done
else
    echo "   ❌ MySQL client non disponible"
fi

echo ""

# 8. LOGS RÉCENTS
echo "📝 8. LOGS RÉCENTS (5 dernières lignes):"
if [ -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
    echo "   📄 Laravel Log:"
    tail -5 "$PROJECT_DIR/storage/logs/laravel.log" | sed 's/^/      /'
else
    echo "   ❌ Pas de logs Laravel"
fi

echo ""

# 9. PORTS RÉSEAU
echo "🌐 9. PORTS RÉSEAU:"
echo "   Port 8000 (Laravel): $(netstat -tlnp 2>/dev/null | grep :8000 | wc -l) connexion(s)"
echo "   Port 5173 (Vite): $(netstat -tlnp 2>/dev/null | grep :5173 | wc -l) connexion(s)"
echo "   Port 80 (Nginx): $(netstat -tlnp 2>/dev/null | grep :80 | wc -l) connexion(s)"
echo "   Port 3306 (MySQL): $(netstat -tlnp 2>/dev/null | grep :3306 | wc -l) connexion(s)"

echo ""

# 10. RECOMMANDATIONS
echo "💡 10. RECOMMANDATIONS:"

# Vérifier si services sont démarrés
if ! systemctl is-active --quiet mysql; then
    echo "   🔧 Démarrer MySQL: sudo systemctl start mysql"
fi

if ! systemctl is-active --quiet nginx; then
    echo "   🔧 Démarrer Nginx: sudo systemctl start nginx"
fi

if [ -z "$LARAVEL_PID" ]; then
    echo "   🔧 Démarrer Laravel: cd $PROJECT_DIR && php artisan serve --host=0.0.0.0 --port=8000 &"
fi

if [ -z "$VITE_PID" ]; then
    echo "   🔧 Démarrer Vite: cd $PROJECT_DIR && npm run dev &"
fi

echo ""
echo "🏁 DIAGNOSTIC TERMINÉ"
echo "===================="


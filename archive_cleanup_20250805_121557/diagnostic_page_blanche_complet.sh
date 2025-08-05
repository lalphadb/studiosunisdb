#!/bin/bash

# 🔍 DIAGNOSTIC COMPLET STUDIOSDB v5 - ÉTAT SERVEUR
# Analyse tous les aspects pour identifier les problèmes

set -e
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🔍 DIAGNOSTIC COMPLET STUDIOSDB v5"
echo "=================================="
echo "Timestamp: $(date)"
echo ""

# 1️⃣ ÉTAT SERVEUR
echo "🖥️  1. ÉTAT SERVEUR:"
echo "-------------------"
echo "OS: $(lsb_release -d 2>/dev/null | cut -f2 || echo 'Linux')"
echo "PHP: $(php -v | head -n1)"
echo "Node: $(node -v 2>/dev/null || echo 'Non installé')"
echo "npm: $(npm -v 2>/dev/null || echo 'Non installé')"
echo "MySQL: $(mysql --version 2>/dev/null | head -n1 || echo 'Non accessible')"
echo "Nginx: $(nginx -v 2>&1 | head -n1 || echo 'Non accessible')"
echo ""

# 2️⃣ ÉTAT PROCESSUS
echo "🔄 2. PROCESSUS ACTIFS:"
echo "----------------------"
echo "Laravel serve: $(pgrep -f 'artisan serve' | wc -l) processus"
echo "Nginx: $(pgrep nginx | wc -l) processus"  
echo "PHP-FPM: $(pgrep php-fpm | wc -l) processus"
echo "Node/Vite: $(pgrep -f vite | wc -l) processus"
echo ""

# 3️⃣ ÉTAT FICHIERS CRITIQUES
echo "📁 3. FICHIERS CRITIQUES:"
echo "-------------------------"
files=(
    "app/Http/Controllers/DashboardController.php"
    "resources/js/Pages/Dashboard/Admin.vue"
    "tailwind.config.js"
    "vite.config.js"
    "package.json"
    "composer.json"
    ".env"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        size=$(stat -c%s "$file" 2>/dev/null || echo "0")
        echo "✅ $file ($size bytes)"
    else
        echo "❌ $file (MANQUANT)"
    fi
done
echo ""

# 4️⃣ ANALYSE DASHBOARDCONTROLLER
echo "🎛️  4. DASHBOARDCONTROLLER:"
echo "--------------------------"
if [ -f "app/Http/Controllers/DashboardController.php" ]; then
    echo "Taille: $(stat -c%s app/Http/Controllers/DashboardController.php) bytes"
    echo "Lignes: $(wc -l < app/Http/Controllers/DashboardController.php)"
    
    # Test syntaxe PHP
    if php -l app/Http/Controllers/DashboardController.php > /dev/null 2>&1; then
        echo "✅ Syntaxe PHP: OK"
    else
        echo "❌ Syntaxe PHP: ERREUR"
        php -l app/Http/Controllers/DashboardController.php 2>&1 | head -5
    fi
    
    # Recherche erreurs courantes
    if grep -q "Division by zero" storage/logs/laravel.log 2>/dev/null; then
        echo "⚠️  Division par zéro détectée dans les logs"
    fi
    
    if grep -q "private.*expecting" storage/logs/laravel.log 2>/dev/null; then
        echo "⚠️  Erreur syntaxe 'private' détectée"
    fi
else
    echo "❌ DashboardController MANQUANT"
fi
echo ""

# 5️⃣ ÉTAT FRONTEND
echo "🎨 5. FRONTEND & ASSETS:"
echo "-----------------------"
echo "node_modules: $([ -d node_modules ] && echo "✅ Présent ($(du -sh node_modules 2>/dev/null | cut -f1))" || echo "❌ Manquant")"
echo "public/build: $([ -d public/build ] && echo "✅ Présent ($(ls public/build | wc -l) fichiers)" || echo "❌ Manquant")"

if [ -f "public/build/manifest.json" ]; then
    echo "✅ Manifest assets: OK"
    manifest_size=$(stat -c%s public/build/manifest.json)
    echo "   Taille: $manifest_size bytes"
else
    echo "❌ Manifest assets: MANQUANT"
fi

# Test Tailwind
if command -v npx &> /dev/null; then
    if npx tailwindcss --help > /dev/null 2>&1; then
        echo "✅ Tailwind CSS: Disponible"
    else
        echo "⚠️  Tailwind CSS: Problème"
    fi
fi
echo ""

# 6️⃣ LOGS D'ERREURS
echo "📋 6. LOGS D'ERREURS RÉCENTS:"
echo "-----------------------------"
if [ -f storage/logs/laravel.log ]; then
    echo "Taille log: $(stat -c%s storage/logs/laravel.log) bytes"
    echo "Dernières erreurs:"
    tail -n 10 storage/logs/laravel.log | grep -E "(ERROR|CRITICAL)" | head -5 || echo "Aucune erreur récente"
else
    echo "❌ Logs Laravel manquants"
fi
echo ""

# 7️⃣ BASE DE DONNÉES
echo "🗄️  7. BASE DE DONNÉES:"
echo "----------------------"
if php artisan tinker --execute="DB::connection()->getPdo()" > /dev/null 2>&1; then
    echo "✅ Connexion DB: OK"
    
    # Test tables
    tables=(membres cours presences paiements users)
    for table in "${tables[@]}"; do
        count=$(php artisan tinker --execute="echo DB::table('$table')->count();" 2>/dev/null | tail -1 || echo "0")
        if [[ "$count" =~ ^[0-9]+$ ]]; then
            echo "   $table: $count enregistrements"
        else
            echo "   $table: ❌ Erreur"
        fi
    done
else
    echo "❌ Connexion DB: ERREUR"
fi
echo ""

# 8️⃣ CACHE & PERFORMANCE
echo "⚡ 8. CACHE & PERFORMANCE:"
echo "-------------------------"
echo "Cache config: $([ -f bootstrap/cache/config.php ] && echo "✅ Présent" || echo "❌ Manquant")"
echo "Cache routes: $([ -f bootstrap/cache/routes-v7.php ] && echo "✅ Présent" || echo "❌ Manquant")"
echo "Cache views: $(find storage/framework/views -name "*.php" | wc -l) vues"
echo "Sessions: $(find storage/framework/sessions -type f | wc -l) fichiers"
echo ""

# 9️⃣ PERMISSIONS
echo "🔐 9. PERMISSIONS:"
echo "-----------------"
dirs=(storage bootstrap/cache public/build)
for dir in "${dirs[@]}"; do
    if [ -d "$dir" ]; then
        perms=$(stat -c%a "$dir" 2>/dev/null || echo "000")
        owner=$(stat -c%U:%G "$dir" 2>/dev/null || echo "unknown")
        echo "$dir: $perms ($owner)"
    fi
done
echo ""

# 🔟 PORTS & SERVICES
echo "🌐 10. PORTS & SERVICES:"
echo "-----------------------"
echo "Port 8000 (Laravel): $(netstat -tuln 2>/dev/null | grep :8000 | wc -l) écoutes"
echo "Port 80 (Nginx): $(netstat -tuln 2>/dev/null | grep :80 | wc -l) écoutes"
echo "Port 3306 (MySQL): $(netstat -tuln 2>/dev/null | grep :3306 | wc -l) écoutes"
echo ""

# 1️⃣1️⃣ RECOMMANDATIONS
echo "💡 11. RECOMMANDATIONS:"
echo "----------------------"

recommendations=()

# Vérifications et recommandations
if [ ! -f "public/build/manifest.json" ]; then
    recommendations+=("🔨 Recompiler les assets: npm run build")
fi

if ! php -l app/Http/Controllers/DashboardController.php > /dev/null 2>&1; then
    recommendations+=("🔧 Corriger syntaxe DashboardController")
fi

if grep -q "Division by zero" storage/logs/laravel.log 2>/dev/null; then
    recommendations+=("⚠️  Corriger division par zéro dans DashboardController")
fi

if [ ! -d "node_modules" ]; then
    recommendations+=("📦 Réinstaller dépendances: npm install")
fi

if [ ${#recommendations[@]} -eq 0 ]; then
    echo "✅ Aucun problème critique détecté"
else
    for rec in "${recommendations[@]}"; do
        echo "$rec"
    done
fi

echo ""
echo "🎯 RÉSUMÉ:"
echo "=========="
echo "Diagnostic terminé - $(date)"

if [ ${#recommendations[@]} -eq 0 ]; then
    echo "🟢 Statut: SYSTÈME SAIN"
    echo "🌐 Testez: http://studiosdb.local:8000/dashboard"
else
    echo "🟡 Statut: CORRECTIONS NÉCESSAIRES"
    echo "🔧 Exécutez: ./fix_page_blanche_definitif_final.sh"
fi
echo ""

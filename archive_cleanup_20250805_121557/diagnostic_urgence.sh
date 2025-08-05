#!/bin/bash

# 🔍 DIAGNOSTIC RAPIDE - ÉTAT CRITIQUE STUDIOSDB v5

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🔍 DIAGNOSTIC CRITIQUE STUDIOSDB v5"
echo "===================================="
echo "Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# PERMISSIONS CRITIQUES
echo "🔐 PERMISSIONS CRITIQUES:"
echo "========================"
echo "• storage/framework/views: $(ls -ld storage/framework/views 2>/dev/null || echo 'MANQUANT')"
echo "• bootstrap/cache: $(ls -ld bootstrap/cache 2>/dev/null || echo 'MANQUANT')"
echo "• public/build: $(ls -ld public/build 2>/dev/null || echo 'MANQUANT')"

# Test écriture
if [ -w "storage/framework/views" ]; then
    echo "✅ Écriture storage: OK"
else
    echo "❌ Écriture storage: BLOQUÉE"
fi

# ASSETS FRONTEND
echo ""
echo "🎨 ASSETS FRONTEND:"
echo "=================="
echo "• node_modules: $([ -d node_modules ] && echo "Présent ($(du -sh node_modules 2>/dev/null | cut -f1))" || echo "MANQUANT")"
echo "• public/build/manifest.json: $([ -f public/build/manifest.json ] && echo "OK ($(stat -c%s public/build/manifest.json) bytes)" || echo "MANQUANT")"
echo "• Fichiers build: $(ls public/build 2>/dev/null | wc -l) assets"

# PROCESSUS SERVEUR
echo ""
echo "🔄 PROCESSUS SERVEUR:"
echo "===================="
LARAVEL_PROC=$(pgrep -f "artisan serve" | wc -l)
NGINX_PROC=$(pgrep nginx | wc -l)
echo "• Laravel: $LARAVEL_PROC processus"
echo "• Nginx: $NGINX_PROC processus"

# PORTS RÉSEAU
echo ""
echo "🌐 PORTS RÉSEAU:"
echo "==============="
echo "• Port 8000: $(netstat -tuln 2>/dev/null | grep :8000 | wc -l) écoutes"
echo "• Port 8001: $(netstat -tuln 2>/dev/null | grep :8001 | wc -l) écoutes"

# LOGS ERREURS
echo ""
echo "📋 LOGS ERREURS:"
echo "==============="
if [ -f storage/logs/laravel.log ]; then
    echo "• Taille log: $(stat -c%s storage/logs/laravel.log) bytes"
    ERRORS=$(grep -c "ERROR\|Exception\|Permission denied" storage/logs/laravel.log 2>/dev/null || echo 0)
    echo "• Erreurs: $ERRORS"
    if [ $ERRORS -gt 0 ]; then
        echo "• Dernières erreurs:"
        grep -E "(ERROR|Exception|Permission denied)" storage/logs/laravel.log | tail -3
    fi
else
    echo "❌ Logs Laravel manquants"
fi

# TESTS CONNEXION
echo ""
echo "🧪 TESTS CONNEXION:"
echo "=================="

# Test ports locaux
for port in 8000 8001; do
    STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:$port 2>/dev/null || echo "000")
    echo "• localhost:$port → HTTP $STATUS"
done

# RECOMMANDATIONS
echo ""
echo "💡 RECOMMANDATIONS URGENTES:"
echo "============================"

recommendations=()

if [ ! -w "storage/framework/views" ]; then
    recommendations+=("🔐 CRITIQUE: Corriger permissions storage/")
fi

if [ ! -f "public/build/manifest.json" ]; then
    recommendations+=("🔨 CRITIQUE: Recompiler assets frontend")
fi

if [ $LARAVEL_PROC -eq 0 ]; then
    recommendations+=("🌐 Redémarrer serveur Laravel")
fi

if [ $ERRORS -gt 0 ]; then
    recommendations+=("📋 Analyser erreurs dans logs")
fi

if [ ${#recommendations[@]} -eq 0 ]; then
    echo "✅ Aucun problème critique détecté"
else
    for rec in "${recommendations[@]}"; do
        echo "$rec"
    done
fi

echo ""
echo "🚨 ACTION IMMÉDIATE:"
echo "==================="
if [ ! -w "storage/framework/views" ] || [ ! -f "public/build/manifest.json" ]; then
    echo "EXÉCUTEZ IMMÉDIATEMENT:"
    echo "chmod +x fix_urgence_permissions_assets.sh"
    echo "./fix_urgence_permissions_assets.sh"
else
    echo "✅ Système stable - testez les URLs"
fi

echo ""
echo "🌐 URLS À TESTER:"
echo "• http://studiosdb.local:8000/dashboard"
echo "• http://127.0.0.1:8000/dashboard"  
echo "• http://localhost:8000/dashboard"
echo ""

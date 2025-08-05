#!/bin/bash

echo "🔍 TEST RAPIDE AVANT CORRECTION"
echo "=============================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Test processus
echo "📊 PROCESSUS:"
LARAVEL_PID=$(pgrep -f "php artisan serve")
VITE_PID=$(pgrep -f "npm run dev")

if [ ! -z "$LARAVEL_PID" ]; then
    echo "   ✅ Laravel actif (PID: $LARAVEL_PID)"
else
    echo "   ❌ Laravel inactif"
fi

if [ ! -z "$VITE_PID" ]; then
    echo "   ✅ Vite actif (PID: $VITE_PID)"
else
    echo "   ❌ Vite inactif"
fi

# Test HTTP
echo ""
echo "🌐 TEST HTTP:"
if curl -f -s "http://localhost:8000/dashboard" > test_output.html 2>/dev/null; then
    echo "   ✅ Dashboard accessible"
    
    # Vérifier contenu
    if grep -q "StudiosDB" test_output.html; then
        echo "   ✅ Contenu présent"
    else
        echo "   ❌ Page blanche - pas de contenu"
        echo "   📄 Taille réponse: $(wc -c < test_output.html) bytes"
    fi
else
    echo "   ❌ Dashboard inaccessible"
fi

# Test assets
echo ""
echo "📦 ASSETS:"
if [ -d "public/build" ]; then
    FILES_COUNT=$(find public/build -name "*.js" -o -name "*.css" | wc -l)
    echo "   ✅ Build directory existe ($FILES_COUNT fichiers)"
else
    echo "   ❌ Build directory manquant"
fi

# Test fichiers critiques
echo ""
echo "📁 FICHIERS CRITIQUES:"
[ -f "resources/js/app.js" ] && echo "   ✅ app.js" || echo "   ❌ app.js"
[ -f "resources/js/Pages/Dashboard/Admin.vue" ] && echo "   ✅ Dashboard/Admin.vue" || echo "   ❌ Dashboard/Admin.vue"
[ -f "resources/js/Layouts/AuthenticatedLayout.vue" ] && echo "   ✅ AuthenticatedLayout.vue" || echo "   ❌ AuthenticatedLayout.vue"

echo ""
echo "🎯 PRÊT POUR CORRECTION AUTOMATIQUE"
echo "Lancer: ./fix_page_blanche_definitif_v2.sh"

# Nettoyer
rm -f test_output.html

exit 0

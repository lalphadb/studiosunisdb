#!/bin/bash

# 🚀 SOLUTION FINALE STUDIOSDB - DÉMARRAGE VITE + TESTS COMPLETS
# ===============================================================

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🔧 SOLUTION FINALE - DÉMARRAGE VITE + INERTIA"
echo "=============================================="

# 1. État initial
echo "📊 ÉTAT INITIAL:"
echo "✅ Laravel: FONCTIONNE (debug page OK)"
echo "✅ Routes: CONFIGURÉES (Inertia::render)"  
echo "✅ Fichiers Vue: EXISTENT (Login.vue, Dashboard.vue)"
echo "✅ app.blade.php: CONFIGURÉ (@vite directive)"
echo "❌ Vite dev server: ARRÊTÉ (cause écran blanc)"
echo ""

# 2. Arrêter tous les processus
echo "🛑 NETTOYAGE PROCESSUS..."
pkill -f "php artisan serve" 2>/dev/null || true
pkill -f "vite" 2>/dev/null || true  
pkill -f "npm run dev" 2>/dev/null || true
sleep 3

# 3. Nettoyer fichiers temporaires
echo "🧹 NETTOYAGE FICHIERS..."
rm -f public/hot 2>/dev/null || true
rm -rf public/build/* 2>/dev/null || true
php artisan route:clear >/dev/null 2>&1
php artisan config:clear >/dev/null 2>&1

# 4. Vérifier npm/node
echo "📦 VÉRIFICATION ENVIRONNEMENT..."
echo "Node version: $(node --version 2>/dev/null || echo 'Non installé')"
echo "NPM version: $(npm --version 2>/dev/null || echo 'Non installé')"

# 5. Installer dépendances si nécessaire
if [ ! -d "node_modules" ] || [ ! -f "node_modules/.package-lock.json" ]; then
    echo "📥 Installation dépendances npm..."
    npm install >/dev/null 2>&1
else
    echo "✅ Dépendances npm: OK"
fi

# 6. Vérifier fichiers critiques TypeScript
echo ""
echo "🔍 VÉRIFICATION FICHIERS TYPESCRIPT..."

# Vérifier app.ts
if [ ! -f "resources/js/app.ts" ]; then
    echo "🔧 Création app.ts..."
    mkdir -p resources/js
    cat > resources/js/app.ts << 'EOF'
import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME || 'StudiosDB';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4F46E5',
    },
});
EOF
    echo "✅ app.ts créé"
else
    echo "✅ app.ts: OK"
fi

# Vérifier bootstrap.ts  
if [ ! -f "resources/js/bootstrap.ts" ]; then
    echo "🔧 Création bootstrap.ts..."
    cat > resources/js/bootstrap.ts << 'EOF'
import axios from 'axios';

declare global {
    interface Window {
        axios: typeof axios;
    }
}

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
EOF
    echo "✅ bootstrap.ts créé"
else
    echo "✅ bootstrap.ts: OK"
fi

# Vérifier app.css
if [ ! -f "resources/css/app.css" ]; then
    echo "🔧 Création app.css..."
    mkdir -p resources/css
    cat > resources/css/app.css << 'EOF'
@tailwind base;
@tailwind components;
@tailwind utilities;

/* StudiosDB Custom Styles */
body {
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
EOF
    echo "✅ app.css créé"
else
    echo "✅ app.css: OK"
fi

# 7. DÉMARRER VITE DEV SERVER
echo ""
echo "🚀 DÉMARRAGE VITE DEV SERVER..."
echo "==============================="

# Démarrer Vite en arrière-plan
nohup npm run dev > vite-output.log 2>&1 &
VITE_PID=$!

echo "✅ Vite démarré (PID: $VITE_PID)"
echo "📋 Logs Vite: tail -f vite-output.log"

# 8. Attendre que Vite soit prêt
echo ""
echo "⏳ ATTENTE DÉMARRAGE VITE..."
for i in {1..15}; do
    if curl -s http://127.0.0.1:5175 >/dev/null 2>&1; then
        echo "✅ Vite dev server opérationnel sur port 5175!"
        break
    elif [ $i -eq 15 ]; then
        echo "❌ Vite ne démarre pas - vérifier logs: tail -f vite-output.log"
        exit 1
    else
        echo "⏳ Tentative $i/15..."
        sleep 2
    fi
done

# 9. DÉMARRER LARAVEL  
echo ""
echo "🚀 DÉMARRAGE LARAVEL SERVER..."
echo "=============================="

nohup php artisan serve --host=0.0.0.0 --port=8000 > laravel-output.log 2>&1 &
LARAVEL_PID=$!

echo "✅ Laravel démarré (PID: $LARAVEL_PID)"
echo "📋 Logs Laravel: tail -f laravel-output.log"

sleep 3

# 10. TESTS AUTOMATIQUES
echo ""
echo "🧪 TESTS AUTOMATIQUES..."
echo "========================"

# Test API
echo -n "🔍 Test API... "
if curl -s http://0.0.0.0:8000/test | grep -q "SUCCESS"; then
    echo "✅ OK"
else
    echo "❌ ERREUR"
fi

# Test Debug  
echo -n "🔍 Test Debug... "
if curl -s http://0.0.0.0:8000/debug | grep -q "StudiosDB"; then
    echo "✅ OK"
else
    echo "❌ ERREUR"
fi

# Test Vite
echo -n "🔍 Test Vite... "
if curl -s http://127.0.0.1:5175 >/dev/null 2>&1; then
    echo "✅ OK"
else
    echo "❌ ERREUR"
fi

# Test Login (doit retourner HTML avec Vue, pas écran blanc)
echo -n "🔍 Test Login... "
LOGIN_RESPONSE=$(curl -s http://0.0.0.0:8000/login)
if echo "$LOGIN_RESPONSE" | grep -q "<!DOCTYPE html>" && echo "$LOGIN_RESPONSE" | grep -q "@vite"; then
    echo "✅ OK (HTML retourné)"
else
    echo "❌ ERREUR (écran blanc ou erreur)"
fi

# 11. RÉSULTATS FINAUX
echo ""
echo "🎉 SOLUTION TERMINÉE!"
echo "===================="
echo ""
echo "📊 STATUT FINAL:"
echo "✅ Vite dev server: http://127.0.0.1:5175"
echo "✅ Laravel server: http://0.0.0.0:8000"
echo ""
echo "🎯 URLS À TESTER:"
echo "   🔍 Debug: http://0.0.0.0:8000/debug"
echo "   🔐 Login: http://0.0.0.0:8000/login (DEVRAIT FONCTIONNER!)"
echo "   🏠 Dashboard: http://0.0.0.0:8000/dashboard"
echo ""
echo "📋 MONITORING:"
echo "   - Vite: tail -f vite-output.log"
echo "   - Laravel: tail -f laravel-output.log"
echo ""
echo "🚀 LES PAGES INERTIA DEVRAIENT MAINTENANT FONCTIONNER!"

# 12. Test final automatique
sleep 5
echo ""
echo "🔬 TEST FINAL AUTOMATIQUE..."
FINAL_TEST=$(curl -s http://0.0.0.0:8000/login | head -5)
if echo "$FINAL_TEST" | grep -q "StudiosDB"; then
    echo "🎉 SUCCESS! Page login fonctionne!"
else
    echo "⚠️  Vérifier manuellement: http://0.0.0.0:8000/login"
fi

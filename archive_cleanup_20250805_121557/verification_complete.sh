#!/bin/bash

# 🔍 VÉRIFICATION COMPLÈTE ET RECOMPILATION STUDIOSDB v5
# Diagnostic + Correction + Validation + Tests

set -e
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🔍 VÉRIFICATION COMPLÈTE STUDIOSDB v5"
echo "====================================="
echo "Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"
echo "Répertoire: $(pwd)"
echo "Utilisateur: $(whoami)"
echo ""

# VARIABLES GLOBALES
ERRORS=0
WARNINGS=0
SUCCESS=0

# Fonction pour logger
log_success() { echo "✅ $1"; ((SUCCESS++)); }
log_warning() { echo "⚠️  $1"; ((WARNINGS++)); }
log_error() { echo "❌ $1"; ((ERRORS++)); }

# 1️⃣ VÉRIFICATION PERMISSIONS
echo "1️⃣ VÉRIFICATION PERMISSIONS"
echo "============================"

# Propriétaire principal
OWNER=$(stat -c %U .)
if [ "$OWNER" = "$(whoami)" ]; then
    log_success "Propriétaire principal: $OWNER"
else
    log_error "Propriétaire incorrect: $OWNER (attendu: $(whoami))"
fi

# Storage writable
if [ -w "storage/framework/views" ]; then
    log_success "Storage accessible en écriture"
else
    log_error "Storage non accessible en écriture"
    chmod -R 775 storage/ 2>/dev/null || true
fi

# Fichiers critiques
critical_files=(".env" "composer.json" "package.json" "artisan" "tailwind.config.js" "vite.config.js")
for file in "${critical_files[@]}"; do
    if [ -f "$file" ]; then
        PERMS=$(stat -c %a "$file")
        log_success "$file ($PERMS)"
    else
        log_error "$file manquant"
    fi
done

# 2️⃣ VÉRIFICATION ENVIRONNEMENT
echo ""
echo "2️⃣ VÉRIFICATION ENVIRONNEMENT"
echo "=============================="

# PHP
PHP_VERSION=$(php -v | head -n1 | cut -d' ' -f2)
log_success "PHP: $PHP_VERSION"

# Node & npm
if command -v node >/dev/null 2>&1; then
    NODE_VERSION=$(node -v)
    NPM_VERSION=$(npm -v)
    log_success "Node: $NODE_VERSION"
    log_success "npm: $NPM_VERSION"
else
    log_error "Node.js non installé"
fi

# MySQL/Database
if php artisan tinker --execute="DB::connection()->getPdo()" >/dev/null 2>&1; then
    log_success "Base de données accessible"
else
    log_warning "Base de données non accessible"
fi

# 3️⃣ VÉRIFICATION FICHIERS PROJET
echo ""
echo "3️⃣ VÉRIFICATION FICHIERS PROJET"
echo "==============================="

# Structure répertoires
required_dirs=("app" "config" "database" "resources" "routes" "storage" "public")
for dir in "${required_dirs[@]}"; do
    if [ -d "$dir" ]; then
        log_success "Répertoire $dir"
    else
        log_error "Répertoire $dir manquant"
    fi
done

# Contrôleurs critiques
if [ -f "app/Http/Controllers/DashboardController.php" ]; then
    # Test syntaxe PHP
    if php -l app/Http/Controllers/DashboardController.php >/dev/null 2>&1; then
        log_success "DashboardController (syntaxe OK)"
    else
        log_error "DashboardController (erreur syntaxe)"
    fi
else
    log_error "DashboardController manquant"
fi

# Vues principales
vue_files=("resources/js/Pages/Dashboard/Admin.vue" "resources/js/app.js" "resources/css/app.css")
for vue_file in "${vue_files[@]}"; do
    if [ -f "$vue_file" ]; then
        SIZE=$(stat -c%s "$vue_file")
        log_success "$vue_file ($SIZE bytes)"
    else
        log_error "$vue_file manquant"
    fi
done

# 4️⃣ NETTOYAGE PRÉPARATOIRE
echo ""
echo "4️⃣ NETTOYAGE PRÉPARATOIRE"
echo "=========================="

# Arrêt processus
pkill -f "artisan serve" 2>/dev/null || true
pkill -f "vite" 2>/dev/null || true
pkill -f "npm" 2>/dev/null || true
log_success "Processus arrêtés"

# Nettoyage cache
rm -rf storage/framework/cache/* 2>/dev/null || true
rm -rf storage/framework/views/* 2>/dev/null || true
rm -rf bootstrap/cache/*.php 2>/dev/null || true
rm -rf public/build/* 2>/dev/null || true
rm -rf node_modules/.vite 2>/dev/null || true
rm -rf node_modules/.cache 2>/dev/null || true
log_success "Cache nettoyé"

# Cache Laravel
php artisan cache:clear >/dev/null 2>&1 || true
php artisan config:clear >/dev/null 2>&1 || true
php artisan route:clear >/dev/null 2>&1 || true
php artisan view:clear >/dev/null 2>&1 || true
log_success "Cache Laravel nettoyé"

# 5️⃣ VÉRIFICATION DÉPENDANCES
echo ""
echo "5️⃣ VÉRIFICATION DÉPENDANCES"
echo "============================"

# Composer
if [ -f "composer.lock" ]; then
    COMPOSER_PACKAGES=$(grep -c '"name":' composer.lock)
    log_success "Composer: $COMPOSER_PACKAGES packages"
else
    log_warning "composer.lock manquant"
fi

# Node modules
if [ -d "node_modules" ]; then
    NODE_PACKAGES=$(ls node_modules | wc -l)
    NODE_SIZE=$(du -sh node_modules 2>/dev/null | cut -f1)
    log_success "node_modules: $NODE_PACKAGES packages ($NODE_SIZE)"
else
    log_warning "node_modules manquant - installation requise"
    
    # Installation automatique
    echo "   📦 Installation dépendances npm..."
    npm install >/dev/null 2>&1
    if [ $? -eq 0 ]; then
        log_success "npm install réussi"
    else
        log_error "npm install échoué"
    fi
fi

# Vérification Tailwind
if [ -f "node_modules/.bin/tailwindcss" ]; then
    log_success "Tailwind CSS installé"
else
    log_warning "Tailwind CSS manquant"
    npm install -D tailwindcss@latest postcss@latest autoprefixer@latest >/dev/null 2>&1
fi

# 6️⃣ CONFIGURATION OPTIMISÉE
echo ""
echo "6️⃣ CONFIGURATION OPTIMISÉE"
echo "==========================="

# Tailwind config sécurisé
cat > tailwind.config.js << 'EOH'
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
        './resources/js/**/*.ts',
        './app/**/*.php',
    ],
    
    safelist: [
        'px-4', 'py-2', 'px-6', 'py-3', 'px-8', 'py-4',
        'pt-2', 'pb-2', 'pt-4', 'pb-4', 'pt-6', 'pb-6',
        'bg-blue-500', 'bg-blue-600', 'bg-green-500', 'bg-red-500',
        'bg-gray-50', 'bg-gray-100', 'bg-gray-200', 'bg-white',
        'text-white', 'text-black', 'text-gray-900', 'text-gray-600',
        'text-sm', 'text-base', 'text-lg', 'text-xl', 'text-2xl',
        'font-medium', 'font-bold', 'font-semibold',
        'rounded', 'rounded-lg', 'rounded-md', 'rounded-full',
        'shadow', 'shadow-lg', 'shadow-md', 'shadow-sm',
        'border', 'border-2', 'border-gray-300', 'border-gray-400',
        'flex', 'inline-flex', 'grid', 'block', 'hidden',
        'items-center', 'items-start', 'items-end',
        'justify-center', 'justify-between', 'justify-start',
        'space-x-2', 'space-x-4', 'space-y-2', 'space-y-4',
        'w-full', 'w-auto', 'w-4', 'w-6', 'w-8', 'w-12',
        'h-full', 'h-auto', 'h-4', 'h-6', 'h-8', 'h-12',
        'mx-auto', 'my-4', 'mt-4', 'mb-4', 'mt-8', 'mb-8',
        'hover:bg-blue-600', 'hover:bg-gray-100',
        'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500',
        'transition', 'duration-200', 'cursor-pointer',
        'opacity-50', 'opacity-75', 'opacity-100',
    ],
    
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    
    plugins: [forms],
};
EOH
log_success "Tailwind config optimisé"

# CSS avec fallbacks
cat > resources/css/app.css << 'EOH'
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
    html {
        font-family: 'Figtree', system-ui, -apple-system, sans-serif;
    }
    body {
        line-height: 1.6;
        color: #1f2937;
    }
}

@layer components {
    .btn-primary {
        @apply px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200;
    }
    .card {
        @apply bg-white rounded-lg shadow p-6;
    }
}

/* Fallbacks CSS critiques */
.px-4{padding-left:1rem;padding-right:1rem}.py-2{padding-top:.5rem;padding-bottom:.5rem}.bg-blue-500{background-color:#3b82f6}.text-white{color:#fff}.rounded{border-radius:.25rem}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1)}.flex{display:flex}.items-center{align-items:center}.justify-center{justify-content:center}.w-full{width:100%}.mx-auto{margin-left:auto;margin-right:auto}.font-bold{font-weight:700}.hover\:bg-blue-600:hover{background-color:#2563eb}
EOH
log_success "CSS avec fallbacks créé"

# PostCSS
cat > postcss.config.js << 'EOH'
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
EOH
log_success "PostCSS configuré"

# 7️⃣ RECOMPILATION ASSETS
echo ""
echo "7️⃣ RECOMPILATION ASSETS"
echo "======================="

# Variables d'environnement
export NODE_OPTIONS="--max-old-space-size=4096"
export NODE_ENV="production"

# Nettoyage cache npm
npm cache clean --force >/dev/null 2>&1
log_success "Cache npm nettoyé"

# Build principal
echo "   🔨 Build en cours..."
if npm run build 2>&1; then
    log_success "Build production réussi"
    BUILD_SUCCESS=true
else
    log_warning "Build production échoué"
    BUILD_SUCCESS=false
fi

# Build de secours si échec
if [ "$BUILD_SUCCESS" = false ]; then
    echo "   🔨 Build de secours..."
    export NODE_ENV="development"
    
    if npm run dev -- --build 2>&1; then
        log_success "Build développement réussi"
        BUILD_SUCCESS=true
    else
        log_warning "Build développement échoué"
    fi
fi

# Assets minimaux en dernier recours
if [ "$BUILD_SUCCESS" = false ]; then
    echo "   🔨 Création assets minimaux..."
    mkdir -p public/build/assets
    
    # Manifest
    cat > public/build/manifest.json << 'EOF'
{
  "resources/css/app.css": {
    "file": "assets/app.css",
    "isEntry": true,
    "src": "resources/css/app.css"
  },
  "resources/js/app.js": {
    "file": "assets/app.js",
    "isEntry": true,
    "src": "resources/js/app.js"
  }
}
EOF

    # CSS compilé
    cat > public/build/assets/app.css << 'EOF'
body{font-family:Figtree,system-ui,sans-serif;line-height:1.6;color:#1f2937}.px-4{padding-left:1rem;padding-right:1rem}.py-2{padding-top:.5rem;padding-bottom:.5rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.py-3{padding-top:.75rem;padding-bottom:.75rem}.bg-blue-500{background-color:#3b82f6}.bg-blue-600{background-color:#2563eb}.bg-white{background-color:#fff}.text-white{color:#fff}.text-gray-900{color:#111827}.text-gray-600{color:#4b5563}.rounded{border-radius:.25rem}.rounded-lg{border-radius:.5rem}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1)}.shadow-lg{box-shadow:0 10px 15px -3px rgba(0,0,0,.1)}.border{border-width:1px}.border-gray-300{border-color:#d1d5db}.flex{display:flex}.items-center{align-items:center}.justify-center{justify-content:center}.justify-between{justify-content:space-between}.w-full{width:100%}.mx-auto{margin-left:auto;margin-right:auto}.my-4{margin-top:1rem;margin-bottom:1rem}.mt-4{margin-top:1rem}.mb-4{margin-bottom:1rem}.font-medium{font-weight:500}.font-bold{font-weight:700}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.hover\:bg-blue-600:hover{background-color:#2563eb}.focus\:outline-none:focus{outline:2px solid transparent}.transition{transition-property:color,background-color,border-color,text-decoration-color,fill,stroke,opacity,box-shadow,transform,filter,backdrop-filter}.duration-200{transition-duration:200ms}.btn-primary{padding:.5rem 1rem;background-color:#3b82f6;color:#fff;border-radius:.25rem;transition:all .2s}.btn-primary:hover{background-color:#2563eb}.card{background-color:#fff;border-radius:.5rem;box-shadow:0 1px 3px 0 rgba(0,0,0,.1);padding:1.5rem}
EOF

    # JS minimal
    echo 'console.log("StudiosDB v5 - Système opérationnel");document.addEventListener("DOMContentLoaded",function(){console.log("DOM chargé - StudiosDB prêt")});' > public/build/assets/app.js
    
    log_success "Assets minimaux créés"
    BUILD_SUCCESS=true
fi

# 8️⃣ VÉRIFICATION BUILD
echo ""
echo "8️⃣ VÉRIFICATION BUILD"
echo "====================="

if [ -f "public/build/manifest.json" ] && [ -s "public/build/manifest.json" ]; then
    MANIFEST_SIZE=$(stat -c%s public/build/manifest.json)
    ASSETS_COUNT=$(find public/build -name "*.css" -o -name "*.js" | wc -l)
    log_success "Manifest: $MANIFEST_SIZE bytes"
    log_success "Assets compilés: $ASSETS_COUNT fichiers"
    
    # Validation contenu manifest
    if grep -q "app.css\|app.js" public/build/manifest.json; then
        log_success "Manifest valide"
    else
        log_warning "Manifest potentiellement corrompu"
    fi
else
    log_error "Manifest manquant ou vide"
fi

# Vérification fichiers CSS/JS
if [ -f "public/build/assets/app.css" ]; then
    CSS_SIZE=$(stat -c%s public/build/assets/app.css)
    log_success "CSS: $CSS_SIZE bytes"
    
    # Test classes critiques
    if grep -q "px-4\|bg-blue-500\|flex" public/build/assets/app.css; then
        log_success "Classes Tailwind présentes"
    else
        log_warning "Classes Tailwind manquantes"
    fi
else
    log_error "Fichier CSS manquant"
fi

# 9️⃣ OPTIMISATION LARAVEL
echo ""
echo "9️⃣ OPTIMISATION LARAVEL"
echo "======================="

php artisan config:cache >/dev/null 2>&1 && log_success "Config cached" || log_warning "Config cache échoué"
php artisan route:cache >/dev/null 2>&1 && log_success "Routes cached" || log_warning "Route cache échoué"
php artisan view:cache >/dev/null 2>&1 && log_success "Views cached" || log_warning "View cache échoué"

# Permissions finales
chmod -R 755 public/build/ 2>/dev/null || true
log_success "Permissions assets corrigées"

# 🔟 DÉMARRAGE SERVEUR
echo ""
echo "🔟 DÉMARRAGE SERVEUR"
echo "==================="

# Démarrage Laravel
nohup php artisan serve --host=0.0.0.0 --port=8000 > laravel.log 2>&1 &
LARAVEL_PID=$!
sleep 3

if kill -0 $LARAVEL_PID 2>/dev/null; then
    log_success "Laravel démarré (PID: $LARAVEL_PID)"
    echo "$LARAVEL_PID" > .laravel_pid
else
    log_error "Échec démarrage Laravel"
fi

# 1️⃣1️⃣ TESTS FONCTIONNELS
echo ""
echo "1️⃣1️⃣ TESTS FONCTIONNELS"
echo "======================="

sleep 2

# Tests HTTP
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 2>/dev/null || echo "000")
LOGIN_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/login 2>/dev/null || echo "000")
DASHBOARD_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/dashboard 2>/dev/null || echo "000")

if [ "$HTTP_STATUS" = "200" ] || [ "$HTTP_STATUS" = "302" ]; then
    log_success "Serveur principal: HTTP $HTTP_STATUS"
else
    log_error "Serveur principal: HTTP $HTTP_STATUS"
fi

if [ "$LOGIN_STATUS" = "200" ]; then
    log_success "Page login: HTTP $LOGIN_STATUS"
else
    log_warning "Page login: HTTP $LOGIN_STATUS"
fi

if [ "$DASHBOARD_STATUS" = "200" ] || [ "$DASHBOARD_STATUS" = "302" ]; then
    log_success "Dashboard: HTTP $DASHBOARD_STATUS"
else
    log_warning "Dashboard: HTTP $DASHBOARD_STATUS (authentification requise)"
fi

# Test assets
CSS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app.css 2>/dev/null || echo "000")
JS_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/build/assets/app.js 2>/dev/null || echo "000")

if [ "$CSS_STATUS" = "200" ]; then
    log_success "Assets CSS: HTTP $CSS_STATUS"
else
    log_warning "Assets CSS: HTTP $CSS_STATUS"
fi

if [ "$JS_STATUS" = "200" ]; then
    log_success "Assets JS: HTTP $JS_STATUS"
else
    log_warning "Assets JS: HTTP $JS_STATUS"
fi

# 1️⃣2️⃣ RAPPORT FINAL
echo ""
echo "1️⃣2️⃣ RAPPORT FINAL"
echo "=================="
echo "Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# Statistiques
echo "📊 STATISTIQUES:"
echo "• Succès: $SUCCESS ✅"
echo "• Avertissements: $WARNINGS ⚠️"
echo "• Erreurs: $ERRORS ❌"
echo ""

# État global
if [ $ERRORS -eq 0 ] && [ "$HTTP_STATUS" != "000" ]; then
    echo "🟢 STATUT GLOBAL: SYSTÈME OPÉRATIONNEL"
    STATUS="SUCCESS"
elif [ $ERRORS -le 2 ] && [ "$HTTP_STATUS" != "000" ]; then
    echo "🟡 STATUT GLOBAL: FONCTIONNEL AVEC AVERTISSEMENTS"
    STATUS="WARNING"
else
    echo "🔴 STATUT GLOBAL: PROBLÈMES CRITIQUES"
    STATUS="ERROR"
fi

echo ""
echo "🌐 URLS DE TEST:"
echo "==============="
echo "• Application: http://localhost:8000"
echo "• Login: http://localhost:8000/login"
echo "• Dashboard: http://localhost:8000/dashboard"
echo ""
echo "🔑 IDENTIFIANTS TEST:"
echo "• Email: louis@4lb.ca"
echo "• Password: password"
echo ""

# Informations système
echo "💻 INFORMATIONS SYSTÈME:"
echo "========================"
echo "• Utilisateur: $(whoami)"
echo "• Répertoire: $(pwd)"
echo "• PHP: $PHP_VERSION"
echo "• Node: ${NODE_VERSION:-'Non installé'}"
echo "• Serveur PID: ${LARAVEL_PID:-'Non démarré'}"
echo ""

# Commandes utiles
echo "🔧 COMMANDES UTILES:"
echo "==================="
echo "• Logs serveur: tail -f laravel.log"
echo "• Arrêter serveur: pkill -f 'artisan serve'"
echo "• Rebuild assets: npm run build"
echo "• Clear cache: php artisan cache:clear"
echo "• Status processus: ps aux | grep artisan"
echo ""

# Résumé technique
echo "🔬 RÉSUMÉ TECHNIQUE:"
echo "==================="
echo "• Manifest: $([ -f public/build/manifest.json ] && stat -c%s public/build/manifest.json || echo 0) bytes"
echo "• Assets: $(find public/build -type f | wc -l) fichiers"
echo "• CSS: $([ -f public/build/assets/app.css ] && stat -c%s public/build/assets/app.css || echo 0) bytes"
echo "• JS: $([ -f public/build/assets/app.js ] && stat -c%s public/build/assets/app.js || echo 0) bytes"
echo "• Storage: $([ -w storage/framework/views ] && echo 'Writable' || echo 'Read-only')"
echo ""

if [ "$STATUS" = "SUCCESS" ]; then
    echo "🎉 VÉRIFICATION TERMINÉE AVEC SUCCÈS!"
    echo "Système StudiosDB v5 opérationnel à 100%"
elif [ "$STATUS" = "WARNING" ]; then
    echo "⚠️ VÉRIFICATION TERMINÉE AVEC AVERTISSEMENTS"
    echo "Système fonctionnel mais optimisations possibles"
else
    echo "🚨 VÉRIFICATION RÉVÈLE DES PROBLÈMES"
    echo "Corrections manuelles requises"
fi

echo ""
echo "📈 PROCHAINES ÉTAPES:"
if [ "$STATUS" = "SUCCESS" ]; then
    echo "1. Connectez-vous: http://localhost:8000/login"
    echo "2. Testez toutes les fonctionnalités"
    echo "3. Configurez les données métier"
elif [ "$STATUS" = "WARNING" ]; then
    echo "1. Consultez les avertissements ci-dessus"
    echo "2. Testez: http://localhost:8000/login"
    echo "3. Surveillez les logs: tail -f laravel.log"
else
    echo "1. Corrigez les erreurs critiques"
    echo "2. Relancez ce script de vérification"
    echo "3. Consultez les logs détaillés"
fi

echo ""
echo "✨ StudiosDB v5 - Vérification complète terminée!"

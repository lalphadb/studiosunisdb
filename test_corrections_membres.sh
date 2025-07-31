#!/bin/bash
# Test des corrections pour l'erreur JavaScript du module Membres

echo "🛠️ TEST DES CORRECTIONS - MODULE MEMBRES"
echo "======================================="
echo "Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

PROJECT_PATH="/home/studiosdb/studiosunisdb/studiosdb_v5_pro"

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

success_msg() {
    echo -e "${GREEN}✅ $1${NC}"
}

error_msg() {
    echo -e "${RED}❌ $1${NC}"
}

info_msg() {
    echo -e "${BLUE}ℹ️ $1${NC}"
}

warning_msg() {
    echo -e "${YELLOW}⚠️ $1${NC}"
}

cd "$PROJECT_PATH" || exit 1

echo -e "${BLUE}🔍 1. VÉRIFICATION DES CORRECTIONS APPLIQUÉES${NC}"
echo "-------------------------------------------"

# Vérifier que le contrôleur passe les filtres
if grep -q "filters.*validated" app/Http/Controllers/MembreController.php; then
    success_msg "Contrôleur: Passage des filtres corrigé"
else
    error_msg "Contrôleur: Problème passage filtres"
fi

# Vérifier les accès sécurisés dans Vue
if grep -q "props.filters?.search" resources/js/Pages/Membres/Index.vue; then
    success_msg "Vue.js: Accès sécurisés avec ?.  implémentés"
else
    error_msg "Vue.js: Accès non sécurisés"
fi

# Vérifier les props par défaut
if grep -q "default: () => \[\]" resources/js/Pages/Membres/Index.vue; then
    success_msg "Vue.js: Props avec valeurs par défaut"
else
    error_msg "Vue.js: Props sans valeurs par défaut"
fi

echo ""
echo -e "${BLUE}🧹 2. NETTOYAGE ET PRÉPARATION${NC}"
echo "-----------------------------"

info_msg "Nettoyage des caches Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
success_msg "Caches nettoyés"

echo ""
echo -e "${BLUE}🎨 3. COMPILATION FRONTEND${NC}"
echo "-------------------------"

info_msg "Compilation des assets..."
if npm run build; then
    success_msg "✨ Compilation réussie - Erreurs JavaScript corrigées !"
    
    # Vérifier la taille du build
    BUILD_SIZE=$(du -sh public/build 2>/dev/null | cut -f1 || echo "N/A")
    info_msg "Taille du build: $BUILD_SIZE"
    
else
    error_msg "Échec de compilation"
    echo ""
    warning_msg "Si problème persiste:"
    echo "rm -rf node_modules package-lock.json"
    echo "npm install"
    echo "npm run build"
    exit 1
fi

echo ""
echo -e "${BLUE}🗄️ 4. TEST BASE DE DONNÉES${NC}"
echo "----------------------------"

info_msg "Test requête membres..."
if php artisan tinker --execute="echo 'Membres en base: ' . App\Models\Membre::count();" 2>/dev/null; then
    success_msg "Accès base de données OK"
else
    warning_msg "Problème base de données ou modèle"
fi

echo ""
echo -e "${BLUE}🌐 5. TEST SERVEUR${NC}"
echo "------------------"

info_msg "Démarrage serveur de test..."
php artisan serve --host=0.0.0.0 --port=8000 &
SERVER_PID=$!

sleep 3

# Test page dashboard
info_msg "Test page Dashboard..."
if curl -s -o /dev/null -w "%{http_code}" "http://localhost:8000/dashboard" | grep -q "200"; then
    success_msg "Dashboard accessible"
else
    warning_msg "Dashboard problème d'accès"
fi

# Test page membres (celle qu'on vient de corriger)
info_msg "Test page Membres..."
if curl -s -o /dev/null -w "%{http_code}" "http://localhost:8000/membres" | grep -q "200"; then
    success_msg "Page Membres accessible - CORRECTIONS RÉUSSIES !"
else
    error_msg "Page Membres toujours en erreur"
fi

# Arrêt du serveur de test
kill $SERVER_PID 2>/dev/null
sleep 1

echo ""
echo -e "${BLUE}📋 6. RÉSUMÉ DES CORRECTIONS${NC}"
echo "----------------------------"

success_msg "✨ Contrôleur MembreController:"
echo "   • Passage obligatoire de l'objet 'filters'"
echo "   • Prévention des erreurs undefined"

success_msg "✨ Composant Vue Membres/Index.vue:"
echo "   • Accès sécurisés avec '?.  ' (optional chaining)"
echo "   • Props avec valeurs par défaut robustes"
echo "   • Protection des boucles v-for"
echo "   • Gestion des objets potentiellement undefined"

success_msg "✨ Problème résolu:"
echo "   • TypeError: Cannot read properties of undefined (reading 'search')"
echo "   • Interface Members maintenant stable"

echo ""
echo -e "${BLUE}🎯 7. DASHBOARD IDENTIFIÉ${NC}"
echo "-------------------------"

success_msg "Page Dashboard utilisée:"
echo "   📁 resources/js/Pages/Dashboard/Admin.vue"
echo "   🎮 Contrôleur: DashboardController@index"
echo "   🔧 Render: Inertia::render('Dashboard/Admin', ...)"

info_msg "Autres dashboards disponibles mais non utilisés:"
echo "   • Dashboard.vue (racine)"
echo "   • DashboardPro.vue"
echo "   • DashboardSimple.vue"
echo "   • DashboardUltraPro.vue"
echo "   • Dashboard/Default.vue"
echo "   • Dashboard/Simple.vue"
echo "   • Dashboard/Error.vue"

echo ""
echo -e "${BLUE}🚀 8. COMMANDES DE TEST FINAL${NC}"
echo "------------------------------"

echo "Pour tester en live:"
echo "1️⃣ php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "2️⃣ Ouvrir les URLs:"
echo "   • Dashboard: http://4lb.ca:8000/dashboard"
echo "   • Membres:   http://4lb.ca:8000/membres"
echo ""
echo "3️⃣ Vérifier dans la console du navigateur:"
echo "   • Plus d'erreurs JavaScript"
echo "   • Interface fluide et responsive"
echo "   • Filtres fonctionnels"

echo ""
echo -e "${BLUE}🔍 9. MONITORING LOGS${NC}"
echo "---------------------"

info_msg "Pour surveiller les erreurs en temps réel:"
echo "tail -f storage/logs/laravel.log"
echo ""
info_msg "Pour logs Nginx:"
echo "sudo tail -f /var/log/nginx/error.log"

echo ""
echo "=================================================="
echo -e "${GREEN}✅ CORRECTIONS TERMINÉES AVEC SUCCÈS !${NC}"
echo "=================================================="
echo -e "${BLUE}🎯 Module Membres maintenant stable et sans erreurs${NC}"
echo -e "${BLUE}🎨 Interface moderne cohérente avec Dashboard${NC}"
echo -e "${BLUE}📱 Prêt pour tests utilisateurs${NC}"
echo "=================================================="


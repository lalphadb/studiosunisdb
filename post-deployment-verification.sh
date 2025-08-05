#!/bin/bash
# 🧪 VÉRIFICATION POST-DÉPLOIEMENT STUDIOSDB V5 PRO

echo "🧪 === VÉRIFICATION POST-DÉPLOIEMENT STUDIOSDB V5 ==="
cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Couleurs
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
PURPLE='\033[0;35m'
NC='\033[0m'

# Score global
SCORE=0
TOTAL_TESTS=20

echo -e "${BLUE}🎯 Démarrage tests $(date '+%H:%M:%S')${NC}"

# TEST 1: Structure projet
echo ""
echo -e "${YELLOW}📁 TEST 1: Structure Projet${NC}"
if [ -f "artisan" ] && [ -f "composer.json" ] && [ -f "package.json" ]; then
    echo "✅ Structure Laravel correcte"
    ((SCORE++))
else
    echo "❌ Structure projet incomplète"
fi

# TEST 2: Configuration environnement  
echo ""
echo -e "${YELLOW}⚙️ TEST 2: Configuration${NC}"
if [ -f ".env" ] && grep -q "APP_KEY=" .env; then
    echo "✅ Configuration environnement OK"
    ((SCORE++))
else
    echo "❌ Configuration manquante"
fi

# TEST 3: Base de données
echo ""
echo -e "${YELLOW}🗄️ TEST 3: Base de Données${NC}"
if php artisan migrate:status &>/dev/null; then
    echo "✅ Base de données accessible"
    ((SCORE++))
    
    # Test tables principales
    USERS_COUNT=$(php artisan tinker --execute="echo App\\Models\\User::count();" 2>/dev/null || echo "0")
    echo "👥 Utilisateurs: $USERS_COUNT"
    
    if [ "$USERS_COUNT" -gt 0 ]; then
        echo "✅ Données présentes"
        ((SCORE++))
    fi
else
    echo "❌ Base de données inaccessible"
fi

# TEST 4: Cache système
echo ""
echo -e "${YELLOW}💾 TEST 4: Cache${NC}"
if php artisan cache:clear &>/dev/null; then
    echo "✅ Cache Laravel fonctionnel"
    ((SCORE++))
else
    echo "❌ Problème cache Laravel"
fi

# Test Redis si disponible
if redis-cli ping &>/dev/null; then
    echo "✅ Redis disponible et fonctionnel"
    ((SCORE++))
else
    echo "⚠️  Redis non disponible (utilisation file cache)"
fi

# TEST 5: Assets frontend
echo ""
echo -e "${YELLOW}🎨 TEST 5: Assets Frontend${NC}"
if [ -f "public/build/manifest.json" ]; then
    ASSETS_SIZE=$(du -sh public/build/ | cut -f1)
    echo "✅ Assets buildés (${ASSETS_SIZE})"
    ((SCORE++))
else
    echo "❌ Assets non buildés"
fi

# TEST 6: Serveur web
echo ""
echo -e "${YELLOW}🌐 TEST 6: Serveur Web${NC}"

# Test serveur local
if curl -s http://localhost:8000 &>/dev/null; then
    echo "✅ Serveur local accessible"
    ((SCORE++))
    
    # Test temps réponse dashboard
    RESPONSE_TIME=$(curl -w "%{time_total}" -s -o /dev/null http://localhost:8000/dashboard 2>/dev/null || echo "N/A")
    echo "⏱️  Dashboard: ${RESPONSE_TIME}s"
    
    if [[ "$RESPONSE_TIME" =~ ^[0-9.]+$ ]] && (( $(echo "$RESPONSE_TIME < 0.1" | bc -l) )); then
        echo "🚀 Performance EXCELLENTE (< 100ms)"
        ((SCORE++))
    elif [[ "$RESPONSE_TIME" =~ ^[0-9.]+$ ]] && (( $(echo "$RESPONSE_TIME < 0.5" | bc -l) )); then
        echo "✅ Performance BONNE (< 500ms)"
        ((SCORE++))
    else
        echo "⚠️  Performance à améliorer"
    fi
else
    echo "❌ Serveur inaccessible"
fi

# TEST 7: Authentication
echo ""
echo -e "${YELLOW}🔐 TEST 7: Authentication${NC}"
if curl -s http://localhost:8000/login | grep -q "csrf-token"; then
    echo "✅ Page login accessible avec CSRF"
    ((SCORE++))
else
    echo "❌ Problème authentication"
fi

# TEST 8: API Endpoints
echo ""
echo -e "${YELLOW}🔌 TEST 8: API Endpoints${NC}"
# Test route API dashboard (nécessite auth, donc test différent)
if php artisan route:list | grep -q "api/dashboard/metriques"; then
    echo "✅ Routes API configurées"
    ((SCORE++))
else
    echo "❌ Routes API manquantes"
fi

# TEST 9: Permissions fichiers
echo ""
echo -e "${YELLOW}🔒 TEST 9: Permissions${NC}"
if [ -w "storage" ] && [ -w "bootstrap/cache" ]; then
    echo "✅ Permissions écriture OK"
    ((SCORE++))
else
    echo "❌ Problèmes permissions"
fi

# TEST 10: Logs
echo ""
echo -e "${YELLOW}📊 TEST 10: Logs${NC}"
if [ -f "storage/logs/laravel.log" ]; then
    RECENT_ERRORS=$(grep -c "ERROR" storage/logs/laravel.log 2>/dev/null || echo "0")
    echo "📋 Erreurs récentes: $RECENT_ERRORS"
    
    if [ "$RECENT_ERRORS" -lt 5 ]; then
        echo "✅ Logs propres"
        ((SCORE++))
    else
        echo "⚠️  Plusieurs erreurs détectées"
    fi
else
    echo "⚠️  Pas de logs (nouveau projet)"
fi

# TEST 11: Git repository
echo ""
echo -e "${YELLOW}📝 TEST 11: Git Repository${NC}"
if [ -d ".git" ]; then
    BRANCH=$(git branch --show-current 2>/dev/null || echo "main")
    COMMITS=$(git rev-list --count HEAD 2>/dev/null || echo "0")
    echo "✅ Git initialisé (branche: $BRANCH, commits: $COMMITS)"
    ((SCORE++))
    
    # Vérifier remote GitHub
    if git remote get-url origin &>/dev/null; then
        echo "✅ Remote GitHub configuré"
        ((SCORE++))
    else
        echo "⚠️  Remote GitHub non configuré"
    fi
else
    echo "❌ Git non initialisé"
fi

# TEST 12: Documentation
echo ""
echo -e "${YELLOW}📚 TEST 12: Documentation${NC}"
if [ -f "README.md" ]; then
    README_SIZE=$(wc -l README.md | cut -d' ' -f1)
    echo "✅ README.md présent (${README_SIZE} lignes)"
    ((SCORE++))
    
    if [ "$README_SIZE" -gt 100 ]; then
        echo "🏆 Documentation complète"
        ((SCORE++))
    fi
else
    echo "❌ README.md manquant"
fi

# TEST 13: Sécurité
echo ""
echo -e "${YELLOW}🛡️ TEST 13: Sécurité${NC}"
if grep -q "APP_DEBUG=false" .env.example; then
    echo "✅ Configuration production sécurisée"
    ((SCORE++))
else
    echo "⚠️  Vérifier configuration production"
fi

# TEST 14: Tests unitaires
echo ""
echo -e "${YELLOW}🧪 TEST 14: Tests Unitaires${NC}"
if php artisan test --without-tty &>/dev/null; then
    echo "✅ Tests unitaires passent"
    ((SCORE++))
else
    echo "⚠️  Tests à vérifier"
fi

# TEST 15: Conformité PSR-12
echo ""
echo -e "${YELLOW}📏 TEST 15: Code Style${NC}"
if [ -f "vendor/bin/pint" ]; then
    if ./vendor/bin/pint --test &>/dev/null; then
        echo "✅ Code PSR-12 compliant"
        ((SCORE++))
    else
        echo "⚠️  Code style à corriger"
    fi
else
    echo "ℹ️  Laravel Pint non installé"
fi

# CALCUL SCORE FINAL
echo ""
echo -e "${PURPLE}📊 === RÉSULTATS FINAUX ===${NC}"

PERCENTAGE=$((SCORE * 100 / TOTAL_TESTS))

echo -e "${BLUE}🎯 Score: ${SCORE}/${TOTAL_TESTS} (${PERCENTAGE}%)${NC}"

if [ $PERCENTAGE -ge 90 ]; then
    echo -e "${GREEN}🏆 EXCELLENT ! StudiosDB v5 Pro est parfaitement configuré !${NC}"
    GRADE="A+"
elif [ $PERCENTAGE -ge 80 ]; then
    echo -e "${GREEN}✅ TRÈS BIEN ! Quelques optimisations mineures possibles.${NC}"
    GRADE="A"
elif [ $PERCENTAGE -ge 70 ]; then
    echo -e "${YELLOW}⚡ BIEN ! Améliorations recommandées.${NC}"
    GRADE="B"
elif [ $PERCENTAGE -ge 60 ]; then
    echo -e "${YELLOW}⚠️  CORRECT ! Corrections nécessaires.${NC}"
    GRADE="C"
else
    echo -e "${RED}❌ INSUFFISANT ! Révision complète requise.${NC}"
    GRADE="D"
fi

echo ""
echo -e "${PURPLE}🎖️ Note finale: ${GRADE}${NC}"

# RECOMMANDATIONS
echo ""
echo -e "${YELLOW}💡 RECOMMANDATIONS:${NC}"

if [ $SCORE -lt $TOTAL_TESTS ]; then
    echo ""
    echo -e "${BLUE}🔧 Actions à effectuer:${NC}"
    
    if ! [ -f "public/build/manifest.json" ]; then
        echo "• npm run build (assets frontend)"
    fi
    
    if ! redis-cli ping &>/dev/null; then
        echo "• sudo apt install redis-server (performance)"
    fi
    
    if ! git remote get-url origin &>/dev/null; then
        echo "• git remote add origin URL (GitHub)"
    fi
    
    if [ ! -f "README.md" ] || [ "$(wc -l README.md | cut -d' ' -f1)" -lt 100 ]; then
        echo "• Mettre à jour documentation README.md"
    fi
fi

echo ""
echo -e "${GREEN}🎯 PROCHAINES ÉTAPES:${NC}"
echo "1. 🌐 Tester interface: http://localhost:8000"
echo "2. 👤 Créer compte admin via /register"
echo "3. 📊 Vérifier dashboard: /dashboard"
echo "4. 🏫 Configurer première école"
echo "5. 👥 Ajouter premiers membres"

echo ""
echo -e "${BLUE}📱 URLS IMPORTANTES:${NC}"
echo "• 🏠 Accueil: http://localhost:8000"
echo "• 🔑 Login: http://localhost:8000/login"
echo "• 📊 Dashboard: http://localhost:8000/dashboard"
echo "• 👥 Membres: http://localhost:8000/membres"
echo "• 📱 Présences: http://localhost:8000/presences/tablette"

echo ""
echo -e "${PURPLE}⏱️ Test terminé à $(date '+%H:%M:%S')${NC}"

# Log résultats
echo "$(date '+%Y-%m-%d %H:%M:%S') - Tests post-déploiement: ${SCORE}/${TOTAL_TESTS} (${PERCENTAGE}% - Grade ${GRADE})" >> verification.log

echo ""
if [ $PERCENTAGE -ge 80 ]; then
    echo -e "${GREEN}🚀 STUDIOSDB V5 PRO PRÊT POUR PRODUCTION ! 🎉${NC}"
else
    echo -e "${YELLOW}🔧 STUDIOSDB V5 PRO À FINALISER SELON RECOMMANDATIONS${NC}"
fi

echo -e "${BLUE}✨ Merci d'utiliser StudiosDB v5 Pro ! ✨${NC}"
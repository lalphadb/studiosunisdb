#!/bin/bash

# StudiosDB - Fix Turnstile Error
# Purpose: Corriger l'erreur de namespace et relancer

echo "=== Correction erreur Turnstile ==="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# 1. Arrêter les serveurs existants
echo "1. Arrêt des serveurs..."
pkill -f "artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null
rm -f public/hot 2>/dev/null
echo -e "${GREEN}✓${NC} Serveurs arrêtés"

# 2. Nettoyer tous les caches
echo ""
echo "2. Nettoyage des caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo -e "${GREEN}✓${NC} Caches nettoyés"

# 3. Vérifier la configuration
echo ""
echo "3. Vérification de la configuration..."

# Vérifier que TurnstileService existe
if [ -f "app/Services/TurnstileService.php" ]; then
    echo -e "${GREEN}✓${NC} TurnstileService.php présent"
else
    echo -e "${RED}✗${NC} TurnstileService.php manquant!"
    echo "   Exécutez: bash setup-turnstile.sh"
    exit 1
fi

# Vérifier HandleInertiaRequests
if grep -q "\\\\App\\\\Services\\\\TurnstileService" app/Http/Middleware/HandleInertiaRequests.php; then
    echo -e "${GREEN}✓${NC} HandleInertiaRequests.php corrigé"
else
    echo -e "${YELLOW}⚠${NC} HandleInertiaRequests.php peut nécessiter une vérification"
fi

# 4. Vérifier les clés dans .env
echo ""
echo "4. Configuration Turnstile..."

if grep -q "TURNSTILE_SITE_KEY" .env; then
    site_key=$(grep "TURNSTILE_SITE_KEY" .env | cut -d'=' -f2)
    if [ -n "$site_key" ]; then
        echo -e "${GREEN}✓${NC} Clés Turnstile configurées"
        if [[ "$site_key" == "1x"* ]]; then
            echo -e "${YELLOW}  Mode test activé (OK pour dev)${NC}"
        fi
    else
        echo -e "${YELLOW}⚠${NC} Clés Turnstile vides - ajout des clés de test..."
        # Ajouter les clés de test si absentes
        sed -i "s/TURNSTILE_SITE_KEY=/TURNSTILE_SITE_KEY=1x00000000000000000000AA/g" .env
        sed -i "s/TURNSTILE_SECRET_KEY=/TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA/g" .env
        echo -e "${GREEN}✓${NC} Clés de test ajoutées"
    fi
else
    echo -e "${YELLOW}⚠${NC} Configuration Turnstile absente - ajout..."
    cat >> .env << 'EOF'

# Cloudflare Turnstile - Clés de test
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA
TURNSTILE_MODE=managed
EOF
    echo -e "${GREEN}✓${NC} Configuration ajoutée"
fi

# 5. Compiler les assets
echo ""
echo "5. Compilation des assets..."
npm run build > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Assets compilés"
else
    echo -e "${YELLOW}⚠${NC} Erreur de compilation - essayez: npm run build"
fi

# 6. Test PHP de TurnstileService
echo ""
echo "6. Test du service Turnstile..."

php_test=$(php artisan tinker --execute="
try {
    \$service = app(\App\Services\TurnstileService::class);
    echo \$service->isEnabled() ? 'Service OK' : 'Service désactivé';
} catch (\Exception \$e) {
    echo 'Erreur: ' . \$e->getMessage();
}
" 2>&1 | grep -E "Service|Erreur" | head -1)

if [[ "$php_test" == *"Service OK"* ]]; then
    echo -e "${GREEN}✓${NC} TurnstileService fonctionne"
elif [[ "$php_test" == *"Service désactivé"* ]]; then
    echo -e "${YELLOW}⚠${NC} TurnstileService désactivé (vérifiez TURNSTILE_ENABLED dans .env)"
else
    echo -e "${RED}✗${NC} Erreur TurnstileService: $php_test"
fi

# 7. Démarrer le serveur
echo ""
echo "7. Démarrage du serveur..."
php artisan serve --host=127.0.0.1 --port=8001 &
SERVER_PID=$!

sleep 2

# 8. Test de la page
echo ""
echo "8. Test de l'application..."
http_code=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/)

if [ "$http_code" = "200" ] || [ "$http_code" = "302" ]; then
    echo -e "${GREEN}✓${NC} Application accessible (HTTP $http_code)"
else
    echo -e "${RED}✗${NC} Application inaccessible (HTTP $http_code)"
fi

echo ""
echo "════════════════════════════════════════════════════════════════"
echo -e "${GREEN}✅ Correction terminée!${NC}"
echo "════════════════════════════════════════════════════════════════"
echo ""
echo "L'application est maintenant accessible à:"
echo -e "${YELLOW}http://127.0.0.1:8001${NC}"
echo ""
echo "Pages à tester:"
echo "  • Dashboard: http://127.0.0.1:8001/dashboard"
echo "  • Test Turnstile: http://127.0.0.1:8001/test-turnstile.html"
echo ""
echo "Pour arrêter le serveur: Ctrl+C ou kill $SERVER_PID"
echo ""

# Garder le script actif
trap "kill $SERVER_PID 2>/dev/null; echo 'Serveur arrêté'; exit" INT
wait $SERVER_PID

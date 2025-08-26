#!/bin/bash

# StudiosDB - Test rapide après correction
# Purpose: Vérifier que l'erreur est résolue

echo "=== Test rapide StudiosDB ==="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

ERRORS=0

echo "1. Vérification des fichiers critiques..."

# Vérifier TurnstileService
if [ -f "app/Services/TurnstileService.php" ]; then
    echo -e "${GREEN}✓${NC} TurnstileService.php existe"
else
    echo -e "${RED}✗${NC} TurnstileService.php manquant"
    ((ERRORS++))
fi

# Vérifier la correction dans HandleInertiaRequests
if grep -q "\\\\App\\\\Services\\\\TurnstileService" app/Http/Middleware/HandleInertiaRequests.php; then
    echo -e "${GREEN}✓${NC} HandleInertiaRequests.php utilise le bon namespace"
else
    echo -e "${RED}✗${NC} HandleInertiaRequests.php a encore l'ancien namespace"
    ((ERRORS++))
fi

echo ""
echo "2. Test PHP du service..."

# Test direct avec PHP
php_output=$(php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->handleRequest(Illuminate\Http\Request::capture());

try {
    if (class_exists('\\App\\Services\\TurnstileService')) {
        echo 'Class exists';
    } else {
        echo 'Class not found';
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage();
}
" 2>&1)

if [[ "$php_output" == *"Class exists"* ]]; then
    echo -e "${GREEN}✓${NC} Classe TurnstileService trouvée"
else
    echo -e "${RED}✗${NC} Problème avec TurnstileService: $php_output"
    ((ERRORS++))
fi

echo ""
echo "3. Test Artisan..."

# Test avec artisan
artisan_output=$(php artisan list 2>&1 | head -5)
if [[ "$artisan_output" == *"Laravel"* ]]; then
    echo -e "${GREEN}✓${NC} Artisan fonctionne"
else
    echo -e "${RED}✗${NC} Artisan a des erreurs"
    ((ERRORS++))
fi

echo ""
echo "════════════════════════════════════════════════════════════"

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ Tous les tests passent! L'erreur devrait être résolue.${NC}"
    echo ""
    echo "Pour démarrer l'application:"
    echo "  bash start-server.sh"
    echo ""
    echo "Ou pour un démarrage rapide:"
    echo "  php artisan serve --port=8001"
else
    echo -e "${RED}❌ $ERRORS erreur(s) détectée(s)${NC}"
    echo ""
    echo "Essayez d'exécuter:"
    echo "  bash fix-turnstile-error.sh"
fi

echo ""

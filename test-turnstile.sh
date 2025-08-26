#!/bin/bash

# StudiosDB - Test Turnstile Integration
# Purpose: Vérifier que Turnstile est bien configuré

echo "=== Test d'intégration Cloudflare Turnstile ==="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

ERRORS=0
WARNINGS=0

# Function to check status
check() {
    local test_name=$1
    local command=$2
    local expected=$3
    
    result=$(eval $command 2>&1)
    
    if [[ "$result" == *"$expected"* ]]; then
        echo -e "${GREEN}✓${NC} $test_name"
        return 0
    else
        echo -e "${RED}✗${NC} $test_name"
        echo "   Résultat: $result"
        ((ERRORS++))
        return 1
    fi
}

# 1. Vérifier les fichiers créés
echo "1. Vérification des fichiers..."

if [ -f "app/Services/TurnstileService.php" ]; then
    echo -e "${GREEN}✓${NC} TurnstileService.php existe"
else
    echo -e "${RED}✗${NC} TurnstileService.php manquant"
    ((ERRORS++))
fi

if [ -f "app/Rules/TurnstileRule.php" ]; then
    echo -e "${GREEN}✓${NC} TurnstileRule.php existe"
else
    echo -e "${RED}✗${NC} TurnstileRule.php manquant"
    ((ERRORS++))
fi

if [ -f "resources/js/Components/TurnstileWidget.vue" ]; then
    echo -e "${GREEN}✓${NC} TurnstileWidget.vue existe"
else
    echo -e "${RED}✗${NC} TurnstileWidget.vue manquant"
    ((ERRORS++))
fi

echo ""
echo "2. Vérification de la configuration..."

# Vérifier services.php
if grep -q "turnstile" config/services.php 2>/dev/null; then
    echo -e "${GREEN}✓${NC} Configuration Turnstile dans services.php"
else
    echo -e "${YELLOW}⚠${NC} Configuration Turnstile absente dans services.php"
    ((WARNINGS++))
fi

# Vérifier .env
if grep -q "TURNSTILE_SITE_KEY" .env 2>/dev/null; then
    site_key=$(grep "TURNSTILE_SITE_KEY" .env | cut -d'=' -f2)
    if [ -n "$site_key" ]; then
        echo -e "${GREEN}✓${NC} TURNSTILE_SITE_KEY définie: ${site_key:0:10}..."
        
        # Vérifier si c'est une clé de test
        if [[ "$site_key" == "1x"* ]]; then
            echo -e "${YELLOW}⚠${NC} Utilisation de clés de TEST (OK pour dev)"
        fi
    else
        echo -e "${RED}✗${NC} TURNSTILE_SITE_KEY vide"
        ((ERRORS++))
    fi
else
    echo -e "${RED}✗${NC} TURNSTILE_SITE_KEY manquante dans .env"
    ((ERRORS++))
fi

if grep -q "TURNSTILE_SECRET_KEY" .env 2>/dev/null; then
    secret_key=$(grep "TURNSTILE_SECRET_KEY" .env | cut -d'=' -f2)
    if [ -n "$secret_key" ]; then
        echo -e "${GREEN}✓${NC} TURNSTILE_SECRET_KEY définie"
    else
        echo -e "${RED}✗${NC} TURNSTILE_SECRET_KEY vide"
        ((ERRORS++))
    fi
else
    echo -e "${RED}✗${NC} TURNSTILE_SECRET_KEY manquante dans .env"
    ((ERRORS++))
fi

echo ""
echo "3. Test PHP..."

# Test du service
php_test=$(php -r "
    require 'vendor/autoload.php';
    require 'app/Services/TurnstileService.php';
    \$service = new \App\Services\TurnstileService();
    echo \$service->isEnabled() ? 'enabled' : 'disabled';
" 2>&1)

if [ "$php_test" = "enabled" ]; then
    echo -e "${GREEN}✓${NC} TurnstileService fonctionne"
else
    echo -e "${RED}✗${NC} Erreur TurnstileService: $php_test"
    ((ERRORS++))
fi

echo ""
echo "4. Génération d'un exemple de route..."

# Créer une route de test
cat > routes/turnstile-test.php << 'EOF'
<?php

use App\Rules\TurnstileRule;
use App\Services\TurnstileService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route de test pour Turnstile
Route::get('/test-turnstile', function () {
    $turnstile = app(TurnstileService::class);
    
    return response()->json([
        'enabled' => $turnstile->isEnabled(),
        'site_key' => $turnstile->getSiteKey(),
        'mode' => $turnstile->getMode(),
        'test_mode' => $turnstile->isTestMode(),
        'config' => $turnstile->getConfig(),
    ]);
});

// Route de validation
Route::post('/test-turnstile', function (Request $request) {
    $request->validate([
        'cf-turnstile-response' => ['required', new TurnstileRule()],
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Turnstile validation réussie!'
    ]);
});
EOF

echo -e "${GREEN}✓${NC} Route de test créée: routes/turnstile-test.php"

echo ""
echo "5. Page HTML de test..."

# Créer une page de test
cat > public/test-turnstile.html << 'EOF'
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Turnstile - StudiosDB</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full p-8 bg-slate-800 rounded-lg shadow-xl">
        <h1 class="text-2xl font-bold mb-6">Test Cloudflare Turnstile</h1>
        
        <form id="testForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2">Email de test</label>
                <input type="email" name="email" value="test@example.com" 
                       class="w-full px-3 py-2 bg-slate-700 rounded border border-slate-600 focus:border-blue-500 focus:outline-none">
            </div>
            
            <!-- Turnstile Widget -->
            <div class="cf-turnstile" 
                 data-sitekey="1x00000000000000000000AA"
                 data-theme="dark"
                 data-language="fr"></div>
            
            <button type="submit" 
                    class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 rounded font-medium transition-colors">
                Tester la validation
            </button>
        </form>
        
        <div id="result" class="mt-4 p-3 rounded hidden"></div>
        
        <div class="mt-6 text-sm text-slate-400">
            <p>Cette page utilise la clé de test Turnstile.</p>
            <p>Elle valide toujours avec succès.</p>
        </div>
    </div>
    
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <script>
        document.getElementById('testForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const response = document.querySelector('[name="cf-turnstile-response"]').value;
            const resultDiv = document.getElementById('result');
            
            if (!response) {
                resultDiv.className = 'mt-4 p-3 rounded bg-red-900 text-red-200';
                resultDiv.textContent = 'Veuillez compléter le Turnstile';
                resultDiv.classList.remove('hidden');
                return;
            }
            
            resultDiv.className = 'mt-4 p-3 rounded bg-green-900 text-green-200';
            resultDiv.textContent = '✓ Token Turnstile obtenu: ' + response.substring(0, 20) + '...';
            resultDiv.classList.remove('hidden');
        });
    </script>
</body>
</html>
EOF

echo -e "${GREEN}✓${NC} Page de test créée: public/test-turnstile.html"

echo ""
echo "════════════════════════════════════════════════════════════════"
echo "RÉSUMÉ DU TEST"
echo "════════════════════════════════════════════════════════════════"
echo ""

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ Tous les tests sont passés!${NC}"
    echo ""
    echo "Turnstile est correctement configuré."
    echo ""
    echo "Pour tester l'intégration:"
    echo "1. Ouvrir: http://127.0.0.1:8001/test-turnstile.html"
    echo "2. La checkbox Turnstile devrait apparaître"
    echo "3. Cochez la case pour obtenir un token"
else
    echo -e "${RED}❌ $ERRORS erreur(s) détectée(s)${NC}"
    echo ""
    echo "Exécutez d'abord:"
    echo "  bash setup-turnstile.sh"
fi

if [ $WARNINGS -gt 0 ]; then
    echo -e "${YELLOW}⚠ $WARNINGS avertissement(s)${NC}"
fi

echo ""
echo "Documentation: TURNSTILE_SETUP.md"
echo ""

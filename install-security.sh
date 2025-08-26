#!/bin/bash

# StudiosDB - Installation complète Turnstile + HTTPS
# Purpose: Configure tout en une commande

echo "
╔══════════════════════════════════════════════════════════════╗
║     StudiosDB - Configuration Sécurité Cloudflare           ║
║                                                              ║
║     • HTTPS avec Nginx                                      ║
║     • Protection Turnstile (gratuit)                        ║
║     • Optimisations Cloudflare                              ║
╚══════════════════════════════════════════════════════════════╝
"

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${BLUE}Configuration en 3 étapes...${NC}"
echo ""

# Étape 1: Turnstile
echo -e "${YELLOW}[1/3] Configuration Turnstile...${NC}"
bash setup-turnstile.sh
echo ""

# Étape 2: Permissions
echo -e "${YELLOW}[2/3] Correction des permissions...${NC}"
chmod +x nginx/deploy.sh test-turnstile.sh setup-turnstile.sh
echo -e "${GREEN}✓${NC} Scripts exécutables"
echo ""

# Étape 3: Compilation
echo -e "${YELLOW}[3/3] Compilation des assets...${NC}"
npm run build
echo ""

echo "════════════════════════════════════════════════════════════════"
echo -e "${GREEN}✅ CONFIGURATION TERMINÉE!${NC}"
echo "════════════════════════════════════════════════════════════════"
echo ""
echo -e "${BLUE}📋 Prochaines étapes:${NC}"
echo ""
echo "1. ${YELLOW}Obtenir les clés Turnstile:${NC}"
echo "   • Allez sur: https://dash.cloudflare.com"
echo "   • Menu gauche → Turnstile → Add site"
echo "   • Domaines: 4lb.ca, www.4lb.ca, localhost"
echo "   • Mode: Managed"
echo "   • Copiez les clés dans .env"
echo ""
echo "2. ${YELLOW}Configurer Nginx (nécessite sudo):${NC}"
echo "   sudo bash nginx/deploy.sh"
echo ""
echo "3. ${YELLOW}Tester l'intégration:${NC}"
echo "   • Local: http://127.0.0.1:8001/test-turnstile.html"
echo "   • Production: https://4lb.ca/login"
echo ""
echo -e "${GREEN}📚 Documentation:${NC}"
echo "   • Guide Turnstile: TURNSTILE_GUIDE.md"
echo "   • Guide Nginx: nginx/DEPLOYMENT_GUIDE.md"
echo "   • Setup Turnstile: TURNSTILE_SETUP.md"
echo ""
echo -e "${BLUE}🧪 Mode test actuel:${NC}"
echo "   Les clés de test sont configurées."
echo "   L'application fonctionne immédiatement!"
echo ""
echo -e "${GREEN}✨ Avantages:${NC}"
echo "   ✓ Protection anti-bot GRATUITE"
echo "   ✓ Pas de compte Google requis"
echo "   ✓ Intégration native Cloudflare"
echo "   ✓ Meilleure performance que reCAPTCHA"
echo "   ✓ GDPR compliant"
echo ""

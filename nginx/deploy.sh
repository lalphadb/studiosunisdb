#!/bin/bash

# StudiosDB - Script de déploiement Nginx pour 4lb.ca
# Purpose: Configure Nginx avec SSL pour production

set -e

echo "=== Configuration Nginx pour 4lb.ca ==="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check if running as root/sudo
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Ce script doit être exécuté avec sudo${NC}"
    exit 1
fi

# Variables
DOMAIN="4lb.ca"
NGINX_CONF="/etc/nginx/sites-available/$DOMAIN"
NGINX_ENABLED="/etc/nginx/sites-enabled/$DOMAIN"
SSL_DIR="/etc/nginx/ssl/$DOMAIN"
PROJECT_PATH="/home/studiosdb/studiosunisdb"

echo "📋 Plan de déploiement:"
echo "  - Domaine: $DOMAIN"
echo "  - Config: $NGINX_CONF"
echo "  - SSL: $SSL_DIR"
echo "  - Projet: $PROJECT_PATH"
echo ""

# 1. Install prerequisites
echo "1. Installation des prérequis..."
apt-get update > /dev/null 2>&1
apt-get install -y nginx certbot python3-certbot-nginx > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Nginx et Certbot installés"

# 2. Create SSL directory
echo "2. Création du répertoire SSL..."
mkdir -p $SSL_DIR
echo -e "${GREEN}✓${NC} Répertoire SSL créé"

# 3. Check Cloudflare or Let's Encrypt
echo ""
echo "3. Configuration SSL - Choisissez une option:"
echo "   1) Cloudflare Origin Certificate (recommandé si proxy Cloudflare)"
echo "   2) Let's Encrypt (si accès direct)"
echo -n "Votre choix [1]: "
read -t 30 ssl_choice
ssl_choice=${ssl_choice:-1}

if [ "$ssl_choice" = "2" ]; then
    echo ""
    echo "Configuration Let's Encrypt..."
    certbot certonly --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email admin@$DOMAIN
    
    # Update nginx conf for Let's Encrypt
    sed -i "s|ssl_certificate .*|ssl_certificate /etc/letsencrypt/live/$DOMAIN/fullchain.pem;|" nginx/4lb.ca.conf
    sed -i "s|ssl_certificate_key .*|ssl_certificate_key /etc/letsencrypt/live/$DOMAIN/privkey.pem;|" nginx/4lb.ca.conf
    
    echo -e "${GREEN}✓${NC} Certificats Let's Encrypt générés"
else
    echo ""
    echo -e "${YELLOW}⚠${NC} Configuration Cloudflare Origin Certificate"
    echo ""
    echo "Instructions:"
    echo "1. Allez sur Cloudflare Dashboard > SSL/TLS > Origin Server"
    echo "2. Créez un certificat origin pour: $DOMAIN et *.$DOMAIN"
    echo "3. Sauvegardez le certificat dans: $SSL_DIR/cert.pem"
    echo "4. Sauvegardez la clé privée dans: $SSL_DIR/key.pem"
    echo ""
    echo "Exemple de commandes après avoir copié les certificats:"
    echo "  echo 'CERTIFICATE_CONTENT' > $SSL_DIR/cert.pem"
    echo "  echo 'PRIVATE_KEY_CONTENT' > $SSL_DIR/key.pem"
    echo "  chmod 600 $SSL_DIR/key.pem"
fi

# 4. Copy nginx configuration
echo ""
echo "4. Installation de la configuration Nginx..."
cp nginx/4lb.ca.conf $NGINX_CONF
echo -e "${GREEN}✓${NC} Configuration copiée"

# 5. Enable site
echo "5. Activation du site..."
ln -sf $NGINX_CONF $NGINX_ENABLED
echo -e "${GREEN}✓${NC} Site activé"

# 6. Test nginx configuration
echo "6. Test de la configuration..."
nginx -t > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Configuration Nginx valide"
else
    echo -e "${RED}✗${NC} Erreur dans la configuration Nginx"
    nginx -t
    exit 1
fi

# 7. Set up firewall
echo "7. Configuration du pare-feu..."
ufw allow 'Nginx Full' > /dev/null 2>&1
ufw allow 22/tcp > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Pare-feu configuré"

# 8. Set Laravel permissions
echo "8. Configuration des permissions Laravel..."
chown -R www-data:www-data $PROJECT_PATH/storage
chown -R www-data:www-data $PROJECT_PATH/bootstrap/cache
chmod -R 775 $PROJECT_PATH/storage
chmod -R 775 $PROJECT_PATH/bootstrap/cache
echo -e "${GREEN}✓${NC} Permissions configurées"

# 9. Create log rotation
echo "9. Configuration de la rotation des logs..."
cat > /etc/logrotate.d/4lb.ca << 'EOF'
/var/log/nginx/4lb.ca.*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data adm
    sharedscripts
    prerotate
        if [ -d /etc/logrotate.d/httpd-prerotate ]; then \
            run-parts /etc/logrotate.d/httpd-prerotate; \
        fi
    endscript
    postrotate
        invoke-rc.d nginx rotate >/dev/null 2>&1
    endscript
}
EOF
echo -e "${GREEN}✓${NC} Rotation des logs configurée"

# 10. Reload nginx
echo "10. Redémarrage de Nginx..."
systemctl reload nginx
systemctl enable nginx
echo -e "${GREEN}✓${NC} Nginx redémarré"

echo ""
echo "=== Configuration terminée ==="
echo ""
echo "📌 Prochaines étapes:"
echo ""
echo "1. Configuration Cloudflare:"
echo "   - DNS: A record → IP du serveur"
echo "   - SSL/TLS: Mode Full (strict)"
echo "   - Activer le proxy (orange cloud)"
echo ""
echo "2. Configuration Laravel (.env):"
echo "   - APP_URL=https://4lb.ca"
echo "   - FORCE_HTTPS=true"
echo ""
echo "3. Test:"
echo "   - https://4lb.ca"
echo "   - https://www.4lb.ca"
echo ""

# Create status check script
cat > check-nginx.sh << 'EOF'
#!/bin/bash
echo "=== Status Nginx 4lb.ca ==="
systemctl status nginx --no-pager | head -10
echo ""
echo "Test HTTPS:"
curl -I https://4lb.ca 2>/dev/null | head -3
echo ""
echo "Logs récents:"
tail -5 /var/log/nginx/4lb.ca.error.log 2>/dev/null || echo "Pas d'erreurs"
EOF
chmod +x check-nginx.sh

echo "Utilisez ./check-nginx.sh pour vérifier le status"
echo ""

#!/bin/bash

# Script de vérification et correction des chemins StudiosDB
# ============================================================

echo "🔍 VÉRIFICATION DES CHEMINS STUDIOSDB"
echo "======================================"
echo ""

# Variables
PROJECT_DIR="/home/studiosdb/studiosunisdb"
OLD_PATH="studiosdb_v5_pro"
ERRORS=0
WARNINGS=0

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction pour vérifier un fichier/dossier
check_path() {
    local path=$1
    local description=$2
    
    if [ -e "$path" ]; then
        echo -e "${GREEN}✅${NC} $description: $path"
    else
        echo -e "${RED}❌${NC} $description: $path (N'EXISTE PAS)"
        ((ERRORS++))
    fi
}

# Fonction pour vérifier les permissions
check_permissions() {
    local path=$1
    local description=$2
    
    if [ -w "$path" ]; then
        echo -e "${GREEN}✅${NC} $description: Writable"
    else
        echo -e "${YELLOW}⚠️${NC} $description: Non-writable"
        ((WARNINGS++))
    fi
}

echo "1️⃣ Vérification des dossiers principaux..."
echo "----------------------------------------"
check_path "$PROJECT_DIR" "Projet principal"
check_path "$PROJECT_DIR/app" "Dossier app"
check_path "$PROJECT_DIR/bootstrap" "Dossier bootstrap"
check_path "$PROJECT_DIR/config" "Dossier config"
check_path "$PROJECT_DIR/database" "Dossier database"
check_path "$PROJECT_DIR/public" "Dossier public"
check_path "$PROJECT_DIR/resources" "Dossier resources"
check_path "$PROJECT_DIR/routes" "Dossier routes"
check_path "$PROJECT_DIR/storage" "Dossier storage"
check_path "$PROJECT_DIR/vendor" "Dossier vendor"
check_path "$PROJECT_DIR/node_modules" "Dossier node_modules"

echo ""
echo "2️⃣ Vérification des fichiers de configuration..."
echo "------------------------------------------------"
check_path "$PROJECT_DIR/.env" "Fichier .env"
check_path "$PROJECT_DIR/composer.json" "Fichier composer.json"
check_path "$PROJECT_DIR/package.json" "Fichier package.json"
check_path "$PROJECT_DIR/vite.config.js" "Fichier vite.config.js"
check_path "$PROJECT_DIR/artisan" "Fichier artisan"

echo ""
echo "3️⃣ Vérification des permissions..."
echo "-----------------------------------"
check_permissions "$PROJECT_DIR/storage" "Storage"
check_permissions "$PROJECT_DIR/storage/logs" "Storage/logs"
check_permissions "$PROJECT_DIR/storage/app" "Storage/app"
check_permissions "$PROJECT_DIR/storage/framework" "Storage/framework"
check_permissions "$PROJECT_DIR/bootstrap/cache" "Bootstrap/cache"

echo ""
echo "4️⃣ Vérification du lien symbolique storage..."
echo "----------------------------------------------"
if [ -L "$PROJECT_DIR/public/storage" ]; then
    LINK_TARGET=$(readlink "$PROJECT_DIR/public/storage")
    EXPECTED_TARGET="$PROJECT_DIR/storage/app/public"
    
    if [ "$LINK_TARGET" = "$EXPECTED_TARGET" ]; then
        echo -e "${GREEN}✅${NC} Lien symbolique correct: public/storage -> storage/app/public"
    else
        echo -e "${RED}❌${NC} Lien symbolique incorrect!"
        echo "   Actuel: $LINK_TARGET"
        echo "   Attendu: $EXPECTED_TARGET"
        ((ERRORS++))
    fi
else
    echo -e "${RED}❌${NC} Lien symbolique manquant!"
    ((ERRORS++))
fi

echo ""
echo "5️⃣ Recherche de références à l'ancien chemin..."
echo "------------------------------------------------"
echo "Recherche de '$OLD_PATH' dans les fichiers..."

# Recherche dans les fichiers (excluant vendor, node_modules, .git)
FOUND_FILES=$(grep -rl "$OLD_PATH" "$PROJECT_DIR" \
    --exclude-dir=vendor \
    --exclude-dir=node_modules \
    --exclude-dir=.git \
    --exclude-dir=storage \
    --exclude-dir=backups \
    2>/dev/null | head -10)

if [ -z "$FOUND_FILES" ]; then
    echo -e "${GREEN}✅${NC} Aucune référence à l'ancien chemin trouvée"
else
    echo -e "${YELLOW}⚠️${NC} Références trouvées dans:"
    echo "$FOUND_FILES" | while read file; do
        echo "   - $file"
        ((WARNINGS++))
    done
fi

echo ""
echo "6️⃣ Vérification de la base de données..."
echo "----------------------------------------"
php -r "
try {
    \$pdo = new PDO('mysql:host=127.0.0.1;dbname=studiosdb', 'root', 'LkmP0km1');
    echo \"✅ Connexion à la base de données OK\n\";
} catch (Exception \$e) {
    echo \"❌ Erreur de connexion DB: \" . \$e->getMessage() . \"\n\";
}
"

echo ""
echo "7️⃣ Vérification des services..."
echo "--------------------------------"

# Vérifier PHP
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1)
    echo -e "${GREEN}✅${NC} PHP: $PHP_VERSION"
else
    echo -e "${RED}❌${NC} PHP non trouvé"
    ((ERRORS++))
fi

# Vérifier Composer
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version 2>/dev/null | head -n 1)
    echo -e "${GREEN}✅${NC} Composer: $COMPOSER_VERSION"
else
    echo -e "${RED}❌${NC} Composer non trouvé"
    ((ERRORS++))
fi

# Vérifier Node
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    echo -e "${GREEN}✅${NC} Node: $NODE_VERSION"
else
    echo -e "${RED}❌${NC} Node non trouvé"
    ((ERRORS++))
fi

# Vérifier NPM
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm -v)
    echo -e "${GREEN}✅${NC} NPM: $NPM_VERSION"
else
    echo -e "${RED}❌${NC} NPM non trouvé"
    ((ERRORS++))
fi

echo ""
echo "======================================"
echo "📊 RÉSUMÉ DE LA VÉRIFICATION"
echo "======================================"

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo -e "${GREEN}✅ Tout est correctement configuré!${NC}"
elif [ $ERRORS -eq 0 ]; then
    echo -e "${YELLOW}⚠️ Configuration OK avec $WARNINGS avertissement(s)${NC}"
else
    echo -e "${RED}❌ $ERRORS erreur(s) et $WARNINGS avertissement(s) détectés${NC}"
fi

echo ""
echo "🔧 Actions recommandées:"
if [ $ERRORS -gt 0 ] || [ $WARNINGS -gt 0 ]; then
    echo "1. Corriger les chemins dans les fichiers listés"
    echo "2. Vérifier les permissions avec: sudo chown -R studiosdb:www-data $PROJECT_DIR"
    echo "3. Régénérer le lien storage si nécessaire: php artisan storage:link"
    echo "4. Nettoyer les caches: php artisan cache:clear && php artisan config:clear"
else
    echo "Aucune action requise - Le système est prêt!"
fi

echo ""
echo "📝 Logs disponibles dans: $PROJECT_DIR/storage/logs/"
echo "🔗 Accès application: http://127.0.0.1:8001"

#!/bin/bash

# ============================================================================
# SCRIPT DE VÉRIFICATION DÉPLOIEMENT - StudiosUnisDB v3.8.3
# ============================================================================

echo "🔍 VÉRIFICATION DÉPLOIEMENT STUDIOSUNISDB"
echo "========================================"
echo ""

# Configuration MySQL (pour éviter les warnings)
MYSQL_CMD="mysql -u root -pLkmP0km1 -D studiosdb --silent"

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

success() { echo -e "${GREEN}✅ $1${NC}"; }
error() { echo -e "${RED}❌ $1${NC}"; }
warning() { echo -e "${YELLOW}⚠️  $1${NC}"; }
info() { echo -e "${BLUE}ℹ️  $1${NC}"; }

# Compteurs
ERRORS=0
WARNINGS=0

# ============================================================================
# 1. VÉRIFICATION BASE DE DONNÉES
# ============================================================================

info "1. VÉRIFICATION BASE DE DONNÉES"
echo "--------------------------------"

# Test connexion
if mysql -u root -pLkmP0km1 -e "USE studiosdb;" 2>/dev/null; then
    success "Connexion base de données OK"
else
    error "Impossible de se connecter à la base de données"
    ((ERRORS++))
    exit 1
fi

# Tables critiques
REQUIRED_TABLES=("users" "ecoles" "membres" "cours" "ceintures" "membre_ceintures" "presences" "seminaires" "roles" "permissions" "model_has_roles" "model_has_permissions")

echo ""
for table in "${REQUIRED_TABLES[@]}"; do
    if $MYSQL_CMD -e "DESCRIBE $table;" >/dev/null 2>&1; then
        success "Table '$table'"
    else
        error "Table '$table' manquante"
        ((ERRORS++))
    fi
done

# ============================================================================
# 2. VÉRIFICATION UTILISATEURS ET PERMISSIONS
# ============================================================================

echo ""
info "2. VÉRIFICATION UTILISATEURS ET PERMISSIONS"
echo "--------------------------------------------"

# Utilisateurs critiques
USERS_COUNT=$($MYSQL_CMD -e "SELECT COUNT(*) FROM users;" 2>/dev/null)
SUPERADMIN_EXISTS=$($MYSQL_CMD -e "SELECT COUNT(*) FROM users WHERE email = 'lalpha@4lb.ca';" 2>/dev/null)
ADMIN_EXISTS=$($MYSQL_CMD -e "SELECT COUNT(*) FROM users WHERE email = 'louis@4lb.ca';" 2>/dev/null)

success "Total utilisateurs: $USERS_COUNT"

if [ "$SUPERADMIN_EXISTS" -eq 1 ]; then
    success "SuperAdmin lalpha@4lb.ca présent"
else
    error "SuperAdmin lalpha@4lb.ca MANQUANT"
    ((ERRORS++))
fi

if [ "$ADMIN_EXISTS" -eq 1 ]; then
    success "Admin louis@4lb.ca présent"
else
    error "Admin louis@4lb.ca MANQUANT"
    ((ERRORS++))
fi

# Rôles et permissions
ROLES_COUNT=$($MYSQL_CMD -e "SELECT COUNT(*) FROM roles;" 2>/dev/null)
PERMISSIONS_COUNT=$($MYSQL_CMD -e "SELECT COUNT(*) FROM permissions;" 2>/dev/null)

if [ "$ROLES_COUNT" -ge 4 ]; then
    success "Rôles: $ROLES_COUNT (minimum requis: 4)"
else
    error "Rôles insuffisants: $ROLES_COUNT/4"
    ((ERRORS++))
fi

if [ "$PERMISSIONS_COUNT" -ge 20 ]; then
    success "Permissions: $PERMISSIONS_COUNT"
else
    warning "Permissions: $PERMISSIONS_COUNT (recommandé: 30+)"
    ((WARNINGS++))
fi

# ============================================================================
# 3. VÉRIFICATION DONNÉES MÉTIER
# ============================================================================

echo ""
info "3. VÉRIFICATION DONNÉES MÉTIER"
echo "-------------------------------"

# Écoles
ECOLES_COUNT=$($MYSQL_CMD -e "SELECT COUNT(*) FROM ecoles;" 2>/dev/null)
success "Écoles: $ECOLES_COUNT"

if [ "$ECOLES_COUNT" -lt 18 ]; then
    warning "Peu d'écoles ($ECOLES_COUNT). Objectif: 22"
    ((WARNINGS++))
fi

# Ceintures
CEINTURES_COUNT=$($MYSQL_CMD -e "SELECT COUNT(*) FROM ceintures;" 2>/dev/null)
if [ "$CEINTURES_COUNT" -gt 0 ]; then
    success "Ceintures: $CEINTURES_COUNT"
else
    warning "Aucune ceinture configurée"
    ((WARNINGS++))
fi

# Membres
MEMBRES_COUNT=$($MYSQL_CMD -e "SELECT COUNT(*) FROM membres;" 2>/dev/null)
success "Membres: $MEMBRES_COUNT"

# ============================================================================
# 4. VÉRIFICATION TECHNIQUE
# ============================================================================

echo ""
info "4. VÉRIFICATION TECHNIQUE"
echo "-------------------------"

# Cache permissions
if php artisan permission:cache-reset >/dev/null 2>&1; then
    success "Cache permissions OK"
else
    error "Problème cache permissions"
    ((ERRORS++))
fi

# Routes admin
ROUTES_COUNT=$(php artisan route:list --path=admin 2>/dev/null | grep -c "admin/")
if [ "$ROUTES_COUNT" -gt 15 ]; then
    success "Routes admin: $ROUTES_COUNT"
else
    warning "Routes admin: $ROUTES_COUNT (vérifiez la configuration)"
    ((WARNINGS++))
fi

# Fichiers critiques
CRITICAL_FILES=("app/Http/Controllers/Admin/DashboardController.php" "app/Models/User.php" "routes/admin.php" "config/app.php")

for file in "${CRITICAL_FILES[@]}"; do
    if [ -f "$file" ]; then
        success "Fichier '$file'"
    else
        error "Fichier '$file' manquant"
        ((ERRORS++))
    fi
done

# ============================================================================
# 5. VÉRIFICATION PERMISSIONS SPÉCIFIQUES
# ============================================================================

echo ""
info "5. VÉRIFICATION PERMISSIONS MODULES"
echo "-----------------------------------"

# Test permissions Louis (admin d'école)
LOUIS_PERMISSIONS=$($MYSQL_CMD -e "
SELECT COUNT(*) FROM permissions p
JOIN role_has_permissions rhp ON p.id = rhp.permission_id
JOIN roles r ON rhp.role_id = r.id
WHERE r.name = 'admin' AND p.name IN ('view-ceintures', 'manage-ceintures', 'view-membres', 'view-cours');
" 2>/dev/null)

if [ "$LOUIS_PERMISSIONS" -ge 3 ]; then
    success "Permissions admin d'école: OK"
else
    error "Permissions admin d'école insuffisantes"
    ((ERRORS++))
fi

# ============================================================================
# 6. RÉSUMÉ FINAL
# ============================================================================

echo ""
echo "==============================================="
if [ "$ERRORS" -eq 0 ]; then
    if [ "$WARNINGS" -eq 0 ]; then
        echo -e "${GREEN}🎉 PARFAIT - Système 100% opérationnel${NC}"
        echo -e "${GREEN}✅ Prêt pour la production${NC}"
    else
        echo -e "${YELLOW}🔶 BON - $WARNINGS avertissement(s) mineur(s)${NC}"
        echo -e "${GREEN}✅ Déploiement possible${NC}"
    fi
else
    echo -e "${RED}🚨 PROBLÈMES - $ERRORS erreur(s) critique(s)${NC}"
    echo -e "${RED}❌ Corrigez avant déploiement${NC}"
fi

echo ""
echo "📊 STATISTIQUES FINALES:"
echo "• Tables: ${#REQUIRED_TABLES[@]} vérifiées"
echo "• Utilisateurs: $USERS_COUNT (SuperAdmin + Admin: ✓)"
echo "• Écoles: $ECOLES_COUNT"
echo "• Ceintures: $CEINTURES_COUNT"
echo "• Membres: $MEMBRES_COUNT"
echo "• Rôles: $ROLES_COUNT"
echo "• Permissions: $PERMISSIONS_COUNT"
echo "• Routes admin: $ROUTES_COUNT"

echo ""
echo "🔗 URLs importantes:"
echo "• Dashboard: http://127.0.0.1:8001/admin"
echo "• Ceintures: http://127.0.0.1:8001/admin/ceintures"
echo "• Production: https://4lb.ca"

echo ""
echo "👥 Comptes de test:"
echo "• SuperAdmin: lalpha@4lb.ca / QwerTfc443-studios!"
echo "• Admin École: louis@4lb.ca / B0bby2111"

echo ""
echo "🕐 Vérification terminée: $(date)"

# Code de sortie
if [ "$ERRORS" -gt 0 ]; then
    exit 1
else
    exit 0
fi

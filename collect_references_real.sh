#!/bin/bash
# ============================================================================
# SCRIPT COLLECTE RÉFÉRENCES - StudiosUnisDB v4.0 (BASÉ SUR AUDIT RÉEL)
# À exécuter avant chaque nouveau chat avec Claude
# ============================================================================

echo "🔄 COLLECTE RÉFÉRENCES StudiosUnisDB v4.0 (AUDIT CONFIRMÉ)"
echo "=========================================================="

# Configuration RÉELLE basée sur l'audit
PROJECT_PATH="/home/studiosdb/studiosunisdb"
OUTPUT_FILE="references_studiosunisdb_$(date +%Y%m%d_%H%M%S).txt"

# Couleurs
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

info() { echo -e "${BLUE}ℹ️  $1${NC}"; }
section() { echo -e "\n${YELLOW}$1${NC}"; }
success() { echo -e "${GREEN}✅ $1${NC}"; }
error() { echo -e "${RED}❌ $1${NC}"; }

# Aller dans le répertoire du projet
cd "$PROJECT_PATH" || exit 1

# Créer le fichier de sortie
exec > >(tee "$OUTPUT_FILE")

section "═══════════════════════════════════════════════════════════════"
section "        📋 RÉFÉRENCES StudiosUnisDB v4.0 (AUDIT RÉEL)          "
section "═══════════════════════════════════════════════════════════════"

echo "Date: $(date)"
echo "Path: $PROJECT_PATH"
echo "Laravel: $(php artisan --version 2>/dev/null || echo 'Laravel version non détectée')"
echo "PHP: $(php --version | head -1)"
echo "Base audit: 2025-06-25 09:32:43"

section "═══════════════════════════════════════════════════════════════"
section "                🎮 CONTRÔLEURS DE RÉFÉRENCE                   "
section "═══════════════════════════════════════════════════════════════"

# UserController (selon audit - présent)
section "--- UserController (RÉFÉRENCE PRINCIPALE) ---"
if [ -f "app/Http/Controllers/Admin/UserController.php" ]; then
    cat app/Http/Controllers/Admin/UserController.php
    success "UserController collecté"
else
    error "UserController manquant (audit indiquait présent)"
fi

# EcoleController (selon audit - présent)
section "--- EcoleController (RÉFÉRENCE VALIDÉE) ---"
if [ -f "app/Http/Controllers/Admin/EcoleController.php" ]; then
    cat app/Http/Controllers/Admin/EcoleController.php
    success "EcoleController collecté"
else
    error "EcoleController manquant"
fi

# CoursController (selon audit - présent)
section "--- CoursController (RÉFÉRENCE VALIDÉE) ---"
if [ -f "app/Http/Controllers/Admin/CoursController.php" ]; then
    cat app/Http/Controllers/Admin/CoursController.php
    success "CoursController collecté"
else
    error "CoursController manquant"
fi

# DashboardController (selon audit - présent)
section "--- DashboardController ---"
if [ -f "app/Http/Controllers/Admin/DashboardController.php" ]; then
    cat app/Http/Controllers/Admin/DashboardController.php
    success "DashboardController collecté"
else
    error "DashboardController manquant"
fi

section "═══════════════════════════════════════════════════════════════"
section "                🏗️ MODÈLES DE RÉFÉRENCE                      "
section "═══════════════════════════════════════════════════════════════"

# User Model (selon audit - présent)
section "--- User Model (MODÈLE CENTRAL) ---"
if [ -f "app/Models/User.php" ]; then
    cat app/Models/User.php
    success "User Model collecté"
else
    error "User Model manquant (audit indiquait présent)"
fi

# Ecole Model
section "--- Ecole Model ---"
if [ -f "app/Models/Ecole.php" ]; then
    cat app/Models/Ecole.php
    success "Ecole Model collecté"
else
    error "Ecole Model manquant"
fi

# Cours Model
section "--- Cours Model ---"
if [ -f "app/Models/Cours.php" ]; then
    cat app/Models/Cours.php
    success "Cours Model collecté"
else
    error "Cours Model manquant"
fi

# Autres modèles (selon audit - 11 modèles total)
section "--- Autres Modèles ---"
for model in "Ceinture" "Presence" "Paiement" "Seminaire" "MembreCeinture" "InscriptionCours" "InscriptionSeminaire"; do
    if [ -f "app/Models/${model}.php" ]; then
        echo "✅ ${model}.php présent"
        head -50 "app/Models/${model}.php"
    else
        echo "❌ ${model}.php manquant"
    fi
done

section "═══════════════════════════════════════════════════════════════"
section "                📝 FORMREQUESTS DE RÉFÉRENCE                  "
section "═══════════════════════════════════════════════════════════════"

# UserRequest
section "--- UserRequest ---"
if [ -f "app/Http/Requests/UserRequest.php" ]; then
    cat app/Http/Requests/UserRequest.php
    success "UserRequest collecté"
else
    error "UserRequest manquant"
fi

# Autres FormRequests
for request in "EcoleRequest" "CoursRequest" "CeintureRequest" "PresenceRequest" "PaiementRequest" "SeminaireRequest"; do
    section "--- ${request} ---"
    if [ -f "app/Http/Requests/${request}.php" ]; then
        cat "app/Http/Requests/${request}.php"
        success "${request} collecté"
    else
        error "${request} manquant"
    fi
done

section "═══════════════════════════════════════════════════════════════"
section "                🛡️ POLICIES DE RÉFÉRENCE                     "
section "═══════════════════════════════════════════════════════════════"

# UserPolicy
section "--- UserPolicy ---"
if [ -f "app/Policies/UserPolicy.php" ]; then
    cat app/Policies/UserPolicy.php
    success "UserPolicy collecté"
else
    error "UserPolicy manquant"
fi

# Autres Policies
for policy in "EcolePolicy" "CoursPolicy" "CeinturePolicy" "PresencePolicy" "PaiementPolicy" "SeminairePolicy"; do
    section "--- ${policy} ---"
    if [ -f "app/Policies/${policy}.php" ]; then
        cat "app/Policies/${policy}.php"
        success "${policy} collecté"
    else
        error "${policy} manquant"
    fi
done

section "═══════════════════════════════════════════════════════════════"
section "                🛣️ ROUTES & CONFIGURATION (RÉELS)             "
section "═══════════════════════════════════════════════════════════════"

# Routes Admin (confirmé par audit)
section "--- Routes Admin (admin.php) ---"
if [ -f "routes/admin.php" ]; then
    cat routes/admin.php
    success "Routes admin.php collectées"
else
    error "Routes admin.php manquantes (audit indiquait présent)"
fi

# Routes Web (confirmé par audit)
section "--- Routes Web (web.php) ---"
if [ -f "routes/web.php" ]; then
    cat routes/web.php
    success "Routes web.php collectées"
else
    error "Routes web.php manquantes"
fi

# Routes Auth
section "--- Routes Auth (auth.php) ---"
if [ -f "routes/auth.php" ]; then
    head -50 routes/auth.php
    success "Routes auth.php collectées (extrait)"
else
    error "Routes auth.php manquantes"
fi

section "═══════════════════════════════════════════════════════════════"
section "                ⚙️ CONFIGURATION SYSTÈME                      "
section "═══════════════════════════════════════════════════════════════"

# Configuration Permissions (selon audit - configuré)
section "--- Configuration Permissions ---"
if [ -f "config/permission.php" ]; then
    head -100 config/permission.php
    success "Config permissions collectée"
else
    error "Config permissions manquante (audit indiquait configuré)"
fi

# Configuration App
section "--- Configuration App ---"
if [ -f "config/app.php" ]; then
    grep -A5 -B5 "providers\|aliases" config/app.php | head -50
    success "Config app collectée (extrait)"
else
    error "Config app manquante"
fi

section "═══════════════════════════════════════════════════════════════"
section "                🗄️ STRUCTURE BASE DE DONNÉES (28 TABLES)     "
section "═══════════════════════════════════════════════════════════════"

# Migration Users (table centrale)
section "--- Migration Users (Table Centrale) ---"
if ls database/migrations/*_create_users_table.php 1> /dev/null 2>&1; then
    cat database/migrations/*_create_users_table.php
    success "Migration users collectée"
else
    error "Migration users manquante"
fi

# Migration Ecoles
section "--- Migration Ecoles ---"
if ls database/migrations/*_create_ecoles_table.php 1> /dev/null 2>&1; then
    cat database/migrations/*_create_ecoles_table.php
    success "Migration ecoles collectée"
else
    error "Migration ecoles manquante"
fi

# Migration Permissions Spatie (selon audit - configuré)
section "--- Migration Permissions Spatie ---"
if ls database/migrations/*_create_permission_tables.php 1> /dev/null 2>&1; then
    head -150 database/migrations/*_create_permission_tables.php
    success "Migration permissions collectée"
else
    error "Migration permissions manquante (audit indiquait configuré)"
fi

# Autres migrations importantes
section "--- Autres Migrations Importantes ---"
for table in "cours" "ceintures" "presences" "paiements" "seminaires"; do
    if ls database/migrations/*_create_${table}_table.php 1> /dev/null 2>&1; then
        echo "✅ Migration ${table} présente"
        head -50 database/migrations/*_create_${table}_table.php
    else
        echo "❌ Migration ${table} manquante"
    fi
done

section "═══════════════════════════════════════════════════════════════"
section "                🎨 VUES DE RÉFÉRENCE (79 VUES TOTAL)          "
section "═══════════════════════════════════════════════════════════════"

# Layout Admin
section "--- Layout Admin ---"
if [ -f "resources/views/layouts/admin.blade.php" ]; then
    head -150 resources/views/layouts/admin.blade.php
    success "Layout admin collecté"
else
    error "Layout admin manquant"
fi

# Vue Users Index (selon audit - vues users présentes)
section "--- Vue Users Index ---"
if [ -f "resources/views/admin/users/index.blade.php" ]; then
    cat resources/views/admin/users/index.blade.php
    success "Vue users index collectée"
else
    error "Vue users index manquante (audit indiquait présent)"
fi

# Autres vues importantes
section "--- Autres Vues Admin ---"
for module in "ecoles" "cours" "ceintures" "presences" "paiements" "seminaires"; do
    if [ -d "resources/views/admin/${module}" ]; then
        echo "✅ Dossier vues ${module} présent"
        ls -la "resources/views/admin/${module}/" | head -10
    else
        echo "❌ Dossier vues ${module} manquant"
    fi
done

section "═══════════════════════════════════════════════════════════════"
section "                📊 INFORMATIONS SYSTÈME                      "
section "═══════════════════════════════════════════════════════════════"

# Composer Packages
section "--- Packages Composer ---"
if [ -f "composer.json" ]; then
    grep -A30 '"require"' composer.json
    success "Packages collectés"
else
    error "composer.json manquant"
fi

# Permissions/Rôles Spatie (selon audit - configuré)
section "--- Permissions/Rôles Spatie ---"
echo "Tentative de lecture des rôles/permissions..."
php artisan tinker --execute="
try {
    echo 'RÔLES CONFIGURÉS:' . PHP_EOL;
    \Spatie\Permission\Models\Role::all()->each(function(\$role) {
        echo '- ' . \$role->name . PHP_EOL;
    });
    echo PHP_EOL . 'PERMISSIONS CONFIGURÉES:' . PHP_EOL;
    \Spatie\Permission\Models\Permission::take(10)->get()->each(function(\$perm) {
        echo '- ' . \$perm->name . PHP_EOL;
    });
} catch(Exception \$e) {
    echo 'Erreur lecture Spatie: ' . \$e->getMessage() . PHP_EOL;
}
" 2>/dev/null || echo "❌ Impossible de lire les rôles/permissions"

# Middleware
section "--- Middleware Laravel ---"
if [ -f "app/Http/Kernel.php" ]; then
    grep -A20 "protected \$middleware" app/Http/Kernel.php
    success "Middleware collecté"
else
    error "Kernel.php manquant"
fi

section "═══════════════════════════════════════════════════════════════"
section "                ✅ COLLECTE TERMINÉE                          "
section "═══════════════════════════════════════════════════════════════"

# Restaurer la sortie normale
exec > /dev/tty

success "Références collectées dans: $OUTPUT_FILE"
info "Taille du fichier: $(du -h "$OUTPUT_FILE" | cut -f1)"
info "Nombre de lignes: $(wc -l < "$OUTPUT_FILE")"

echo ""
echo "🎯 INSTRUCTIONS CRITIQUES POUR CLAUDE:"
echo "======================================"
echo "1. ✅ Joindre le fichier: $OUTPUT_FILE"
echo "2. ✅ Utiliser UNIQUEMENT les références collectées"
echo "3. ❌ JAMAIS supposer l'existence de fichiers"
echo "4. ✅ Respecter l'architecture unifiée User (PAS membres)"
echo "5. ✅ Utiliser user_id partout (JAMAIS membre_id)"
echo ""
echo "📋 ARCHITECTURE CONFIRMÉE PAR AUDIT:"
echo "- Tables: 28 (références obsolètes: 0)"
echo "- Contrôleurs: 23 (Admin: 11)"
echo "- Modèles: 11 (User.php central)"
echo "- Routes: 139 (Admin: 64)"
echo "- Laravel 12.19.3 + PHP 8.3.6"
echo ""
echo "🚨 RAPPEL RÈGLES ABSOLUES:"
echo "- Table users (JAMAIS membres)"
echo "- Relations user_id (JAMAIS membre_id)"
echo "- Laravel 12.19 middleware HasMiddleware"
echo "- Spatie Permission hasRole()"
echo "- Tailwind CSS uniquement"
echo "- Messages français dans vues"
echo ""

success "✅ Collecte basée sur AUDIT RÉEL terminée avec succès!"

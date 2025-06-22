#!/bin/bash

# ===============================================================================
# SCRIPT D'AUDIT DE CONFORMITÉ POUR PROJET LARAVEL - StudiosUnisDB v5.0
# Auteur: Gemini Architect
# Date: 2025-06-22
# Description: Ce script vérifie la conformité des modules par rapport
#              aux standards professionnels définis (FormRequest, Policy, etc.)
# ===============================================================================

set -e
export LANG=C.UTF-8

# --- CONFIGURATION ---
PROJECT_PATH="/home/studiosdb/studiosunisdb"
AUDIT_DIR="$PROJECT_PATH/audit"
TIMESTAMP=$(date '+%Y%m%d_%H%M%S')
SUMMARY_FILE="$AUDIT_DIR/conformity_report_${TIMESTAMP}.md"
CONTROLLER_PATH="app/Http/Controllers/Admin"

# --- COULEURS ---
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# --- FONCTIONS ---
log_info() { echo -e "${BLUE}INFO:${NC} $1"; }
log_ok() { echo -e "${GREEN}✅ OK:${NC} $1"; }
log_warn() { echo -e "${YELLOW}⚠️  WARN:${NC} $1"; }
log_error() { echo -e "${RED}❌ ERROR:${NC} $1"; }
write_summary() { echo "$1" >> "$SUMMARY_FILE"; }

# --- INITIALISATION ---
mkdir -p "$AUDIT_DIR"
cd "$PROJECT_PATH" || { log_error "Chemin du projet introuvable."; exit 1; }

cat > "$SUMMARY_FILE" << EOF
# 📋 RAPPORT DE CONFORMITÉ D'ARCHITECTURE - StudiosUnisDB
**Date de l'audit:** $(date '+%Y-%m-%d %H:%M:%S')

Ce rapport vérifie la conformité de chaque module par rapport au "Module Blueprint" standard.
Légende: ✅Conforme, ⚠️Partiel/Non Standard, ❌Manquant.

EOF

# --- AUDIT DE CONFORMITÉ DES MODULES ---
log_info "Début de l'audit de conformité des modules..."
write_summary "## 🔎 Audit des Modules de l'Administration"
write_summary ""
write_summary "| Module | FormRequest | Policy | Vues | Middleware | Tests | Conformité |"
write_summary "| :--- | :---: | :---: | :---: | :---: | :---: | :---: |"

for controller_file in "$CONTROLLER_PATH"/*.php; do
    controller_name=$(basename "$controller_file" .php)
    module_name=${controller_name/Controller/}
    
    if [[ "$controller_name" == "DashboardController.php.BROKEN" ]]; then continue; fi

    log_info "Analyse du module: $module_name"

    # Vérification 1: FormRequest
    request_file="app/Http/Requests/Admin/${module_name}Request.php"
    [[ -f "$request_file" ]] && request_status="✅" || request_status="❌"
    
    # Vérification 2: Policy (gère le cas spécial de Ceinture -> MembreCeinture)
    policy_name="$module_name"
    if [ "$module_name" == "Ceinture" ]; then policy_name="MembreCeinture"; fi
    policy_file="app/Policies/${policy_name}Policy.php"
    [[ -f "$policy_file" ]] && policy_status="✅" || policy_status="❌"
    
    # Vérification 3: Dossier de vues (Pluriel par défaut, avec exceptions)
    module_lower=$(tr '[:upper:]' '[:lower:]' <<< "$module_name")
    view_dir_plural="resources/views/admin/${module_lower}s"
    view_dir_singular="resources/views/admin/${module_lower}"
    if [ -d "$view_dir_plural" ]; then
        view_status="✅"
    elif [ -d "$view_dir_singular" ]; then
        view_status="✅"
    else
        view_status="⚠️"
    fi

    # Vérification 4: Middleware Laravel 12
    grep -q "public static function middleware" "$controller_file" && middleware_status="✅" || middleware_status="❌"
    
    # Vérification 5: Fichier de Test
    test_file="tests/Feature/Admin/${module_name}Test.php"
    [[ -f "$test_file" ]] && test_status="✅" || test_status="❌"
    
    # Évaluation de la conformité
    conformity="🔴"
    if [ "$request_status" == "✅" ] && [ "$policy_status" == "✅" ] && [ "$view_status" == "✅" ] && [ "$middleware_status" == "✅" ]; then
        conformity="🟢"
    elif [ "$request_status" == "✅" ] || [ "$policy_status" == "✅" ] || [ "$middleware_status" == "✅" ]; then
        conformity="🟡"
    fi

    write_summary "| **$module_name** | $request_status | $policy_status | $view_status | $middleware_status | $test_status | $conformity |"
done

# --- RECHERCHE D'INCOHÉRENCES GLOBALES ---
log_info "Recherche des incohérences terminologiques restantes..."
write_summary "\n## ☠️ Détection de Terminologie Obsolète (`membre`)"
write_summary ""
obsolete_count=$(grep -r -i -c "membre" app/Http/Controllers/ resources/views/admin/ | awk -F: '{s+=$2} END {print s}')
if [ "$obsolete_count" -gt 0 ]; then
    write_summary "- 🚨 **$obsolete_count** références à 'membre' trouvées. Ci-dessous un échantillon :"
    write_summary "\`\`\`"
    grep -r -i -n "membre" app/Http/Controllers/ resources/views/admin/ | head -10 >> "$SUMMARY_FILE"
    write_summary "\`\`\`"
else
    write_summary "- ✅ Aucune référence obsolète majeure trouvée dans le code applicatif."
fi

# --- VÉRIFICATION DE LA SÉCURITÉ DES ROUTES ---
log_info "Analyse de la sécurité des routes..."
write_summary "\n## 🛡️ Audit de Sécurité des Routes"
write_summary ""
unprotected_routes=$(php artisan route:list --json | jq -r '.[] | select(.uri | startswith("admin/")) | select(.action | test("Telescope|Dashboard|Login|Logout") | not) | select(.middleware | contains(["can:"]) | not) | .method + " " + .uri')
if [ -z "$unprotected_routes" ]; then
    write_summary "- ✅ Toutes les routes de ressources admin semblent protégées par des permissions via middleware `can:`."
else
    write_summary "- ❌ **ROUTES ADMIN SANS MIDDLEWARE 'CAN:' DÉTECTÉES :**"
    write_summary "\`\`\`"
    echo "$unprotected_routes" >> "$SUMMARY_FILE"
    write_summary "\`\`\`"
fi

echo ""
log_ok "Audit de conformité terminé."
log_info "Rapport généré : $SUMMARY_FILE"

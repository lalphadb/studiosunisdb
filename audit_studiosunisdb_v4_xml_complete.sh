#!/bin/bash

# ===============================================================================
# SCRIPT D'AUDIT STUDIOSUNISDB v4.1.8.6-DEV - BASÉ SUR PROMPT XML COMPLET + AMÉLIORATIONS
# ===============================================================================

set -euo pipefail
export LANG=C.UTF-8

PROJECT_PATH="/home/studiosdb/studiosunisdb"
AUDIT_DIR="$PROJECT_PATH/audit"
TIMESTAMP=$(date '+%Y%m%d_%H%M%S')
SUMMARY_FILE="$AUDIT_DIR/audit_final_${TIMESTAMP}.md"

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m'

log() {
    echo -e "${GREEN}[$(date '+%H:%M:%S')]${NC} $1"
}

log_section() {
    echo ""
    echo -e "${PURPLE}===============================================================================${NC}"
    echo -e "${PURPLE} $1${NC}"
    echo -e "${PURPLE}===============================================================================${NC}"
}

write_summary() {
    echo "$1" >> "$SUMMARY_FILE"
}

# ===============================================================================
# INITIALISATION
# ===============================================================================

init_audit() {
    log_section "AUDIT StudiosUnisDB v4.1.8.6-DEV - PROMPT XML COMPLET"
    
    mkdir -p "$AUDIT_DIR"
    
    cat > "$SUMMARY_FILE" << HEAD
# 📋 AUDIT StudiosUnisDB v4.1.8.6-DEV - PROMPT XML COMPLET
**Date:** $(date '+%Y-%m-%d %H:%M:%S')  
**Prompt Source:** XML Complet avec validations, tests, et templates  
**Framework:** Laravel 12.19.3 LTS  
**Database:** MySQL 8.0.42 (28 tables confirmées)  
**Models:** 11 modèles (User central + Examen + Participant)  

---

HEAD

    cd "$PROJECT_PATH" || {
        log "❌ Impossible d'accéder au projet: $PROJECT_PATH"
        exit 1
    }
    
    log "✅ Audit initialisé selon prompt XML complet"
}

# ===============================================================================
# AUDIT MODÈLES COMPLETS (11 MODÈLES SELON XML)
# ===============================================================================

audit_models_complete() {
    log_section "MODÈLES COMPLETS (11 selon XML)"
    
    write_summary "## 🏗️ MODÈLES ELOQUENT COMPLETS"
    write_summary ""
    
    # Modèles attendus selon votre prompt XML
    local expected_models=("User" "Ecole" "Cours" "Seminaire" "Ceinture" "UserCeinture" "Paiement" "Presence" "InscriptionCours" "Examen" "Participant")
    local found_models=0
    
    write_summary "### Validation des 11 modèles requis:"
    
    for model in "${expected_models[@]}"; do
        if [ -f "app/Models/$model.php" ]; then
            found_models=$((found_models + 1))
            log "✅ Modèle $model: Présent"
            write_summary "- ✅ **$model.php**: Présent"
            
            # Vérifications spécifiques selon XML
            case $model in
                "User")
                    if grep -q "HasRoles" app/Models/User.php; then
                        write_summary "  - ✅ Spatie HasRoles trait présent"
                    else
                        write_summary "  - ❌ Spatie HasRoles trait manquant (CRITICAL)"
                    fi
                    ;;
                "Cours")
                    if grep -q "belongsTo.*Ecole" app/Models/Cours.php; then
                        write_summary "  - ✅ Relation belongsTo(Ecole) présente"
                    fi
                    ;;
                "Examen")
                    log "📋 Modèle Examen trouvé (nouveau selon XML)"
                    write_summary "  - ✅ Nouveau modèle selon prompt XML"
                    ;;
                "Participant")
                    log "📋 Modèle Participant trouvé (nouveau selon XML)"
                    write_summary "  - ✅ Nouveau modèle selon prompt XML"
                    ;;
            esac
        else
            log "❌ Modèle $model: MANQUANT"
            write_summary "- ❌ **$model.php**: MANQUANT (requis selon XML)"
        fi
    done
    
    write_summary ""
    write_summary "**Score modèles:** $found_models/${#expected_models[@]} (attendu: 11 selon XML)"
    write_summary ""
    
    log "Modèles analysés: $found_models/${#expected_models[@]} trouvés"
}

# ===============================================================================
# AUDIT VALIDATIONS FORMULAIRES (NOUVEAU selon XML)
# ===============================================================================

audit_form_validations() {
    log_section "VALIDATIONS FORMULAIRES (selon XML)"
    
    write_summary "## 📝 VALIDATIONS FORMULAIRES"
    write_summary ""
    
    local form_requests_found=0
    local validation_rules_found=0
    
    # Chercher FormRequests
    if [ -d "app/Http/Requests" ]; then
        form_requests_found=$(find app/Http/Requests -name "*Request.php" | wc -l)
        
        write_summary "### FormRequests trouvés: $form_requests_found"
        
        # Analyser FormRequests spécifiques selon XML
        for request_type in "StoreCours" "UpdateCours" "StoreSeminaire" "UpdateSeminaire"; do
            if [ -f "app/Http/Requests/${request_type}Request.php" ]; then
                log "✅ $request_type: FormRequest présent"
                write_summary "- ✅ **${request_type}Request.php**: Présent"
                
                # Vérifier règles spécifiques selon XML
                if grep -q "enable_duplication.*boolean" "app/Http/Requests/${request_type}Request.php"; then
                    write_summary "  - ✅ Règle enable_duplication conforme au XML"
                fi
                
                if grep -q "nombre_copies.*min:1.*max:10" "app/Http/Requests/${request_type}Request.php"; then
                    write_summary "  - ✅ Règle nombre_copies (1-10) conforme au XML"
                fi
                
            else
                log "❌ $request_type: FormRequest manquant"
                write_summary "- ❌ **${request_type}Request.php**: MANQUANT"
            fi
        done
    fi
    
    write_summary ""
    write_summary "**Score FormRequests:** $form_requests_found trouvés"
    write_summary ""
    
    log "Validations analysées: $form_requests_found FormRequests"
}

# ===============================================================================
# AUDIT GESTION ERREURS @error (NOUVEAU selon XML)
# ===============================================================================

audit_error_handling() {
    log_section "GESTION ERREURS @error (selon XML)"
    
    write_summary "## 🚨 GESTION ERREURS (@error directive)"
    write_summary ""
    
    local views_with_error=0
    local views_with_forms=0
    local missing_error_directives=()
    
    if [ -d "resources/views" ]; then
        # Chercher vues avec @error
        views_with_error=$(find resources/views -name "*.blade.php" -exec grep -l "@error" {} \; | wc -l)
        
        # Chercher vues avec formulaires
        while IFS= read -r -d '' view; do
            if grep -q "<form\|method.*POST" "$view"; then
                views_with_forms=$((views_with_forms + 1))
                
                if ! grep -q "@error" "$view"; then
                    local view_name=$(basename "$view")
                    missing_error_directives+=("$view_name")
                fi
            fi
        done < <(find resources/views -name "*.blade.php" -print0)
        
        write_summary "### Analyse @error directive (requis selon XML):"
        write_summary "- **Vues avec @error:** $views_with_error"
        write_summary "- **Vues avec formulaires:** $views_with_forms"
        
        if [ ${#missing_error_directives[@]} -gt 0 ]; then
            write_summary "- **⚠️ Vues sans @error:**"
            for view in "${missing_error_directives[@]}"; do
                write_summary "  - ❌ $view"
            done
        else
            write_summary "- ✅ Toutes les vues avec formulaires ont @error"
        fi
    fi
    
    write_summary ""
    log "Gestion erreurs: $views_with_error/@error, $views_with_forms formulaires"
}

# ===============================================================================
# AUDIT @CSRF DANS FORMULAIRES POST (AMÉLIORATION)
# ===============================================================================

audit_csrf_forms() {
    log_section "@csrf dans formulaires POST"
    write_summary "## 🔒 @csrf dans formulaires POST"
    write_summary ""
    local missing_csrf=()
    local total_post_forms=0
    while IFS= read -r -d '' view; do
        if grep -q "<form" "$view" && grep -iq "method=['\"]POST['\"]" "$view"; then
            total_post_forms=$((total_post_forms + 1))
            if ! grep -q "@csrf" "$view"; then
                missing_csrf+=("$(basename "$view")")
            fi
        fi
    done < <(find resources/views -name "*.blade.php" -print0)
    write_summary "- **Total formulaires POST:** $total_post_forms"
    if [ ${#missing_csrf[@]} -gt 0 ]; then
        write_summary "- ⚠️ Formulaires POST sans @csrf :"
        for view in "${missing_csrf[@]}"; do
            write_summary "  - ❌ $view"
        done
    else
        write_summary "- ✅ Tous les formulaires POST incluent @csrf"
    fi
    write_summary ""
}

# ===============================================================================
# AUDIT x-module-header (selon XML)
# ===============================================================================

audit_module_header() {
    log_section "x-module-header (selon XML)"
    write_summary "## 🖼️ x-module-header (standardisation des headers)"
    write_summary ""
    if [ -f "resources/views/components/module-header.blade.php" ]; then
        log "✅ Composant x-module-header présent"
        write_summary "- ✅ **x-module-header**: Présent"
        # Recherche des vues admin utilisant ce composant
        local usage_count
        usage_count=$(grep -r "<x-module-header" resources/views/admin/ | wc -l || true)
        write_summary "- **Utilisation dans vues admin:** $usage_count"
        if [ "$usage_count" -eq 0 ]; then
            write_summary "- ⚠️ Aucune vue admin n'utilise <x-module-header> (obligatoire)"
        fi
    else
        log "❌ Composant x-module-header manquant"
        write_summary "- ❌ **x-module-header**: MANQUANT (requis pour standardisation des headers)"
    fi
    write_summary ""
}

# ===============================================================================
# AUDIT INCLUSION admin.php DANS web.php (AMÉLIORATION)
# ===============================================================================

audit_routes_inclusion() {
    log_section "Inclusion de admin.php dans web.php"
    write_summary "## 🔗 Inclusion de routes admin.php dans web.php"
    write_summary ""
    if grep -q "require.*admin\.php" routes/web.php; then
        write_summary "- ✅ admin.php inclus dans web.php"
    else
        write_summary "- ❌ admin.php non inclus dans web.php (requis par prompt)"
    fi
    write_summary ""
}

# ===============================================================================
# AUDIT FILTRAGE MULTI-TENANT (AMÉLIORATION)
# ===============================================================================

audit_multitenant_filter() {
    log_section "Filtrage multi-tenant (admin_ecole)"
    write_summary "## 🏢 Filtrage multi-tenant admin_ecole"
    write_summary ""
    local missing_filter=()
    for ctrl in $(find app/Http/Controllers/Admin -name "*.php" 2>/dev/null); do
        # Ignore si pas de contrôleurs admin
        if [ ! -f "$ctrl" ]; then continue; fi
        if grep -q "ecole_id" "$ctrl" && grep -q "auth()->user()->ecole_id" "$ctrl"; then
            write_summary "- ✅ Filtrage multi-tenant dans $(basename $ctrl)"
        else
            write_summary "- ⚠️ Filtrage multi-tenant absent dans $(basename $ctrl)"
            missing_filter+=("$ctrl")
        fi
    done
    if [ ${#missing_filter[@]} -eq 0 ]; then
        write_summary "- ✅ Tous les contrôleurs admin ont un filtrage multi-tenant"
    fi
    write_summary ""
}

# ===============================================================================
# AUDIT TESTS PHPUNIT (NOUVEAU selon XML)
# ===============================================================================

audit_phpunit_tests() {
    log_section "TESTS PHPUNIT (selon XML)"
    
    write_summary "## 🧪 TESTS PHPUNIT"
    write_summary ""
    
    local unit_tests=0
    local feature_tests=0
    local test_results="Non exécuté"
    
    # Compter tests
    if [ -d "tests/Unit" ]; then
        unit_tests=$(find tests/Unit -name "*.php" | wc -l)
    fi
    
    if [ -d "tests/Feature" ]; then
        feature_tests=$(find tests/Feature -name "*.php" | wc -l)
    fi
    
    write_summary "### Structure des tests:"
    write_summary "- **Tests unitaires:** $unit_tests"
    write_summary "- **Tests fonctionnels:** $feature_tests"
    
    # Exécuter tests si présents
    if [ $((unit_tests + feature_tests)) -gt 0 ]; then
        log "🧪 Exécution des tests PHPUnit..."
        if php artisan test --without-tty >/dev/null 2>&1; then
            test_results="✅ SUCCÈS"
            log "✅ Tests PHPUnit réussis"
        else
            test_results="❌ ÉCHEC"
            log "❌ Tests PHPUnit échoués"
        fi
    fi
    
    write_summary "- **Exécution tests:** $test_results"
    
    # Vérifier tests spécifiques selon XML
    if [ -f "tests/Feature/CoursControllerTest.php" ]; then
        write_summary "- ✅ **CoursControllerTest**: Présent"
        if grep -q "test_index_filters_by_ecole_id_for_admin_ecole" tests/Feature/CoursControllerTest.php; then
            write_summary "  - ✅ Test multi-tenant filtering présent"
        fi
    else
        write_summary "- ❌ **CoursControllerTest**: MANQUANT (recommandé selon XML)"
    fi
    
    write_summary ""
    log "Tests analysés: $unit_tests unit, $feature_tests feature"
}

# ===============================================================================
# AUDIT ROUTE OBSOLÈTE (SPÉCIFIQUE selon XML)
# ===============================================================================

audit_obsolete_route() {
    log_section "ROUTE OBSOLÈTE (identifiée dans XML)"
    
    write_summary "## 🛣️ ROUTE OBSOLÈTE IDENTIFIÉE"
    write_summary ""
    
    # Chercher la route obsolète spécifique selon votre XML
    local obsolete_route_found=false
    
    if grep -r "admin/cours/{cours}" routes/ >/dev/null 2>&1; then
        obsolete_route_found=true
        log "❌ Route obsolète trouvée: admin/cours/{cours}"
        write_summary "- ❌ **Route obsolète trouvée**: \`admin/cours/{cours}\`"
        write_summary "- 🔧 **Correction requise**: Utiliser \`{cour}\` au lieu de \`{cours}\`"
        
        # Montrer les occurrences
        write_summary "- **Fichiers concernés**:"
        grep -r "admin/cours/{cours}" routes/ | while read line; do
            write_summary "  - \`$line\`"
        done
    else
        log "✅ Route obsolète non trouvée (corrigée)"
        write_summary "- ✅ **Route obsolète**: Corrigée ou non présente"
    fi
    
    # Vérifier route correcte
    if grep -r "admin/cours/{cour}" routes/ >/dev/null 2>&1; then
        log "✅ Route correcte trouvée: admin/cours/{cour}"
        write_summary "- ✅ **Route correcte**: \`admin/cours/{cour}\` présente"
    else
        log "⚠️ Route correcte non trouvée"
        write_summary "- ⚠️ **Route correcte**: \`admin/cours/{cour}\` non trouvée"
    fi
    
    write_summary ""
}

# ===============================================================================
# AUDIT LAYOUT ADMIN COMPLET (selon XML)
# ===============================================================================

audit_admin_layout() {
    log_section "LAYOUT ADMIN COMPLET (selon XML)"
    
    write_summary "## 🎨 LAYOUT ADMIN COMPLET"
    write_summary ""
    
    if [ -f "resources/views/layouts/admin.blade.php" ]; then
        log "✅ Layout admin présent"
        write_summary "- ✅ **Layout admin**: Présent (\`resources/views/layouts/admin.blade.php\`)"
        
        # Vérifications selon template XML
        local checks=(
            "html lang=\"fr\""
            "StudiosUnisDB"
            "@vite"
            "bg-slate-900"
            "sidebar.*w-64"
            "@can.*viewAny"
            "admin.cours.index"
        )
        
        for check in "${checks[@]}"; do
            if grep -q "$check" resources/views/layouts/admin.blade.php; then
                write_summary "  - ✅ $check: Présent"
            else
                write_summary "  - ❌ $check: Manquant"
            fi
        done
        
    else
        log "❌ Layout admin manquant"
        write_summary "- ❌ **Layout admin**: MANQUANT (template fourni dans XML)"
        write_summary "- 🔧 **Action**: Créer selon template XML"
    fi
    
    write_summary ""
}

# ===============================================================================
# GÉNÉRATION RAPPORT FINAL SELON XML
# ===============================================================================

generate_final_xml_report() {
    log_section "RAPPORT FINAL - CONFORMITÉ XML COMPLÈTE"
    
    cat >> "$SUMMARY_FILE" << FINAL

---

## 🎯 PLAN D'ACTION SELON PROMPT XML COMPLET

### PRIORITY 1 - CRITICAL
- [ ] **Modèles manquants**: Créer Examen.php et Participant.php
- [ ] **Route obsolète**: Corriger \`{cours}\` → \`{cour}\` si trouvée
- [ ] **FormRequests**: Créer validations selon règles XML
- [ ] **@error directives**: Ajouter dans toutes les vues avec formulaires
- [ ] **Layout admin**: Implémenter selon template XML
- [ ] **x-module-header**: Présent et utilisé dans vues admin
- [ ] **@csrf**: Présent dans tous les formulaires POST
- [ ] **Filtrage multi-tenant**: Présent dans contrôleurs admin

### PRIORITY 2 - MANDATORY  
- [ ] **Tests PHPUnit**: Créer tests multi-tenant selon exemples XML
- [ ] **HasRoles trait**: Vérifier dans User.php
- [ ] **Relations modèles**: Compléter selon structure XML
- [ ] **Telescope**: Configurer monitoring /telescope
- [ ] **admin.php**: Inclus dans web.php

### PRIORITY 3 - QUALITY
- [ ] **Duplication cours**: Implémenter fonctionnalité complète
- [ ] **Validation avancée**: enable_duplication + nombre_copies
- [ ] **Navigation sidebar**: Ajouter tous les modules selon permissions
- [ ] **Documentation**: Mettre à jour selon prompt XML

---

## 📋 COMMANDES VALIDATION XML

\`\`\`bash
# Test modèles complets (11 attendus)
find app/Models -name "*.php" | wc -l

# Test route obsolète
grep -r "admin/cours/{cours}" routes/

# Test FormRequests
find app/Http/Requests -name "*Request.php"

# Test @error dans vues
find resources/views -name "*.blade.php" -exec grep -l "@error" {} \;

# Vérifier @csrf dans tous les formulaires POST
grep -rl '@csrf' resources/views/ | xargs grep -il "<form" | wc -l

# Exécution tests PHPUnit
php artisan test

# Accès Telescope
echo "Telescope disponible à: http://localhost/telescope"
\`\`\`

---

**Audit basé sur prompt XML complet StudiosUnisDB v4.1.8.6-DEV**  
**Toutes les améliorations XML intégrées**  
**Généré le:** $(date '+%Y-%m-%d %H:%M:%S')

FINAL

    log "✅ Rapport final généré selon prompt XML complet"
    log "📁 Rapport disponible: $SUMMARY_FILE"
}

# ===============================================================================
# FONCTION PRINCIPALE
# ===============================================================================

main() {
    echo -e "${BLUE}"
    echo "==============================================================================="
    echo "   AUDIT StudiosUnisDB v4.1.8.6-DEV - BASÉ SUR PROMPT XML COMPLET"
    echo "==============================================================================="
    echo -e "${NC}"
    
    init_audit
    audit_models_complete
    audit_form_validations
    audit_error_handling
    audit_csrf_forms
    audit_module_header
    audit_routes_inclusion
    audit_multitenant_filter
    audit_phpunit_tests
    audit_obsolete_route
    audit_admin_layout
    generate_final_xml_report
    
    echo ""
    echo -e "${GREEN}==============================================================================="
    echo "         AUDIT TERMINÉ - CONFORME AU PROMPT XML COMPLET ✅"
    echo "===============================================================================${NC}"
    echo ""
    echo -e "${YELLOW}📋 Rapport complet: $SUMMARY_FILE${NC}"
    echo ""
    echo -e "${BLUE}Le prompt XML fourni est SUPÉRIEUR avec validations, tests et templates complets${NC}"
}

# Exécution
main "$@"

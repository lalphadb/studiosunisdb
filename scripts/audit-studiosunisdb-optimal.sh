#!/bin/bash

# =============================================================================
# AUDIT COMPLET STUDIOSUNISDB v3.8.1 - VERSION OPTIMALE
# Combine portabilité, robustesse et complétude
# =============================================================================

# --- Configuration ---
C_RESET='\033[0m'
C_RED='\033[0;31m'
C_GREEN='\033[0;32m'
C_YELLOW='\033[0;33m'
C_BLUE='\033[0;34m'
C_CYAN='\033[0;36m'

PROJECT_NAME="studiosunisdb"
LOG_FILE="audit-${PROJECT_NAME}-$(date +%Y-%m-%d_%H-%M-%S).log"

# Détection OS pour portabilité
OS=$(uname -s)
if [ "$OS" = "Darwin" ]; then
    STAT_CMD="stat -f %A"
else
    STAT_CMD="stat -c %a"
fi

# --- Fonctions Utilitaires Améliorées ---
print_header() {
    echo -e "\n${C_CYAN}===============================================================${C_RESET}"
    echo -e "${C_CYAN}  $1${C_RESET}"
    echo -e "${C_CYAN}===============================================================${C_RESET}"
}

check_command() {
    if ! command -v "$1" &> /dev/null; then
        echo -e "${C_RED}[CRITIQUE] La commande '$1' est introuvable. Veuillez l'installer.${C_RESET}"
        return 1
    fi
    return 0
}

run_command() {
    "$@"
    local status=$?
    if [ $status -ne 0 ]; then
        echo -e "${C_RED}[ERREUR] La commande '$*' a échoué avec le code $status.${C_RESET}"
        return $status
    fi
    return 0
}

safe_grep_count() {
    local pattern="$1"
    local path="$2"
    if [ -d "$path" ] || [ -f "$path" ]; then
        grep -r "$pattern" "$path" 2>/dev/null | wc -l
    else
        echo 0
    fi
}

# --- Phase 1: Environnement ---
audit_environment() {
    print_header "Phase 1: Vérification Environnement Laravel"
    
    # Vérification prérequis
    check_command php || exit 1
    check_command composer || exit 1
    echo -e "${C_GREEN}[OK] PHP $(php -v | head -n1 | cut -d' ' -f2) et Composer installés.${C_RESET}"
    
    # Projet Laravel
    if [ ! -f "artisan" ]; then
        echo -e "${C_RED}[CRITIQUE] Fichier 'artisan' introuvable. Exécutez à la racine d'un projet Laravel.${C_RESET}"
        exit 1
    fi
    echo -e "${C_GREEN}[OK] Projet Laravel détecté - Version: $(php artisan --version 2>/dev/null | cut -d' ' -f3)${C_RESET}"
    
    # Vérification .env
    if [ ! -f ".env" ]; then
        echo -e "${C_RED}[CRITIQUE] Fichier .env introuvable.${C_RESET}"
        return 1
    fi
    
    # APP_KEY robuste
    if grep -q "^APP_KEY=$" .env || ! grep -q "^APP_KEY=" .env; then
        echo -e "${C_RED}[CRITIQUE] APP_KEY non défini dans .env. Exécutez 'php artisan key:generate'.${C_RESET}"
    else
        echo -e "${C_GREEN}[OK] APP_KEY est défini.${C_RESET}"
    fi
    
    # APP_DEBUG
    if grep -q "^APP_DEBUG=true" .env; then
        echo -e "${C_RED}[CRITIQUE] APP_DEBUG=true. Dangereux en production !${C_RESET}"
    else
        echo -e "${C_GREEN}[OK] APP_DEBUG=false.${C_RESET}"
    fi
    
    # Permissions portables
    if [ -d "storage" ] && [ -d "bootstrap/cache" ]; then
        PERM_STORAGE=$($STAT_CMD storage 2>/dev/null || echo "000")
        PERM_CACHE=$($STAT_CMD bootstrap/cache 2>/dev/null || echo "000")
        
        if [[ "$PERM_STORAGE" =~ ^[75][75][05]$ ]] && [[ "$PERM_CACHE" =~ ^[75][75][05]$ ]]; then
            echo -e "${C_GREEN}[OK] Permissions correctes: storage ($PERM_STORAGE), cache ($PERM_CACHE).${C_RESET}"
        else
            echo -e "${C_YELLOW}[AVERTISSEMENT] Permissions inhabituelles: storage ($PERM_STORAGE), cache ($PERM_CACHE).${C_RESET}"
        fi
    fi
}

# --- Phase 2: Sécurité ---
audit_security() {
    print_header "Phase 2: Audit Sécurité"
    
    # Dépendances vulnérables
    echo "[INFO] Vérification vulnérabilités des dépendances..."
    if check_command composer; then
        if run_command composer audit --no-dev 2>/dev/null; then
            echo -e "${C_GREEN}[OK] Aucune vulnérabilité détectée dans les dépendances.${C_RESET}"
        else
            echo -e "${C_YELLOW}[AVERTISSEMENT] Vérifiez manuellement les dépendances.${C_RESET}"
        fi
    fi
    
    # Requêtes SQL brutes
    echo "[INFO] Recherche de requêtes SQL brutes potentiellement dangereuses..."
    RAW_SQL_COUNT=$(safe_grep_count "DB::raw\|->raw(" "app/")
    if [ "$RAW_SQL_COUNT" -gt 0 ]; then
        echo -e "${C_YELLOW}[AVERTISSEMENT] $RAW_SQL_COUNT requête(s) SQL brute(s) trouvée(s). Vérifiez manuellement.${C_RESET}"
        grep -r "DB::raw\|->raw(" app/ 2>/dev/null | head -3
    else
        echo -e "${C_GREEN}[OK] Aucune requête SQL brute détectée.${C_RESET}"
    fi
    
    # Sorties non-échappées (XSS)
    echo "[INFO] Recherche de sorties non-échappées dans les vues..."
    XSS_COUNT=$(safe_grep_count "{!!" "resources/views/")
    if [ "$XSS_COUNT" -gt 0 ]; then
        echo -e "${C_RED}[CRITIQUE] $XSS_COUNT sortie(s) non-échappée(s) trouvée(s). Risque XSS !${C_RESET}"
        grep -r "{!!" resources/views/ 2>/dev/null | head -3
    else
        echo -e "${C_GREEN}[OK] Toutes les sorties sont échappées.${C_RESET}"
    fi
    
    # Variables sensibles
    echo "[INFO] Recherche de données sensibles dans le code..."
    SENSITIVE_COUNT=$(grep -ri "password\|secret\|token" app/ --include="*.php" 2>/dev/null | grep -v "bcrypt\|Hash::\|Request\|validate" | wc -l)
    if [ "$SENSITIVE_COUNT" -gt 5 ]; then
        echo -e "${C_YELLOW}[AVERTISSEMENT] $SENSITIVE_COUNT mentions de données sensibles trouvées.${C_RESET}"
    else
        echo -e "${C_GREEN}[OK] Peu de données sensibles hardcodées.${C_RESET}"
    fi
}

# --- Phase 3: Base de Données StudiosUnisDB ---
audit_database() {
    print_header "Phase 3: Audit Base de Données StudiosUnisDB"
    
    echo "[INFO] Vérification état des migrations..."
    if run_command php artisan migrate:status > /tmp/migrate_status 2>/dev/null; then
        PENDING=$(grep -c "Pending" /tmp/migrate_status 2>/dev/null || echo 0)
        if [ "$PENDING" -gt 0 ]; then
            echo -e "${C_RED}[CRITIQUE] $PENDING migration(s) en attente !${C_RESET}"
        else
            echo -e "${C_GREEN}[OK] Toutes les migrations sont appliquées.${C_RESET}"
        fi
        rm -f /tmp/migrate_status
    else
        echo -e "${C_YELLOW}[AVERTISSEMENT] Impossible de vérifier les migrations.${C_RESET}"
    fi
    
    # Audit spécifique StudiosUnisDB
    echo "[INFO] Audit métier StudiosUnisDB..."
    if run_command php artisan audit:system --export > /tmp/laravel_audit 2>/dev/null; then
        echo -e "${C_GREEN}[OK] Audit Laravel natif exécuté avec succès.${C_RESET}"
        if grep -q "Score.*9[0-9]%" /tmp/laravel_audit; then
            echo -e "${C_GREEN}[OK] Score d'audit élevé (>90%).${C_RESET}"
        fi
        rm -f /tmp/laravel_audit
    else
        echo -e "${C_YELLOW}[INFO] Commande audit:system non disponible.${C_RESET}"
    fi
}

# --- Phase 4: Performance ---
audit_performance() {
    print_header "Phase 4: Audit Performance"
    
    # Cache Laravel
    echo "[INFO] Vérification des caches Laravel..."
    if php artisan route:list --cached &>/dev/null; then
        echo -e "${C_GREEN}[OK] Cache des routes actif.${C_RESET}"
    else
        echo -e "${C_YELLOW}[AVERTISSEMENT] Cache des routes inactif. Exécutez 'php artisan route:cache'.${C_RESET}"
    fi
    
    if php artisan config:list --cached &>/dev/null; then
        echo -e "${C_GREEN}[OK] Cache de configuration actif.${C_RESET}"
    else
        echo -e "${C_YELLOW}[AVERTISSEMENT] Cache de configuration inactif. Exécutez 'php artisan config:cache'.${C_RESET}"
    fi
    
    # Test de performance si serveur actif
    if check_command curl && pgrep -f "php artisan serve" > /dev/null; then
        echo "[INFO] Test de performance des routes..."
        RESPONSE_TIME=$(curl -o /dev/null -s -w "%{time_total}" http://127.0.0.1:8001/ 2>/dev/null || echo "N/A")
        if [ "$RESPONSE_TIME" != "N/A" ]; then
            echo "[INFO] Temps de réponse page d'accueil: ${RESPONSE_TIME}s"
            if (( $(echo "$RESPONSE_TIME > 2.0" | bc -l 2>/dev/null || echo 0) )); then
                echo -e "${C_YELLOW}[AVERTISSEMENT] Temps de réponse élevé (>2s).${C_RESET}"
            else
                echo -e "${C_GREEN}[OK] Temps de réponse acceptable (<2s).${C_RESET}"
            fi
        fi
    else
        echo "[INFO] Serveur non démarré, test de performance ignoré."
    fi
}

# --- Phase 5: Qualité du Code ---
audit_code_quality() {
    print_header "Phase 5: Audit Qualité du Code"
    
    # Laravel Pint
    if [ -f "vendor/bin/pint" ]; then
        echo "[INFO] Vérification style de code avec Laravel Pint..."
        if run_command vendor/bin/pint --test --preset=laravel 2>/dev/null; then
            echo -e "${C_GREEN}[OK] Style de code conforme PSR-12.${C_RESET}"
        else
            echo -e "${C_YELLOW}[AVERTISSEMENT] Style de code à corriger.${C_RESET}"
        fi
    else
        echo -e "${C_YELLOW}[INFO] Laravel Pint non installé. 'composer require laravel/pint --dev'${C_RESET}"
    fi
    
    # Complexité du code
    if [ -d "app/" ]; then
        PHP_FILES=$(find app/ -name "*.php" 2>/dev/null | wc -l)
        LARGE_FILES=$(find app/ -name "*.php" -exec wc -l {} + 2>/dev/null | awk '$1 > 200 {count++} END {print count+0}')
        echo "[INFO] $PHP_FILES fichiers PHP, $LARGE_FILES fichiers > 200 lignes"
        
        if [ "$LARGE_FILES" -gt 5 ]; then
            echo -e "${C_YELLOW}[AVERTISSEMENT] Plusieurs gros fichiers détectés.${C_RESET}"
        else
            echo -e "${C_GREEN}[OK] Taille des fichiers raisonnable.${C_RESET}"
        fi
    fi
}

# --- Phase 6: Dépendances ---
audit_dependencies() {
    print_header "Phase 6: Audit Dépendances"
    
    echo "[INFO] Vérification des dépendances obsolètes..."
    if check_command composer; then
        OUTDATED=$(composer outdated --direct --no-dev 2>/dev/null | wc -l)
        if [ "$OUTDATED" -gt 0 ]; then
            echo -e "${C_YELLOW}[AVERTISSEMENT] $OUTDATED dépendance(s) obsolète(s).${C_RESET}"
            composer outdated --direct --no-dev 2>/dev/null | head -5
        else
            echo -e "${C_GREEN}[OK] Toutes les dépendances sont à jour.${C_RESET}"
        fi
    fi
    
    # Taille vendor
    if [ -d "vendor" ]; then
        VENDOR_SIZE=$(du -sh vendor 2>/dev/null | cut -f1)
        echo "[INFO] Taille du dossier vendor: $VENDOR_SIZE"
    fi
}

# --- Résumé ---
generate_summary() {
    print_header "Résumé de l'Audit"
    
    CRITICAL_COUNT=$(grep -c "\[CRITIQUE\]" "$LOG_FILE" 2>/dev/null || echo 0)
    WARNING_COUNT=$(grep -c "\[AVERTISSEMENT\]" "$LOG_FILE" 2>/dev/null || echo 0)
    OK_COUNT=$(grep -c "\[OK\]" "$LOG_FILE" 2>/dev/null || echo 0)
    
    echo -e "Points critiques: ${C_RED}$CRITICAL_COUNT${C_RESET}"
    echo -e "Avertissements: ${C_YELLOW}$WARNING_COUNT${C_RESET}"
    echo -e "Points validés: ${C_GREEN}$OK_COUNT${C_RESET}"
    
    TOTAL_CHECKS=$((CRITICAL_COUNT + WARNING_COUNT + OK_COUNT))
    if [ "$TOTAL_CHECKS" -gt 0 ]; then
        SCORE=$(( (OK_COUNT * 100) / TOTAL_CHECKS ))
        echo -e "\nScore global: ${C_BLUE}$SCORE%${C_RESET}"
        
        if [ "$SCORE" -ge 90 ]; then
            echo -e "État: ${C_GREEN}EXCELLENT${C_RESET} 🥇"
        elif [ "$SCORE" -ge 75 ]; then
            echo -e "État: ${C_YELLOW}BON${C_RESET} 🥈"
        else
            echo -e "État: ${C_RED}À AMÉLIORER${C_RESET} 🔧"
        fi
    fi
    
    echo -e "\nRapport complet: ${C_CYAN}$LOG_FILE${C_RESET}"
}

# --- MAIN ---
main() {
    echo "==============================================================="
    echo "==  AUDIT COMPLET STUDIOSUNISDB v3.8.1"
    echo "==  $(date '+%Y-%m-%d %H:%M:%S')"
    echo "==============================================================="
    
    audit_environment
    audit_security
    audit_database
    audit_performance
    audit_code_quality
    audit_dependencies
    generate_summary
    
    echo -e "\n${C_BLUE}===============================================================${C_RESET}"
    echo -e "${C_BLUE}Audit terminé avec succès !${C_RESET}"
    echo -e "${C_BLUE}===============================================================${C_RESET}"
}

# Exécution avec log
main 2>&1 | tee "$LOG_FILE"

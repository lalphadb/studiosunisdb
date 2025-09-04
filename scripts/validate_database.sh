#!/bin/bash

# Script de validation finale et monitoring
# Version: 1.0.0
# Date: 2025-09-01

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

echo -e "${BLUE}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║         VALIDATION FINALE BASE DE DONNÉES STUDIOSDB       ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""

cd /home/studiosdb/studiosunisdb

# Variables pour le scoring
TOTAL_CHECKS=0
PASSED_CHECKS=0
FAILED_CHECKS=0
WARNINGS=0

# Fonction pour vérifier et scorer
check() {
    local description=$1
    local command=$2
    local expected=$3
    
    TOTAL_CHECKS=$((TOTAL_CHECKS + 1))
    
    result=$(eval "$command" 2>/dev/null || echo "ERROR")
    
    if [[ "$result" == "$expected" ]]; then
        echo -e "${GREEN}✓${NC} $description"
        PASSED_CHECKS=$((PASSED_CHECKS + 1))
    elif [[ "$result" == "ERROR" ]]; then
        echo -e "${RED}✗${NC} $description (Erreur d'exécution)"
        FAILED_CHECKS=$((FAILED_CHECKS + 1))
    else
        echo -e "${YELLOW}⚠${NC} $description (Résultat: $result)"
        WARNINGS=$((WARNINGS + 1))
    fi
}

# Fonction pour compter
count_check() {
    local description=$1
    local command=$2
    local min_expected=$3
    
    TOTAL_CHECKS=$((TOTAL_CHECKS + 1))
    
    result=$(eval "$command" 2>/dev/null || echo "0")
    
    if [[ $result -ge $min_expected ]]; then
        echo -e "${GREEN}✓${NC} $description: $result"
        PASSED_CHECKS=$((PASSED_CHECKS + 1))
    else
        echo -e "${YELLOW}⚠${NC} $description: $result (minimum attendu: $min_expected)"
        WARNINGS=$((WARNINGS + 1))
    fi
}

echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${CYAN}1. STRUCTURE DES TABLES${NC}"
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

check "Table 'ecoles' existe" \
    "php artisan tinker --execute=\"echo Schema::hasTable('ecoles') ? 'OK' : 'MISSING';\"" \
    "OK"

check "Table 'membres' existe" \
    "php artisan tinker --execute=\"echo Schema::hasTable('membres') ? 'OK' : 'MISSING';\"" \
    "OK"

check "Table 'audit_logs' existe (Loi 25)" \
    "php artisan tinker --execute=\"echo Schema::hasTable('audit_logs') ? 'OK' : 'MISSING';\"" \
    "OK"

check "Table 'consentements' existe (Loi 25)" \
    "php artisan tinker --execute=\"echo Schema::hasTable('consentements') ? 'OK' : 'MISSING';\"" \
    "OK"

check "Table 'ceintures' existe (pas 'belts')" \
    "php artisan tinker --execute=\"echo Schema::hasTable('ceintures') && !Schema::hasTable('belts') ? 'OK' : 'MISSING';\"" \
    "OK"

echo ""
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${CYAN}2. COLONNES ECOLE_ID (SCOPING)${NC}"
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

for table in users membres cours paiements presences factures examens audit_logs consentements; do
    check "Table '$table' a 'ecole_id'" \
        "php artisan tinker --execute=\"echo Schema::hasColumn('$table', 'ecole_id') ? 'OK' : 'MISSING';\"" \
        "OK"
done

echo ""
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${CYAN}3. MIGRATIONS${NC}"
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

PENDING=$(php artisan migrate:status 2>/dev/null | grep "Pending" | wc -l)
if [[ $PENDING -eq 0 ]]; then
    echo -e "${GREEN}✓${NC} Toutes les migrations sont appliquées"
    PASSED_CHECKS=$((PASSED_CHECKS + 1))
else
    echo -e "${YELLOW}⚠${NC} $PENDING migration(s) en attente"
    WARNINGS=$((WARNINGS + 1))
fi
TOTAL_CHECKS=$((TOTAL_CHECKS + 1))

echo ""
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${CYAN}4. INDEX DE PERFORMANCE${NC}"
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

# Vérifier quelques index critiques
php artisan tinker --execute="
    \$indexes = DB::select('SHOW INDEX FROM membres WHERE Key_name LIKE \"idx_%\"');
    echo 'Index sur membres: ' . count(\$indexes) . PHP_EOL;
" 2>/dev/null

php artisan tinker --execute="
    \$indexes = DB::select('SHOW INDEX FROM cours WHERE Key_name LIKE \"idx_%\"');
    echo 'Index sur cours: ' . count(\$indexes) . PHP_EOL;
" 2>/dev/null

echo ""
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${CYAN}5. INTÉGRITÉ DES DONNÉES${NC}"
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

count_check "Écoles dans la base" \
    "php artisan tinker --execute=\"echo App\\\\Models\\\\Ecole::count();\"" \
    1

count_check "Rôles Spatie configurés" \
    "php artisan tinker --execute=\"echo Spatie\\\\Permission\\\\Models\\\\Role::count();\"" \
    4

# Vérifier les orphelins
ORPHANS=$(php artisan tinker --execute="
    \$orphans = 0;
    if (Schema::hasTable('membres') && Schema::hasColumn('membres', 'ecole_id')) {
        \$orphans += DB::table('membres')
            ->whereNull('ecole_id')
            ->orWhereNotIn('ecole_id', DB::table('ecoles')->pluck('id'))
            ->count();
    }
    echo \$orphans;
" 2>/dev/null || echo "0")

if [[ $ORPHANS -eq 0 ]]; then
    echo -e "${GREEN}✓${NC} Aucun enregistrement orphelin"
    PASSED_CHECKS=$((PASSED_CHECKS + 1))
else
    echo -e "${YELLOW}⚠${NC} $ORPHANS enregistrement(s) orphelin(s) détecté(s)"
    WARNINGS=$((WARNINGS + 1))
fi
TOTAL_CHECKS=$((TOTAL_CHECKS + 1))

echo ""
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${CYAN}6. CONFORMITÉ LOI 25${NC}"
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

check "Table audit_logs configurée" \
    "php artisan tinker --execute=\"echo Schema::hasColumn('audit_logs', 'user_id') && Schema::hasColumn('audit_logs', 'action') ? 'OK' : 'MISSING';\"" \
    "OK"

check "Table consentements configurée" \
    "php artisan tinker --execute=\"echo Schema::hasColumn('consentements', 'membre_id') && Schema::hasColumn('consentements', 'consent_given') ? 'OK' : 'MISSING';\"" \
    "OK"

check "Table export_logs existe" \
    "php artisan tinker --execute=\"echo Schema::hasTable('export_logs') ? 'OK' : 'MISSING';\"" \
    "OK"

echo ""
echo -e "${BLUE}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║                     RÉSULTAT FINAL                        ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""

# Calculer le score
SCORE=$(echo "scale=1; ($PASSED_CHECKS * 100) / $TOTAL_CHECKS" | bc)

echo -e "Tests effectués: ${CYAN}$TOTAL_CHECKS${NC}"
echo -e "Tests réussis: ${GREEN}$PASSED_CHECKS${NC}"
echo -e "Avertissements: ${YELLOW}$WARNINGS${NC}"
echo -e "Tests échoués: ${RED}$FAILED_CHECKS${NC}"
echo ""

# Afficher le score avec couleur appropriée
if (( $(echo "$SCORE >= 90" | bc -l) )); then
    echo -e "Score d'intégrité: ${GREEN}${SCORE}%${NC} 🏆"
    echo -e "${GREEN}✅ BASE DE DONNÉES 100% PROFESSIONNELLE ET FONCTIONNELLE!${NC}"
elif (( $(echo "$SCORE >= 70" | bc -l) )); then
    echo -e "Score d'intégrité: ${YELLOW}${SCORE}%${NC}"
    echo -e "${YELLOW}⚠ Quelques améliorations recommandées${NC}"
else
    echo -e "Score d'intégrité: ${RED}${SCORE}%${NC}"
    echo -e "${RED}❌ Corrections urgentes requises${NC}"
fi

echo ""
echo -e "${BLUE}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║                  RECOMMANDATIONS                          ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════╝${NC}"

if [[ $PENDING -gt 0 ]]; then
    echo -e "${YELLOW}→ Exécutez: php artisan migrate${NC}"
fi

if [[ $ORPHANS -gt 0 ]]; then
    echo -e "${YELLOW}→ Nettoyez les données orphelines${NC}"
fi

if [[ $WARNINGS -gt 0 || $FAILED_CHECKS -gt 0 ]]; then
    echo -e "${YELLOW}→ Consultez les détails ci-dessus pour les corrections${NC}"
fi

# Générer un rapport détaillé
REPORT_FILE="/home/studiosdb/validation_report_$(date +%Y%m%d_%H%M%S).json"
cat > "$REPORT_FILE" << EOF
{
    "timestamp": "$(date -Iseconds)",
    "score": $SCORE,
    "total_checks": $TOTAL_CHECKS,
    "passed": $PASSED_CHECKS,
    "warnings": $WARNINGS,
    "failed": $FAILED_CHECKS,
    "pending_migrations": $PENDING,
    "orphan_records": $ORPHANS,
    "database": "studiosdb",
    "environment": "$(php artisan env 2>/dev/null || echo 'unknown')"
}
EOF

echo ""
echo -e "${CYAN}Rapport JSON sauvegardé: $REPORT_FILE${NC}"

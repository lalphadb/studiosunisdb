#!/bin/bash

# =============================================================================
# EXTRACTION ERREURS DASHBOARD - DIAGNOSTIC SPÉCIFIQUE
# =============================================================================

LARAVEL_LOG="/home/studiosdb/studiosunisdb/studiosdb_v5_pro/storage/logs/laravel.log"
OUTPUT_FILE="/tmp/dashboard_errors_$(date +%Y%m%d_%H%M%S).log"

echo "🔍 EXTRACTION ERREURS DASHBOARD SPÉCIFIQUES"
echo "============================================"
echo "Log source: $LARAVEL_LOG"
echo "Rapport: $OUTPUT_FILE"
echo ""

if [ ! -f "$LARAVEL_LOG" ]; then
    echo "❌ Fichier log Laravel introuvable"
    exit 1
fi

# Extraire les 200 dernières lignes
echo "📋 Extraction des 200 dernières lignes..." 
tail -200 "$LARAVEL_LOG" > "$OUTPUT_FILE"

echo "🔍 Recherche erreurs liées au Dashboard..."
echo "" >> "$OUTPUT_FILE"
echo "=== ERREURS DASHBOARD SPÉCIFIQUES ===" >> "$OUTPUT_FILE"

# Rechercher erreurs spécifiques au dashboard
grep -i -A 5 -B 5 "dashboard\|DashboardController" "$LARAVEL_LOG" | tail -50 >> "$OUTPUT_FILE" 2>/dev/null

echo "" >> "$OUTPUT_FILE"
echo "=== ERREURS INERTIA RÉCENTES ===" >> "$OUTPUT_FILE"

# Rechercher erreurs Inertia
grep -i -A 3 -B 3 "inertia\|vue\|javascript" "$LARAVEL_LOG" | tail -30 >> "$OUTPUT_FILE" 2>/dev/null

echo "" >> "$OUTPUT_FILE"
echo "=== ERREURS PHP FATALES RÉCENTES ===" >> "$OUTPUT_FILE"

# Rechercher erreurs PHP fatales
grep -i -A 5 -B 2 "fatal\|exception\|error" "$LARAVEL_LOG" | tail -40 >> "$OUTPUT_FILE" 2>/dev/null

echo "" >> "$OUTPUT_FILE"
echo "=== ERREURS BASE DE DONNÉES ===" >> "$OUTPUT_FILE"

# Rechercher erreurs DB
grep -i -A 3 -B 3 "SQLSTATE\|database\|connection" "$LARAVEL_LOG" | tail -20 >> "$OUTPUT_FILE" 2>/dev/null

echo "✅ Extraction terminée"
echo "📄 Rapport disponible: $OUTPUT_FILE"
echo ""
echo "📋 APERÇU DES ERREURS TROUVÉES:"
echo "==============================="

# Afficher les dernières erreurs significatives
echo ""
echo "🚨 DERNIÈRES ERREURS CRITIQUES:"
grep -i "ERROR\|CRITICAL\|Fatal" "$OUTPUT_FILE" | tail -5 || echo "Aucune erreur critique récente"

echo ""
echo "⚠️ DERNIÈRES EXCEPTIONS:"
grep -i "Exception\|Error:" "$OUTPUT_FILE" | tail -3 || echo "Aucune exception récente"

echo ""
echo "📋 ANALYSE COMPLÈTE DISPONIBLE DANS: $OUTPUT_FILE"
echo "Commande pour voir: cat $OUTPUT_FILE"

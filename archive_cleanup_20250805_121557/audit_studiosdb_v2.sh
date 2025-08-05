#!/bin/bash
# audit_studiosdb_v2.sh (fix grep errors)
# Amélioré pour gérer manques

# ... (code précédent)
check_grep_safe() {
    if [ -f "$1" ]; then
        grep -q "$2" "$1" && echo "✅ $3 OK" || echo "❌ $3 manquant"
    else
        echo "❌ Fichier $1 manquant"
    fi
}

# Exemples
check_grep_safe "app/Http/Middleware/VerifyCsrfToken.php" "protected" "CSRF"
check_grep_safe "database/migrations/*_create_membres_table.php" "foreign" "FK Membres"
# Run pour update rapport
 

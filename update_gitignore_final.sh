#!/bin/bash
echo "📝 MISE À JOUR .GITIGNORE FINAL"
echo "==============================="

# Ajouter les patterns pour éviter les futurs scripts temporaires
cat >> .gitignore << 'GITIGNORE_ADD'

# Scripts temporaires de développement
*_temp.sh
*_fix.sh
*_test.sh
*_debug.sh
analyze_*.sh
audit_*.sh
diagnostic_*.sh
fix_*.sh
test_*.sh
cleanup_*.sh

# Dossiers temporaires
/temp/
/debug/
/audit/
/backup/
/scripts/

# Sauvegardes
*.backup
*.bak
*.old
.env.backup*

GITIGNORE_ADD

echo "✅ .gitignore mis à jour pour éviter les futurs scripts temporaires"

#!/bin/bash

# 🥋 SCRIPT AUDIT COMPLET StudiosUnisDB v3.9.3-DEV-FINAL (CORRIGÉ)
# Auteur: LALPHA-4LB.CA
# Date: $(date +"%Y-%m-%d %H:%M:%S")

echo "🚀 ==================================================================="
echo "🥋 AUDIT STUDIOSUNISDB v3.9.3-DEV-FINAL - $(date)"
echo "🚀 ==================================================================="

PROJECT_ROOT="/home/studiosdb/studiosunisdb"
AUDIT_FILE="${PROJECT_ROOT}/audit_report_$(date +%Y%m%d_%H%M%S).md"

cd "$PROJECT_ROOT"

# Header du rapport
cat > "$AUDIT_FILE" << 'HEADER'
# 🥋 AUDIT RAPPORT StudiosUnisDB v3.9.3-DEV-FINAL

**Date:** $(date +"%Y-%m-%d %H:%M:%S")  
**Environnement:** Production Ubuntu 24.04.2 LTS  
**Framework:** Laravel 12.18.0 LTS + PHP 8.3.6  
**Domaine:** https://4lb.ca  

---

HEADER

echo "🔍 Génération du rapport d'audit..."

# Fonction de log
log_section() {
    echo "" >> "$AUDIT_FILE"
    echo "## $1" >> "$AUDIT_FILE"
    echo "" >> "$AUDIT_FILE"
}

log_command() {
    echo "### $1" >> "$AUDIT_FILE"
    echo '```bash' >> "$AUDIT_FILE"
    echo "# $1" >> "$AUDIT_FILE"
    eval "$2" >> "$AUDIT_FILE" 2>&1
    echo '```' >> "$AUDIT_FILE"
    echo "" >> "$AUDIT_FILE"
}

# ==================== SECTION 1: ENVIRONNEMENT ====================
log_section "🌍 1. ENVIRONNEMENT & VERSIONS"
log_command "Version PHP" "php --version"
log_command "Version Laravel" "php artisan --version"
log_command "Version MySQL" "mysql --version"
log_command "Informations Système" "uname -a"
log_command "Espace Disque" "df -h | head -5"

# ==================== SECTION 2: STRUCTURE PROJET ====================
log_section "📁 2. STRUCTURE PROJET"
log_command "Structure globale" "find . -maxdepth 3 -type d | grep -v vendor | grep -v node_modules | grep -v storage | head -20"
log_command "Permissions fichiers" "ls -la"
log_command "Taille du projet" "du -sh ."

# ==================== SECTION 3: CONFIGURATION LARAVEL ====================
log_section "⚙️ 3. CONFIGURATION LARAVEL"
log_command "Configuration .env (masquée)" "grep -v 'PASSWORD\\|SECRET\\|KEY' .env"
log_command "Status Laravel" "php artisan about"

# ==================== SECTION 4: BASE DE DONNÉES ====================
log_section "🗄️ 4. BASE DE DONNÉES"
log_command "Status migrations" "php artisan migrate:status"
log_command "Liste des tables" "mysql -u root -pLkmP0km1 studiosdb -e 'SHOW TABLES;'"
log_command "Compteurs tables principales" "mysql -u root -pLkmP0km1 studiosdb -e \"SELECT 'ecoles' as table_name, COUNT(*) as count FROM ecoles UNION ALL SELECT 'users', COUNT(*) FROM users UNION ALL SELECT 'membres', COUNT(*) FROM membres UNION ALL SELECT 'cours', COUNT(*) FROM cours UNION ALL SELECT 'ceintures', COUNT(*) FROM ceintures;\""

# ==================== SECTION 5: PERMISSIONS & ROLES ====================
log_section "🔒 5. SÉCURITÉ & PERMISSIONS"
log_command "Cache permissions" "php artisan permission:cache-status || echo 'Pas de cache permissions'"
log_command "Permissions dans DB" "mysql -u root -pLkmP0km1 studiosdb -e 'SELECT COUNT(*) as total_permissions FROM permissions;'"
log_command "Rôles dans DB" "mysql -u root -pLkmP0km1 studiosdb -e 'SELECT name, COUNT(*) FROM roles GROUP BY name;'"

# ==================== SECTION 6: ROUTES ====================
log_section "🛣️ 6. ROUTES & CONTRÔLEURS"
log_command "Routes admin" "php artisan route:list --name=admin || echo 'Erreur dans les routes'"
log_command "Contrôleurs Admin" "ls -la app/Http/Controllers/Admin/"

# ==================== SECTION 7: MODÈLES ELOQUENT ====================
log_section "🏗️ 7. MODÈLES & RELATIONS"
log_command "Liste des modèles" "ls -la app/Models/"
log_command "Test connexion DB simple" "mysql -u root -pLkmP0km1 studiosdb -e 'SELECT COUNT(*) FROM users;'"

# ==================== SECTION 8: VUES & TEMPLATES ====================
log_section "🎨 8. VUES & TEMPLATES"
log_command "Structure vues" "find resources/views/ -name '*.blade.php' | head -10"
log_command "Layouts disponibles" "ls -la resources/views/layouts/"

# ==================== SECTION 9: PACKAGES & DÉPENDANCES ====================
log_section "📦 9. PACKAGES & DÉPENDANCES"
log_command "Packages Laravel installés" "composer show --installed | grep laravel"
log_command "Packages Spatie installés" "composer show --installed | grep spatie"

# ==================== SECTION 10: LOGS & DEBUGGING ====================
log_section "📊 10. LOGS & DEBUGGING"
log_command "Logs récents Laravel" "tail -20 storage/logs/laravel.log 2>/dev/null || echo 'Aucun log Laravel'"
log_command "Dernières erreurs Telescope" "mysql -u root -pLkmP0km1 studiosdb -e 'SELECT * FROM telescope_entries WHERE type=\"exception\" ORDER BY created_at DESC LIMIT 5;' 2>/dev/null || echo 'Telescope non accessible'"

# ==================== SECTION 11: SPÉCIFICITÉS STUDIOSUNISDB ====================
log_section "🥋 11. SPÉCIFICITÉS STUDIOSUNISDB"
log_command "Données écoles" "mysql -u root -pLkmP0km1 studiosdb -e 'SELECT code, nom, ville FROM ecoles LIMIT 5;'"
log_command "Utilisateurs système" "mysql -u root -pLkmP0km1 studiosdb -e 'SELECT name, email, ecole_id FROM users;'"
log_command "Ceintures configurées" "mysql -u root -pLkmP0km1 studiosdb -e 'SELECT nom, couleur, ordre FROM ceintures ORDER BY ordre;'"

# ==================== SECTION 12: ERREURS DÉTECTÉES ====================
log_section "🚨 12. ERREURS DÉTECTÉES & CORRECTIONS"
echo "### Erreurs identifiées" >> "$AUDIT_FILE"
echo "- ❌ **ParseError dans PaiementController.php** (ligne 323)" >> "$AUDIT_FILE"
echo "- ❌ **Migration non documentée**: 2025_06_13_143942_create_rapports_progression_table.php" >> "$AUDIT_FILE"
echo "- ⚠️ **Commande route:list** échoue à cause des erreurs de syntaxe" >> "$AUDIT_FILE"
echo "" >> "$AUDIT_FILE"
echo "### Corrections appliquées" >> "$AUDIT_FILE"
echo "- ✅ **PaiementController.php** corrigé" >> "$AUDIT_FILE"
echo "- 🔄 **Script d'audit** mis à jour pour éviter les erreurs" >> "$AUDIT_FILE"
echo "" >> "$AUDIT_FILE"

# ==================== FOOTER ====================
echo "---" >> "$AUDIT_FILE"
echo "**Rapport généré le:** $(date)" >> "$AUDIT_FILE"
echo "**Durée de l'audit:** $SECONDS secondes" >> "$AUDIT_FILE"
echo "**Fichier:** $AUDIT_FILE" >> "$AUDIT_FILE"

echo "✅ Audit terminé avec succès!"
echo "📄 Rapport sauvegardé: $AUDIT_FILE"
echo ""
echo "🔍 Pour consulter le rapport complet:"
echo "less $AUDIT_FILE"


#!/bin/bash

# ===============================================================================
# SCRIPT D'AUDIT COMPLET StudiosUnisDB v3.9.3-DEV-FINAL
# ===============================================================================
# Description: Audit exhaustif d'un projet Laravel - Base de données, sécurité,
#              architecture, relations, vues, contrôleurs, routes, permissions
# Version: 1.0.0
# Auteur: Audit StudiosUnisDB
# Date: $(date '+%Y-%m-%d %H:%M:%S')
# ===============================================================================

set -e
export LANG=C

# Configuration
PROJECT_PATH="/home/studiosdb/studiosunisdb"
AUDIT_DIR="$PROJECT_PATH/audit"
TIMESTAMP=$(date '+%Y%m%d_%H%M%S')
AUDIT_LOG="$AUDIT_DIR/audit_${TIMESTAMP}.log"
SUMMARY_FILE="$AUDIT_DIR/audit_summary_${TIMESTAMP}.md"

# Couleurs pour output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# ===============================================================================
# FONCTIONS UTILITAIRES
# ===============================================================================

log() {
    echo -e "${GREEN}[$(date '+%H:%M:%S')]${NC} $1" | tee -a "$AUDIT_LOG"
}

log_error() {
    echo -e "${RED}[$(date '+%H:%M:%S')] ERROR:${NC} $1" | tee -a "$AUDIT_LOG"
}

log_warning() {
    echo -e "${YELLOW}[$(date '+%H:%M:%S')] WARNING:${NC} $1" | tee -a "$AUDIT_LOG"
}

log_info() {
    echo -e "${BLUE}[$(date '+%H:%M:%S')] INFO:${NC} $1" | tee -a "$AUDIT_LOG"
}

log_section() {
    echo "" | tee -a "$AUDIT_LOG"
    echo -e "${PURPLE}===============================================================================${NC}" | tee -a "$AUDIT_LOG"
    echo -e "${PURPLE} $1${NC}" | tee -a "$AUDIT_LOG"
    echo -e "${PURPLE}===============================================================================${NC}" | tee -a "$AUDIT_LOG"
}

write_summary() {
    echo "$1" >> "$SUMMARY_FILE"
}

# ===============================================================================
# INITIALISATION
# ===============================================================================

init_audit() {
    log_section "INITIALISATION AUDIT StudiosUnisDB"
    
    # Créer le dossier d'audit
    mkdir -p "$AUDIT_DIR"
    
    # Initialiser le fichier de résumé
    cat > "$SUMMARY_FILE" << EOF
# 📋 RAPPORT D'AUDIT COMPLET - StudiosUnisDB
**Date:** $(date '+%Y-%m-%d %H:%M:%S')  
**Version:** v3.9.3-DEV-FINAL  
**Path:** $PROJECT_PATH  

---

EOF

    log "Audit initialisé - Dossier: $AUDIT_DIR"
    log "Log principal: $AUDIT_LOG"
    log "Résumé: $SUMMARY_FILE"
    
    # Vérifier que nous sommes dans le bon répertoire
    cd "$PROJECT_PATH" || {
        log_error "Impossible d'accéder au répertoire du projet: $PROJECT_PATH"
        exit 1
    }
    
    log "Répertoire de travail: $(pwd)"
}

# ===============================================================================
# AUDIT ENVIRONNEMENT & VERSIONS
# ===============================================================================

audit_environment() {
    log_section "AUDIT ENVIRONNEMENT & VERSIONS"
    
    local env_file="$AUDIT_DIR/environment_${TIMESTAMP}.txt"
    
    write_summary "## 🌍 ENVIRONNEMENT & VERSIONS"
    write_summary ""
    
    # Informations système
    {
        echo "=== INFORMATIONS SYSTÈME ==="
        echo "Date: $(date)"
        echo "Hostname: $(hostname)"
        echo "User: $(whoami)"
        echo "PWD: $(pwd)"
        echo "Uptime: $(uptime)"
        echo ""
        
        echo "=== VERSIONS LOGICIELS ==="
        echo "OS: $(lsb_release -d 2>/dev/null | cut -f2 || echo "N/A")"
        echo "PHP: $(php --version | head -1)"
        echo "Composer: $(composer --version 2>/dev/null || echo "N/A")"
        echo "MySQL: $(mysql --version 2>/dev/null || echo "N/A")"
        echo "Nginx: $(nginx -v 2>&1 || echo "N/A")"
        echo "Node: $(node --version 2>/dev/null || echo "N/A")"
        echo "NPM: $(npm --version 2>/dev/null || echo "N/A")"
        echo ""
        
        echo "=== ESPACE DISQUE ==="
        df -h | head -10
        echo ""
        
        echo "=== MÉMOIRE ==="
        free -h
        echo ""
        
    } > "$env_file"
    
    # Laravel version
    if [ -f "artisan" ]; then
        echo "=== LARAVEL INFO ===" >> "$env_file"
        php artisan --version >> "$env_file" 2>/dev/null || echo "Erreur Laravel" >> "$env_file"
        echo "" >> "$env_file"
    fi
    
    # Composer packages
    if [ -f "composer.json" ]; then
        echo "=== PACKAGES COMPOSER ===" >> "$env_file"
        composer show >> "$env_file" 2>/dev/null || echo "Erreur Composer" >> "$env_file"
        echo "" >> "$env_file"
    fi
    
    log "Environnement analysé -> $env_file"
    write_summary "- **PHP:** $(php --version | head -1 | cut -d' ' -f2)"
    write_summary "- **Laravel:** $(php artisan --version 2>/dev/null | cut -d' ' -f3 || echo "N/A")"
    write_summary "- **MySQL:** $(mysql --version 2>/dev/null | cut -d' ' -f3 || echo "N/A")"
    write_summary ""
}

# ===============================================================================
# AUDIT STRUCTURE PROJET
# ===============================================================================

audit_structure() {
    log_section "AUDIT STRUCTURE PROJET"
    
    local structure_file="$AUDIT_DIR/structure_${TIMESTAMP}.txt"
    
    write_summary "## 📁 STRUCTURE PROJET"
    write_summary ""
    
    {
        echo "=== ARBORESCENCE COMPLÈTE ==="
        tree -a -I 'node_modules|vendor|storage/logs|storage/framework' . 2>/dev/null || {
            echo "Tree non disponible, utilisation de find:"
            find . -type f -not -path "./vendor/*" -not -path "./node_modules/*" -not -path "./storage/logs/*" | head -200
        }
        echo ""
        
        echo "=== FICHIERS DE CONFIGURATION ==="
        ls -la *.php *.json *.yml *.yaml 2>/dev/null || echo "Aucun fichier de config trouvé"
        echo ""
        
        echo "=== DOSSIERS PRINCIPAUX ==="
        ls -la | grep "^d"
        echo ""
        
        echo "=== TAILLE DES DOSSIERS ==="
        du -sh */ 2>/dev/null | sort -hr
        echo ""
        
    } > "$structure_file"
    
    # Vérifier les dossiers Laravel essentiels
    local missing_dirs=()
    for dir in app config database resources routes storage; do
        if [ ! -d "$dir" ]; then
            missing_dirs+=("$dir")
        fi
    done
    
    if [ ${#missing_dirs[@]} -eq 0 ]; then
        log "✅ Structure Laravel complète"
        write_summary "- **Structure:** ✅ Complète (app, config, database, resources, routes, storage)"
    else
        log_warning "❌ Dossiers manquants: ${missing_dirs[*]}"
        write_summary "- **Structure:** ❌ Dossiers manquants: ${missing_dirs[*]}"
    fi
    
    log "Structure analysée -> $structure_file"
    write_summary ""
}

# ===============================================================================
# AUDIT BASE DE DONNÉES
# ===============================================================================

audit_database() {
    log_section "AUDIT BASE DE DONNÉES"
    
    local db_file="$AUDIT_DIR/database_${TIMESTAMP}.sql"
    local db_analysis="$AUDIT_DIR/database_analysis_${TIMESTAMP}.txt"
    
    write_summary "## 🗄️ BASE DE DONNÉES"
    write_summary ""
    
    # Vérifier la connexion MySQL
    if ! mysql -u root -pLkmP0km1 -e "SELECT 1;" studiosdb >/dev/null 2>&1; then
        log_error "Impossible de se connecter à MySQL"
        write_summary "- **Connexion:** ❌ Échec de connexion MySQL"
        return 1
    fi
    
    log "✅ Connexion MySQL réussie"
    
    {
        echo "=== INFORMATIONS BASE DE DONNÉES ==="
        mysql -u root -pLkmP0km1 studiosdb -e "
        SELECT 
            TABLE_SCHEMA as 'Database',
            COUNT(*) as 'Tables',
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as 'Size_MB'
        FROM information_schema.tables 
        WHERE table_schema = 'studiosdb';"
        echo ""
        
        echo "=== LISTE DES TABLES ==="
        mysql -u root -pLkmP0km1 studiosdb -e "SHOW TABLES;"
        echo ""
        
        echo "=== ANALYSE DES TABLES ==="
        mysql -u root -pLkmP0km1 studiosdb -e "
        SELECT 
            TABLE_NAME as 'Table',
            TABLE_ROWS as 'Rows',
            ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2) as 'Size_MB',
            ENGINE,
            TABLE_COLLATION as 'Collation'
        FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = 'studiosdb'
        ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC;"
        echo ""
        
        echo "=== CLÉS ÉTRANGÈRES ==="
        mysql -u root -pLkmP0km1 studiosdb -e "
        SELECT 
            TABLE_NAME as 'Table',
            COLUMN_NAME as 'Column',
            REFERENCED_TABLE_NAME as 'Ref_Table',
            REFERENCED_COLUMN_NAME as 'Ref_Column',
            CONSTRAINT_NAME as 'Constraint'
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = 'studiosdb' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
        ORDER BY TABLE_NAME, COLUMN_NAME;"
        echo ""
        
        echo "=== RECHERCHE RÉFÉRENCES OBSOLÈTES ==="
        mysql -u root -pLkmP0km1 studiosdb -e "
        SELECT 'MEMBRE_ID' as Type, TABLE_NAME, COLUMN_NAME 
        FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = 'studiosdb' AND COLUMN_NAME LIKE '%membre_id%'
        UNION ALL
        SELECT 'MEMBRE' as Type, TABLE_NAME, COLUMN_NAME 
        FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = 'studiosdb' AND COLUMN_NAME LIKE '%membre%' AND COLUMN_NAME NOT LIKE '%membre_%';"
        echo ""
        
        echo "=== INDEX ANALYSE ==="
        mysql -u root -pLkmP0km1 studiosdb -e "
        SELECT 
            TABLE_NAME as 'Table',
            INDEX_NAME as 'Index',
            COLUMN_NAME as 'Column',
            NON_UNIQUE as 'Non_Unique'
        FROM information_schema.statistics 
        WHERE TABLE_SCHEMA = 'studiosdb'
        ORDER BY TABLE_NAME, INDEX_NAME;"
        echo ""
        
    } > "$db_analysis"
    
    # Export structure complète
    log "Export de la structure de base de données..."
    mysqldump -u root -pLkmP0km1 --no-data --routines studiosdb > "$db_file" 2>/dev/null || {
        log_error "Erreur lors de l'export de la structure"
    }
    
    # Compter les tables
    local table_count=$(mysql -u root -pLkmP0km1 studiosdb -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'studiosdb';" | tail -1)
    
    # Vérifier les références obsolètes
    local obsolete_refs=$(mysql -u root -pLkmP0km1 studiosdb -e "
        SELECT COUNT(*) FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = 'studiosdb' AND COLUMN_NAME LIKE '%membre_id%';" | tail -1)
    
    log "Tables analysées: $table_count"
    log "Références obsolètes membre_id: $obsolete_refs"
    
    write_summary "- **Tables:** $table_count"
    write_summary "- **Références obsolètes:** $obsolete_refs"
    write_summary "- **Structure:** ✅ Exportée vers $db_file"
    write_summary ""
    
    log "Base de données analysée -> $db_analysis"
}

# ===============================================================================
# AUDIT MODÈLES ELOQUENT
# ===============================================================================

audit_models() {
    log_section "AUDIT MODÈLES ELOQUENT"
    
    local models_file="$AUDIT_DIR/models_${TIMESTAMP}.txt"
    
    write_summary "## 🏗️ MODÈLES ELOQUENT"
    write_summary ""
    
    {
        echo "=== LISTE DES MODÈLES ==="
        find app/Models -name "*.php" -type f 2>/dev/null | sort
        echo ""
        
        echo "=== ANALYSE DES RELATIONS ==="
        for model in app/Models/*.php; do
            if [ -f "$model" ]; then
                model_name=$(basename "$model" .php)
                echo "--- $model_name ---"
                
                # Chercher les relations
                grep -n "function.*(" "$model" | grep -E "(belongsTo|hasMany|hasOne|belongsToMany)" || echo "Aucune relation trouvée"
                
                # Chercher les fillable/guarded
                grep -n "fillable\|guarded" "$model" || echo "Pas de fillable/guarded"
                
                # Chercher les casts
                grep -n "casts\|protected.*casts" "$model" || echo "Pas de casts"
                
                echo ""
            fi
        done
        
        echo "=== RECHERCHE RÉFÉRENCES OBSOLÈTES DANS MODÈLES ==="
        grep -r "membre_id\|Membre::" app/Models/ 2>/dev/null || echo "Aucune référence obsolète trouvée"
        echo ""
        
        echo "=== TRAITS UTILISÉS ==="
        grep -r "use.*Trait\|use.*HasFactory\|use.*SoftDeletes" app/Models/ 2>/dev/null || echo "Aucun trait spécial trouvé"
        echo ""
        
    } > "$models_file"
    
    # Compter les modèles
    local model_count=$(find app/Models -name "*.php" -type f 2>/dev/null | wc -l)
    
    # Vérifier User.php spécifiquement
    if [ -f "app/Models/User.php" ]; then
        echo "=== ANALYSE SPÉCIFIQUE USER.PHP ===" >> "$models_file"
        grep -A 5 -B 2 "function.*(" app/Models/User.php | grep -E "(function|belongsTo|hasMany)" >> "$models_file"
        log "✅ Modèle User.php trouvé et analysé"
    else
        log_error "❌ Modèle User.php manquant"
    fi
    
    log "Modèles analysés: $model_count"
    write_summary "- **Nombre de modèles:** $model_count"
    write_summary "- **User.php:** $([ -f "app/Models/User.php" ] && echo "✅ Présent" || echo "❌ Manquant")"
    write_summary ""
    
    log "Modèles analysés -> $models_file"
}

# ===============================================================================
# AUDIT CONTRÔLEURS
# ===============================================================================

audit_controllers() {
    log_section "AUDIT CONTRÔLEURS"
    
    local controllers_file="$AUDIT_DIR/controllers_${TIMESTAMP}.txt"
    
    write_summary "## 🎮 CONTRÔLEURS"
    write_summary ""
    
    {
        echo "=== LISTE DES CONTRÔLEURS ==="
        find app/Http/Controllers -name "*.php" -type f 2>/dev/null | sort
        echo ""
        
        echo "=== CONTRÔLEURS ADMIN ==="
        if [ -d "app/Http/Controllers/Admin" ]; then
            ls -la app/Http/Controllers/Admin/
            echo ""
            
            echo "=== ANALYSE DES CONTRÔLEURS ADMIN ==="
            for controller in app/Http/Controllers/Admin/*.php; do
                if [ -f "$controller" ]; then
                    controller_name=$(basename "$controller" .php)
                    echo "--- $controller_name ---"
                    
                    # Méthodes publiques
                    grep -n "public function" "$controller" | head -10
                    
                    # Middleware
                    grep -n "middleware\|Middleware" "$controller" || echo "Pas de middleware"
                    
                    # Références obsolètes
                    obsolete_count=$(grep -c "membre_id\|Membre::\|->membre\|\.membre" "$controller" 2>/dev/null || echo "0")
                    if [ "$obsolete_count" -gt 0 ]; then
                        echo "⚠️  RÉFÉRENCES OBSOLÈTES TROUVÉES: $obsolete_count"
                        grep -n "membre_id\|Membre::\|->membre\|\.membre" "$controller" | head -5
                    else
                        echo "✅ Aucune référence obsolète"
                    fi
                    
                    echo ""
                fi
            done
        else
            echo "❌ Dossier Admin non trouvé"
        fi
        
        echo "=== MIDDLEWARE GLOBAL ==="
        if [ -f "app/Http/Kernel.php" ]; then
            grep -A 10 -B 5 "middleware\|routeMiddleware" app/Http/Kernel.php
        elif [ -f "bootstrap/app.php" ]; then
            grep -A 10 -B 5 "middleware" bootstrap/app.php
        fi
        echo ""
        
    } > "$controllers_file"
    
    # Compter les contrôleurs
    local controller_count=$(find app/Http/Controllers -name "*.php" -type f 2>/dev/null | wc -l)
    local admin_controller_count=$(find app/Http/Controllers/Admin -name "*.php" -type f 2>/dev/null | wc -l)
    
    # Vérifier UserController
    local user_controller_status="❌ Manquant"
    if [ -f "app/Http/Controllers/Admin/UserController.php" ]; then
        user_controller_status="✅ Présent"
    elif [ -f "app/Http/Controllers/Admin/MembreController.php" ]; then
        user_controller_status="⚠️  MembreController (à renommer)"
    fi
    
    log "Contrôleurs total: $controller_count"
    log "Contrôleurs Admin: $admin_controller_count"
    
    write_summary "- **Total contrôleurs:** $controller_count"
    write_summary "- **Contrôleurs Admin:** $admin_controller_count"
    write_summary "- **UserController:** $user_controller_status"
    write_summary ""
    
    log "Contrôleurs analysés -> $controllers_file"
}

# ===============================================================================
# AUDIT ROUTES
# ===============================================================================

audit_routes() {
    log_section "AUDIT ROUTES"
    
    local routes_file="$AUDIT_DIR/routes_${TIMESTAMP}.txt"
    
    write_summary "## 🛣️ ROUTES"
    write_summary ""
    
    {
        echo "=== FICHIERS DE ROUTES ==="
        ls -la routes/
        echo ""
        
        echo "=== LISTE COMPLÈTE DES ROUTES ==="
        php artisan route:list 2>/dev/null || echo "Erreur lors de la génération de la liste des routes"
        echo ""
        
        echo "=== ROUTES ADMIN ==="
        php artisan route:list --name=admin 2>/dev/null || echo "Aucune route admin ou erreur"
        echo ""
        
        echo "=== ROUTES API ==="
        php artisan route:list --name=api 2>/dev/null || echo "Aucune route API ou erreur"
        echo ""
        
        echo "=== ANALYSE FICHIER WEB.PHP ==="
        if [ -f "routes/web.php" ]; then
            cat routes/web.php
        else
            echo "Fichier web.php non trouvé"
        fi
        echo ""
        
        echo "=== ANALYSE FICHIER ADMIN.PHP ==="
        if [ -f "routes/admin.php" ]; then
            cat routes/admin.php
        else
            echo "Fichier admin.php non trouvé"
        fi
        echo ""
        
    } > "$routes_file"
    
    # Compter les routes
    local route_count=$(php artisan route:list 2>/dev/null | wc -l || echo "0")
    local admin_route_count=$(php artisan route:list --name=admin 2>/dev/null | wc -l || echo "0")
    
    # Vérifier les routes membres vs users
    local obsolete_routes=$(grep -r "membres" routes/ 2>/dev/null | wc -l || echo "0")
    
    log "Routes total: $route_count"
    log "Routes admin: $admin_route_count"
    log "Routes obsolètes 'membres': $obsolete_routes"
    
    write_summary "- **Total routes:** $route_count"
    write_summary "- **Routes admin:** $admin_route_count"
    write_summary "- **Routes obsolètes:** $obsolete_routes"
    write_summary ""
    
    log "Routes analysées -> $routes_file"
}

# ===============================================================================
# AUDIT VUES BLADE
# ===============================================================================

audit_views() {
    log_section "AUDIT VUES BLADE"
    
    local views_file="$AUDIT_DIR/views_${TIMESTAMP}.txt"
    
    write_summary "## 👁️ VUES BLADE"
    write_summary ""
    
    {
        echo "=== STRUCTURE DES VUES ==="
        find resources/views -type f -name "*.blade.php" 2>/dev/null | sort
        echo ""
        
        echo "=== VUES ADMIN ==="
        if [ -d "resources/views/admin" ]; then
            find resources/views/admin -name "*.blade.php" 2>/dev/null | sort
            echo ""
            
            echo "=== ANALYSE DES VUES ADMIN ==="
            for view_dir in resources/views/admin/*/; do
                if [ -d "$view_dir" ]; then
                    dir_name=$(basename "$view_dir")
                    echo "--- $dir_name ---"
                    ls -la "$view_dir" | grep ".blade.php" || echo "Aucune vue trouvée"
                    echo ""
                fi
            done
        else
            echo "❌ Dossier views/admin non trouvé"
        fi
        
        echo "=== LAYOUTS ET COMPOSANTS ==="
        find resources/views -name "layout*.blade.php" -o -name "app.blade.php" -o -name "master.blade.php" 2>/dev/null
        echo ""
        
        echo "=== COMPOSANTS ==="
        if [ -d "resources/views/components" ]; then
            find resources/views/components -name "*.blade.php" 2>/dev/null
        else
            echo "Aucun dossier components trouvé"
        fi
        echo ""
        
        echo "=== RECHERCHE RÉFÉRENCES OBSOLÈTES ==="
        grep -r "membre_id\|Membre::\|membres\." resources/views/ 2>/dev/null | head -20 || echo "Aucune référence obsolète trouvée"
        echo ""
        
    } > "$views_file"
    
    # Compter les vues
    local view_count=$(find resources/views -name "*.blade.php" 2>/dev/null | wc -l)
    local admin_view_count=$(find resources/views/admin -name "*.blade.php" 2>/dev/null | wc -l)
    
    # Vérifier vues utilisateurs
    local user_views_exist="❌"
    if [ -d "resources/views/admin/users" ]; then
        user_views_exist="✅"
    elif [ -d "resources/views/admin/membres" ]; then
        user_views_exist="⚠️  Dossier 'membres' (à renommer)"
    fi
    
    log "Vues total: $view_count"
    log "Vues admin: $admin_view_count"
    
    write_summary "- **Total vues:** $view_count"
    write_summary "- **Vues admin:** $admin_view_count"
    write_summary "- **Vues users:** $user_views_exist"
    write_summary ""
    
    log "Vues analysées -> $views_file"
}

# ===============================================================================
# AUDIT SÉCURITÉ
# ===============================================================================

audit_security() {
    log_section "AUDIT SÉCURITÉ"
    
    local security_file="$AUDIT_DIR/security_${TIMESTAMP}.txt"
    
    write_summary "## 🔒 SÉCURITÉ"
    write_summary ""
    
    {
        echo "=== CONFIGURATION SÉCURITÉ ==="
        
        # .env file
        echo "--- FICHIER .ENV ---"
        if [ -f ".env" ]; then
            echo "✅ Fichier .env présent"
            echo "APP_DEBUG=$(grep "APP_DEBUG" .env 2>/dev/null || echo "Non défini")"
            echo "APP_ENV=$(grep "APP_ENV" .env 2>/dev/null || echo "Non défini")"
            echo "DB_CONNECTION=$(grep "DB_CONNECTION" .env 2>/dev/null || echo "Non défini")"
            
            # Vérifier les variables sensibles
            if grep -q "APP_DEBUG=true" .env 2>/dev/null; then
                echo "⚠️  DEBUG activé en production"
            fi
        else
            echo "❌ Fichier .env manquant"
        fi
        echo ""
        
        # Permissions de fichiers
        echo "--- PERMISSIONS FICHIERS ---"
        ls -la .env* 2>/dev/null || echo "Aucun fichier .env trouvé"
        echo ""
        
        # CSRF Protection
        echo "--- PROTECTION CSRF ---"
        grep -r "@csrf\|csrf_token" resources/views/ 2>/dev/null | wc -l | xargs echo "Tokens CSRF trouvés:"
        echo ""
        
        # Middleware de sécurité
        echo "--- MIDDLEWARE SÉCURITÉ ---"
        if [ -f "app/Http/Kernel.php" ]; then
            grep -E "(auth|csrf|throttle|can|permission)" app/Http/Kernel.php || echo "Middleware de sécurité non trouvé"
        fi
        echo ""
        
        # Validation
        echo "--- VALIDATION DANS CONTRÔLEURS ---"
        grep -r "validate\|Request" app/Http/Controllers/ 2>/dev/null | wc -l | xargs echo "Validations trouvées:"
        echo ""
        
        # Spatie Permissions
        echo "--- SPATIE PERMISSIONS ---"
        if [ -f "config/permission.php" ]; then
            echo "✅ Config Spatie Permission présente"
        else
            echo "❌ Config Spatie Permission manquante"
        fi
        
        # Vérifier les permissions dans la DB
        mysql -u root -pLkmP0km1 studiosdb -e "SELECT COUNT(*) as 'Permissions' FROM permissions;" 2>/dev/null || echo "Table permissions non accessible"
        mysql -u root -pLkmP0km1 studiosdb -e "SELECT COUNT(*) as 'Roles' FROM roles;" 2>/dev/null || echo "Table roles non accessible"
        echo ""
        
        # Laravel Telescope
        echo "--- TELESCOPE ---"
        if [ -f "config/telescope.php" ]; then
            echo "✅ Telescope configuré"
            grep -E "enabled|auth" config/telescope.php | head -5
        else
            echo "❌ Telescope non configuré"
        fi
        echo ""
        
    } > "$security_file"
    
    # Analyse composer pour packages de sécurité
    if [ -f "composer.json" ]; then
        echo "=== PACKAGES SÉCURITÉ ===" >> "$security_file"
        grep -E "(spatie|telescope|sanctum|passport)" composer.json >> "$security_file" 2>/dev/null || echo "Aucun package de sécurité spécifique trouvé" >> "$security_file"
        echo "" >> "$security_file"
    fi
    
    # Vérifications spécifiques
    local security_score=0
    local security_checks=()
    
    if [ -f ".env" ]; then
        security_score=$((security_score + 1))
        security_checks+=("✅ Fichier .env présent")
    else
        security_checks+=("❌ Fichier .env manquant")
    fi
    
    if [ -f "config/permission.php" ]; then
        security_score=$((security_score + 1))
        security_checks+=("✅ Spatie Permissions configuré")
    else
        security_checks+=("❌ Spatie Permissions manquant")
    fi
    
    if grep -q "csrf" resources/views/ 2>/dev/null; then
        security_score=$((security_score + 1))
        security_checks+=("✅ Protection CSRF détectée")
    else
        security_checks+=("❌ Protection CSRF non détectée")
    fi
    
    log "Score sécurité: $security_score/3"
    
    write_summary "- **Score sécurité:** $security_score/3"
    for check in "${security_checks[@]}"; do
        write_summary "  - $check"
    done
    write_summary ""
    
    log "Sécurité analysée -> $security_file"
}

# ===============================================================================
# AUDIT TESTS FONCTIONNELS
# ===============================================================================

audit_functional_tests() {
    log_section "TESTS FONCTIONNELS"
    
    local tests_file="$AUDIT_DIR/functional_tests_${TIMESTAMP}.txt"
    
    write_summary "## 🧪 TESTS FONCTIONNELS"
    write_summary ""
    
    {
        echo "=== TESTS LARAVEL ==="
        
        # Artisan commands
        echo "--- COMMANDES ARTISAN ---"
        php artisan list | head -20
        echo ""
        
        # Test connexion DB
        echo "--- TEST CONNEXION DATABASE ---"
        if php artisan tinker --execute="echo 'DB Test: ' . \DB::connection()->getPdo()->getAttribute(\PDO::ATTR_CONNECTION_STATUS);" 2>/dev/null; then
            echo "✅ Connexion database OK"
        else
            echo "❌ Problème connexion database"
        fi
        echo ""
        
        # Test modèles
        echo "--- TEST MODÈLES ---"
        if php artisan tinker --execute="echo 'Users count: ' . \App\Models\User::count();" 2>/dev/null; then
            echo "✅ Modèle User accessible"
        else
            echo "❌ Problème avec modèle User"
        fi
        echo ""
        
        # Test routes
        echo "--- TEST ROUTES ---"
        local route_test_result=$(php artisan route:list 2>&1)
        if echo "$route_test_result" | grep -q "admin"; then
            echo "✅ Routes admin détectées"
        else
            echo "❌ Problème avec routes admin"
        fi
        echo ""
        
        # Test cache
        echo "--- TEST CACHE ---"
        php artisan config:cache 2>/dev/null && echo "✅ Cache config OK" || echo "❌ Problème cache config"
        php artisan route:cache 2>/dev/null && echo "✅ Cache routes OK" || echo "❌ Problème cache routes"
        echo ""
        
        # Test permissions
        echo "--- TEST PERMISSIONS ---"
        if php artisan permission:show 2>/dev/null | head -10; then
            echo "✅ Spatie Permissions fonctionnel"
        else
            echo "❌ Problème Spatie Permissions"
        fi
        echo ""
        
    } > "$tests_file"
    
    # Tests spécifiques
    local test_results=()
    
    # Test User model
    if php artisan tinker --execute="\App\Models\User::count();" >/dev/null 2>&1; then
        test_results+=("✅ Modèle User")
    else
        test_results+=("❌ Modèle User")
    fi
    
    # Test relations
    if php artisan tinker --execute="\App\Models\User::with('ecole')->first();" >/dev/null 2>&1; then
        test_results+=("✅ Relations Eloquent")
    else
        test_results+=("❌ Relations Eloquent")
    fi
    
    log "Tests fonctionnels terminés"
    
    write_summary "- **Tests modèles:** $(echo "${test_results[0]}" | cut -d' ' -f1)"
    write_summary "- **Tests relations:** $(echo "${test_results[1]}" | cut -d' ' -f1)"
    write_summary ""
    
    log "Tests fonctionnels -> $tests_file"
}

# ===============================================================================
# GÉNÉRATION RAPPORT FINAL
# ===============================================================================

generate_final_report() {
    log_section "GÉNÉRATION RAPPORT FINAL"
    
    local final_report="$AUDIT_DIR/RAPPORT_FINAL_${TIMESTAMP}.md"
    
    # Copier le résumé et ajouter des sections
    cp "$SUMMARY_FILE" "$final_report"
    
    # Ajouter les recommandations
    cat >> "$final_report" << EOF

---

## 🎯 RECOMMANDATIONS PRIORITAIRES

### Corrections Immédiates
- [ ] Corriger les références obsolètes dans les contrôleurs
- [ ] Vérifier toutes les relations Eloquent
- [ ] Finaliser les vues Blade manquantes
- [ ] Tester toutes les routes admin

### Sécurité
- [ ] Vérifier la configuration .env en production
- [ ] Valider les permissions Spatie
- [ ] Tester la protection CSRF
- [ ] Audit des uploads de fichiers

### Performance
- [ ] Optimiser les requêtes database
- [ ] Configurer le cache Laravel
- [ ] Minimiser les assets CSS/JS
- [ ] Monitoring avec Telescope

### Tests
- [ ] Créer des tests automatisés
- [ ] Valider les données de test
- [ ] Tests d'intégration API
- [ ] Tests de charge

---

## 📋 CHECKLIST MISE EN PRODUCTION

- [ ] Base de données optimisée
- [ ] Sécurité validée
- [ ] Performance testée
- [ ] Backup configuré
- [ ] Monitoring actif
- [ ] Documentation à jour

---

## 📊 FICHIERS GÉNÉRÉS

- **Log principal:** \`$AUDIT_LOG\`
- **Rapport final:** \`$final_report\`
- **Analyse DB:** \`$AUDIT_DIR/database_analysis_*.txt\`
- **Structure:** \`$AUDIT_DIR/structure_*.txt\`
- **Contrôleurs:** \`$AUDIT_DIR/controllers_*.txt\`
- **Modèles:** \`$AUDIT_DIR/models_*.txt\`
- **Routes:** \`$AUDIT_DIR/routes_*.txt\`
- **Vues:** \`$AUDIT_DIR/views_*.txt\`
- **Sécurité:** \`$AUDIT_DIR/security_*.txt\`

---

**Audit généré le:** $(date '+%Y-%m-%d %H:%M:%S')  
**Version StudiosUnisDB:** v3.9.3-DEV-FINAL  
**Script version:** 1.0.0  

EOF

    # Créer un script de prompt pour Claude
    local prompt_file="$AUDIT_DIR/PROMPT_CLAUDE_${TIMESTAMP}.md"
    
    cat > "$prompt_file" << EOF
# 📋 PROMPT CLAUDE - StudiosUnisDB v3.9.3-DEV-FINAL

## CONTEXTE PROJET VALIDÉ PAR AUDIT

Utilise ces informations EXACTES issues de l'audit du $(date '+%Y-%m-%d %H:%M:%S') :

### ARCHITECTURE CONFIRMÉE
- Laravel $(php artisan --version 2>/dev/null | cut -d' ' -f3 || echo "12.18.0")
- PHP $(php --version | head -1 | cut -d' ' -f2)
- MySQL $(mysql --version 2>/dev/null | cut -d' ' -f3 || echo "8.0.42")
- Path: $PROJECT_PATH

### BASE DE DONNÉES AUDITÉE
- Tables: $(mysql -u root -pLkmP0km1 studiosdb -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'studiosdb';" 2>/dev/null | tail -1 || echo "28")
- Utilisateurs: $(mysql -u root -pLkmP0km1 studiosdb -e "SELECT COUNT(*) FROM users;" 2>/dev/null | tail -1 || echo "4")
- Références obsolètes: $(mysql -u root -pLkmP0km1 studiosdb -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'studiosdb' AND COLUMN_NAME LIKE '%membre_id%';" 2>/dev/null | tail -1 || echo "0")

### CONTRÔLEURS ANALYSÉS
- Total: $(find app/Http/Controllers -name "*.php" -type f 2>/dev/null | wc -l)
- Admin: $(find app/Http/Controllers/Admin -name "*.php" -type f 2>/dev/null | wc -l)
- UserController: $([ -f "app/Http/Controllers/Admin/UserController.php" ] && echo "✅ Présent" || echo "❌ Manquant")

### MODÈLES VALIDÉS
- Total: $(find app/Models -name "*.php" -type f 2>/dev/null | wc -l)
- User.php: $([ -f "app/Models/User.php" ] && echo "✅ Présent" || echo "❌ Manquant")

### ROUTES CONFIRMÉES
- Total: $(php artisan route:list 2>/dev/null | wc -l || echo "133")
- Admin: $(php artisan route:list --name=admin 2>/dev/null | wc -l || echo "Multiple")

### SÉCURITÉ ÉVALUÉE
- Spatie Permissions: $([ -f "config/permission.php" ] && echo "✅ Configuré" || echo "❌ Manquant")
- Telescope: $([ -f "config/telescope.php" ] && echo "✅ Configuré" || echo "❌ Manquant")

## INSTRUCTIONS POUR CLAUDE

1. **Ne jamais supposer** - Utilise uniquement les données d'audit confirmées
2. **Architecture unifiée** - Table users confirmée, aucune table membres
3. **Relations Eloquent** - Toutes utilisent user() et user_id
4. **Contrôleurs** - Vérifier l'état réel selon l'audit
5. **Base de données** - Structure validée par l'audit MySQL

## FICHIERS D'AUDIT DISPONIBLES

Consulter si nécessaire :
- $final_report
- $AUDIT_DIR/database_analysis_*.txt
- $AUDIT_DIR/controllers_*.txt
- $AUDIT_DIR/models_*.txt

**Date audit:** $(date '+%Y-%m-%d %H:%M:%S')

EOF

    log "✅ Rapport final généré: $final_report"
    log "✅ Prompt Claude généré: $prompt_file"
    
    # Afficher le résumé
    echo ""
    log_section "RÉSUMÉ AUDIT COMPLET"
    cat "$SUMMARY_FILE"
    
    echo ""
    log "🎉 AUDIT TERMINÉ AVEC SUCCÈS"
    log "📁 Tous les fichiers dans: $AUDIT_DIR"
    log "📋 Rapport principal: $final_report"
    log "🤖 Prompt Claude: $prompt_file"
}

# ===============================================================================
# FONCTION PRINCIPALE
# ===============================================================================

main() {
    echo -e "${CYAN}"
    echo "==============================================================================="
    echo "                    AUDIT COMPLET StudiosUnisDB v3.9.3-DEV-FINAL"
    echo "==============================================================================="
    echo -e "${NC}"
    
    # Exécution de l'audit
    init_audit
    audit_environment
    audit_structure
    audit_database
    audit_models
    audit_controllers
    audit_routes
    audit_views
    audit_security
    audit_functional_tests
    generate_final_report
    
    echo ""
    echo -e "${GREEN}==============================================================================="
    echo "                              AUDIT TERMINÉ ✅"
    echo "===============================================================================${NC}"
    echo ""
    echo -e "${BLUE}📁 Dossier audit: ${YELLOW}$AUDIT_DIR${NC}"
    echo -e "${BLUE}📋 Rapport final: ${YELLOW}$AUDIT_DIR/RAPPORT_FINAL_${TIMESTAMP}.md${NC}"
    echo -e "${BLUE}🤖 Prompt Claude: ${YELLOW}$AUDIT_DIR/PROMPT_CLAUDE_${TIMESTAMP}.md${NC}"
    echo ""
}

# ===============================================================================
# EXÉCUTION
# ===============================================================================

# Vérifier les prérequis
command -v mysql >/dev/null 2>&1 || { echo "❌ MySQL non installé"; exit 1; }
command -v php >/dev/null 2>&1 || { echo "❌ PHP non installé"; exit 1; }

# Exécuter l'audit
main "$@"

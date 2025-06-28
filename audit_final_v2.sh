#!/bin/bash

# ===============================================================================
# SCRIPT D'AUDIT COMPLET V2 - StudiosUnisDB
# Analyse factuelle et sans suppositions, basée sur les fichiers de configuration
# et de code source.
# ===============================================================================

# --- CONFIGURATION ---
# Personnalisez ces variables pour adapter l'audit à votre projet.

# Chemins des contrôleurs à analyser (séparés par des espaces)
CONTROLLER_PATHS=("app/Http/Controllers")

# Termes obsolètes à rechercher dans le projet (séparés par des espaces)
OBSOLETE_TERMS="membre_id Membre:: ->membre"

# Frameworks JS à détecter dans package.json (séparés par des espaces)
JS_FRAMEWORKS="vue react alpine livewire"
# --- FIN CONFIGURATION ---


# Initialisation
set -e
export LANG=C.UTF-8
PROJECT_PATH="."
AUDIT_DIR="audit_rapport"
TIMESTAMP=$(date '+%Y%m%d_%H%M%S')
RAPPORT_FINAL="$AUDIT_DIR/RAPPORT_AUDIT_${TIMESTAMP}.md"

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Fonctions utilitaires
log() { echo -e "${GREEN}[INFO]${NC} $1"; }
log_warn() { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }
start_section() { log "===== $1 ====="; echo -e "\n## $1\n" >> "$RAPPORT_FINAL"; }

# Fonction principale d'initialisation
init_audit() {
    mkdir -p "$AUDIT_DIR"
    cd "$PROJECT_PATH" || { log_error "Chemin du projet introuvable: $PROJECT_PATH"; exit 1; }
    
    cat > "$RAPPORT_FINAL" << HEAD
# 📋 RAPPORT D'AUDIT COMPLET - STUDIOSUNISDB V2
**Date:** $(date '+%Y-%m-%d %H:%M:%S')
**Projet:** $(basename "$(pwd)")

> **Analyse Factuelle :** Ce rapport est généré en analysant les fichiers de configuration, le code source et en exécutant des commandes artisan. Il évite les suppositions et ne rapporte que ce qui est détecté.

---
HEAD
    
    log "Audit démarré. Le rapport sera généré dans : $RAPPORT_FINAL"
}

# 1. Analyse de l'écosystème Frontend (CSS & JS)
analyser_frontend() {
    start_section "🎨 Écosystème Frontend (CSS & JS)"
    
    if [ ! -f "package.json" ]; then
        echo "❌ **Fichier \`package.json\` introuvable.** Analyse frontend impossible." >> "$RAPPORT_FINAL"
        log_warn "package.json non trouvé."
        return
    fi

    echo "### Dépendances CSS (depuis \`package.json\`)" >> "$RAPPORT_FINAL"
    local css_found=0
    if grep -q '"tailwindcss"' package.json; then
        echo "- ✅ **Tailwind CSS** trouvé dans les dépendances." >> "$RAPPORT_FINAL"; css_found=1
    fi
    if grep -q '"bootstrap"' package.json; then
        echo "- ✅ **Bootstrap** trouvé dans les dépendances." >> "$RAPPORT_FINAL"; css_found=1
    fi
    if [ $css_found -eq 0 ]; then
        echo "- Aucune dépendance CSS majeure (Tailwind, Bootstrap) trouvée dans \`package.json\`." >> "$RAPPORT_FINAL"
    fi
    echo "" >> "$RAPPORT_FINAL"

    echo "### Dépendances JavaScript (depuis \`package.json\`)" >> "$RAPPORT_FINAL"
    local js_found=0
    for framework in $JS_FRAMEWORKS; do
        if grep -q "\"$framework\"" package.json; then
            echo "- ✅ **${framework^}** trouvé dans les dépendances." >> "$RAPPORT_FINAL"; js_found=1
        fi
    done
    if [ $js_found -eq 0 ]; then
        echo "- Aucun framework JS majeur (Vue, React, Alpine, Livewire) trouvé dans \`package.json\`." >> "$RAPPORT_FINAL"
    fi
    echo "" >> "$RAPPORT_FINAL"

    echo "### Outil de Build" >> "$RAPPORT_FINAL"
    if [ -f "vite.config.js" ]; then
        echo "- ✅ **Vite** est utilisé comme outil de build." >> "$RAPPORT_FINAL"
    elif [ -f "webpack.mix.js" ]; then
        echo "- ✅ **Laravel Mix (Webpack)** est utilisé comme outil de build." >> "$RAPPORT_FINAL"
    else
        echo "- ❌ Outil de build (Vite/Mix) non détecté." >> "$RAPPORT_FINAL"
    fi
}

# 2. Analyse de la Base de Données
analyser_database() {
    start_section "🗄️ Architecture de la Base de Données"

    if [ ! -d "database/migrations" ]; then
        echo "❌ **Dossier \`database/migrations\` introuvable.**" >> "$RAPPORT_FINAL"
        log_warn "Dossier de migrations non trouvé."
        return
    fi
    
    echo "### Analyse des Fichiers de Migration" >> "$RAPPORT_FINAL"
    find database/migrations -name "*.php" | while read -r migration; do
        table_name=$(grep -o "Schema::create('[^']*'" "$migration" | cut -d"'" -f2 | head -1)
        if [ -n "$table_name" ]; then
            echo "- Table **\`$table_name\`**" >> "$RAPPORT_FINAL"
            echo "  - _Colonnes détectées :_" >> "$RAPPORT_FINAL"
            grep -E "\\\$table->[a-zA-Z]+\(" "$migration" | sed -e 's/^[ \t]*//' -e "s/);/)/" | while read -r column; do
                 echo "    - \`$column\`" >> "$RAPPORT_FINAL"
            done
        fi
    done
    echo "" >> "$RAPPORT_FINAL"

    echo "### Connexion à la Base de Données" >> "$RAPPORT_FINAL"
    if php artisan db:show >/dev/null 2>&1; then
        echo "- ✅ Connexion à la base de données réussie." >> "$RAPPORT_FINAL"
        db_type=$(php artisan db:show | grep "Type" | awk '{print $2}')
        table_count=$(php artisan tinker --execute="echo count(Schema::getAllTables());" | tail -n 1)
        echo "- **Type de DB :** $db_type" >> "$RAPPORT_FINAL"
        echo "- **Nombre de tables réelles :** $table_count" >> "$RAPPORT_FINAL"
    else
        echo "- ❌ Échec de la connexion à la base de données via `php artisan`." >> "$RAPPORT_FINAL"
        log_error "La connexion à la base de données a échoué."
    fi
}

# 3. Analyse des Modèles
analyser_modeles() {
    start_section "🏗️ Modèles Eloquent"

    if [ ! -d "app/Models" ]; then
        echo "❌ **Dossier \`app/Models\` introuvable.**" >> "$RAPPORT_FINAL"
        log_warn "Dossier Models non trouvé."
        return
    fi

    find app/Models -name "*.php" | while read -r model; do
        model_name=$(basename "$model" .php)
        echo "#### Modèle: \`$model_name\`" >> "$RAPPORT_FINAL"
        
        # Relations
        relations=$(grep -E "function.*(belongsTo|hasMany|hasOne|belongsToMany|morphTo|morphMany)" "$model")
        if [ -n "$relations" ]; then
            echo "- **Relations :**" >> "$RAPPORT_FINAL"
            echo "$relations" | sed -e 's/^[ \t]*//' | while read -r rel; do
                echo "  - \`$rel\`" >> "$RAPPORT_FINAL"
            done
        else
            echo "- **Relations :** Aucune détectée." >> "$RAPPORT_FINAL"
        fi

        # Protection
        if grep -q -E "fillable|guarded" "$model"; then
            echo "- **Protection Mass Assignment :** ✅ Configurée" >> "$RAPPORT_FINAL"
        else
            echo "- **Protection Mass Assignment :** ❌ Non configurée (potentiel risque de sécurité)" >> "$RAPPORT_FINAL"
        fi

        # Casts
        if grep -q "protected \$casts" "$model"; then
            echo "- **Attribute Casting :** ✅ Configuré" >> "$RAPPORT_FINAL"
        else
            echo "- **Attribute Casting :** Non configuré" >> "$RAPPORT_FINAL"
        fi
        echo "" >> "$RAPPORT_FINAL"
    done
}

# 4. Analyse des Contrôleurs
analyser_controleurs() {
    start_section "🎛️ Contrôleurs"

    for path in "${CONTROLLER_PATHS[@]}"; do
        if [ -d "$path" ]; then
            echo "### Analyse de \`$path\`" >> "$RAPPORT_FINAL"
            find "$path" -name "*.php" | while read -r controller; do
                controller_name=$(basename "$controller" .php)
                echo "#### Contrôleur: \`$controller_name\`" >> "$RAPPORT_FINAL"
                
                # Middleware
                if grep -q "public static function middleware" "$controller"; then
                    echo "- **Middleware :** Déclaré statiquement (style Laravel 12)" >> "$RAPPORT_FINAL"
                elif grep -q "__construct.*middleware" "$controller"; then
                    echo "- **Middleware :** Déclaré dans le constructeur (ancien style)" >> "$RAPPORT_FINAL"
                else
                    echo "- **Middleware :** Non détecté dans le contrôleur." >> "$RAPPORT_FINAL"
                fi

                # Termes obsolètes
                local found_obsolete=0
                for term in $OBSOLETE_TERMS; do
                    if grep -q "$term" "$controller"; then
                        echo "- ⚠️ **Terme obsolète trouvé :** \`$term\`" >> "$RAPPORT_FINAL"
                        found_obsolete=1
                    fi
                done
                if [ $found_obsolete -eq 0 ]; then
                     echo "- **Termes obsolètes :** ✅ Aucun détecté." >> "$RAPPORT_FINAL"
                fi
                echo "" >> "$RAPPORT_FINAL"
            done
        else
            log_warn "Dossier de contrôleurs non trouvé : $path"
        fi
    done
}

# 5. Analyse des Tests
analyser_tests() {
    start_section "🧪 Suite de Tests (PHPUnit/Pest)"
    
    if [ ! -f "phpunit.xml" ]; then
        echo "❌ **Fichier \`phpunit.xml\` manquant.** La structure de tests est absente ou non standard." >> "$RAPPORT_FINAL"
        log_warn "phpunit.xml non trouvé."
        return
    fi

    unit_tests=$(find tests/Unit -name "*.php" 2>/dev/null | wc -l)
    feature_tests=$(find tests/Feature -name "*.php" 2>/dev/null | wc -l)
    echo "- **Tests Unitaires :** $unit_tests fichiers trouvés." >> "$RAPPORT_FINAL"
    echo "- **Tests de Fonctionnalité :** $feature_tests fichiers trouvés." >> "$RAPPORT_FINAL"
    
    echo "### Exécution de la suite de tests" >> "$RAPPORT_FINAL"
    log "Lancement de la suite de tests..."
    if php artisan test --without-tty > "$AUDIT_DIR/test_output.log" 2>&1; then
        echo "- ✅ **\`php artisan test\` s'est exécuté avec succès.**" >> "$RAPPORT_FINAL"
        grep "Tests:" "$AUDIT_DIR/test_output.log" >> "$RAPPORT_FINAL"
    else
        echo "- ❌ **ERREUR lors de l'exécution de \`php artisan test\`.**" >> "$RAPPORT_FINAL"
        echo "  - _Consultez le fichier \`$AUDIT_DIR/test_output.log\` pour les détails._" >> "$RAPPORT_FINAL"
        log_error "Erreur lors de l'exécution de la suite de tests."
    fi
}

# 6. Génération de la Synthèse
generer_synthese() {
    start_section "📊 Synthèse et Recommandations"

    echo "### ✅ Points Forts" >> "$RAPPORT_FINAL"
    # Ajouter des logiques pour détecter les points forts
    if php artisan test >/dev/null 2>&1; then echo "- La suite de tests est fonctionnelle." >> "$RAPPORT_FINAL"; fi
    if grep -q "fillable|guarded" app/Models/*.php 2>/dev/null; then echo "- La protection Mass Assignment est utilisée sur les modèles." >> "$RAPPORT_FINAL"; fi
    echo "" >> "$RAPPORT_FINAL"

    echo "### ⚠️ Points de vigilance et Axes d'Amélioration" >> "$RAPPORT_FINAL"
    # Ajouter des logiques pour détecter les points faibles
    if grep -q "APP_DEBUG=true" .env; then echo "- **Mode Débogage Actif :** \`APP_DEBUG\` est sur \`true\` dans \`.env\`. À désactiver en production." >> "$RAPPORT_FINAL"; fi
    obsolete_count=$( (grep -r -E "$(echo $OBSOLETE_TERMS | sed 's/ /|/g')" app 2>/dev/null || true) | wc -l)
    if [ "$obsolete_count" -gt 0 ]; then echo "- **Code Obsolète :** $obsolete_count références à des termes obsolètes ont été trouvées." >> "$RAPPORT_FINAL"; fi
    if ! grep -q "fillable|guarded" app/Models/*.php 2>/dev/null; then echo "- **Sécurité Modèles :** Au moins un modèle n'a pas de protection Mass Assignment." >> "$RAPPORT_FINAL"; fi
    if ! php artisan test >/dev/null 2>&1; then echo "- **Tests en Erreur :** La suite de tests ne passe pas." >> "$RAPPORT_FINAL"; fi
    echo "" >> "$RAPPORT_FINAL"

    echo "### 🎯 Actions Prioritaires Recommandées" >> "$RAPPORT_FINAL"
    echo "1.  **Sécurité :** Si \`APP_DEBUG\` est actif, planifier sa désactivation pour la production." >> "$RAPPORT_FINAL"
    echo "2.  **Maintenance :** Analyser et remplacer les \`$obsolete_count\` références au code obsolète." >> "$RAPPORT_FINAL"
    echo "3.  **Fiabilité :** Si la suite de tests échoue, la corriger est la priorité N°1 pour garantir la non-régression." >> "$RAPPORT_FINAL"
    echo "4.  **Bonnes Pratiques :** S'assurer que tous les modèles Eloquent ont une protection \`\$fillable\` ou \`\$guarded\`." >> "$RAPPORT_FINAL"
}

# ===============================================================================
# EXÉCUTION DU SCRIPT
# ===============================================================================
main() {
    start_time=$(date +%s)
    init_audit
    
    analyser_frontend
    analyser_database
    analyser_modeles
    analyser_controleurs
    analyser_tests
    generer_synthese
    
    end_time=$(date +%s)
    duration=$((end_time - start_time))
    
    echo "---" >> "$RAPPORT_FINAL"
    echo "**Audit terminé en $duration secondes.**" >> "$RAPPORT_FINAL"

    log "Audit terminé avec succès !"
    echo -e "📄 ${YELLOW}Rapport complet disponible ici :${NC} $RAPPORT_FINAL"
}

main "$@"


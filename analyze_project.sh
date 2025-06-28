#!/bin/bash

# ===============================================================================
# SCRIPT D'ANALYSE PROJET LARAVEL - SANS SUPPOSITIONS
# Analyse RÉELLE de l'architecture existante
# ===============================================================================

set -e
export LANG=C.UTF-8

PROJECT_PATH="."
ANALYSIS_FILE="project_analysis.txt"

echo "🔍 ANALYSE RÉELLE DU PROJET..."
echo "================================================" > "$ANALYSIS_FILE"
echo "ANALYSE PROJET LARAVEL - $(date)" >> "$ANALYSIS_FILE"
echo "================================================" >> "$ANALYSIS_FILE"

# --- 1. FRAMEWORK CSS - ANALYSE RÉELLE ---
echo "" >> "$ANALYSIS_FILE"
echo "🎨 FRAMEWORK CSS DÉTECTÉ:" >> "$ANALYSIS_FILE"
echo "------------------------" >> "$ANALYSIS_FILE"

# Chercher tous les layouts possibles
find resources/views -name "*.blade.php" -path "*/layouts/*" 2>/dev/null | while read layout; do
    echo "Layout trouvé: $layout" >> "$ANALYSIS_FILE"
    
    if grep -q "tailwind\|bg-slate\|text-slate" "$layout" 2>/dev/null; then
        echo "✅ TAILWIND CSS confirmé dans $layout" >> "$ANALYSIS_FILE"
        echo "   Classes trouvées: $(grep -o 'bg-[a-z]*-[0-9]*\|text-[a-z]*-[0-9]*' "$layout" | head -5 | tr '\n' ' ')" >> "$ANALYSIS_FILE"
    elif grep -q "adminlte\|sidebar-mini\|main-sidebar" "$layout" 2>/dev/null; then
        echo "✅ ADMINLTE confirmé dans $layout" >> "$ANALYSIS_FILE"
    elif grep -q "bootstrap\|btn-primary\|container-fluid" "$layout" 2>/dev/null; then
        echo "✅ BOOTSTRAP confirmé dans $layout" >> "$ANALYSIS_FILE"
    else
        echo "❓ Framework non identifié dans $layout" >> "$ANALYSIS_FILE"
    fi
done

# --- 2. FICHIERS DE ROUTES - TROUVER TOUS ---
echo "" >> "$ANALYSIS_FILE"
echo "🛣️ FICHIERS DE ROUTES TROUVÉS:" >> "$ANALYSIS_FILE"
echo "------------------------------" >> "$ANALYSIS_FILE"

find routes -name "*.php" 2>/dev/null | while read route_file; do
    echo "Route file: $route_file" >> "$ANALYSIS_FILE"
    
    # Analyser le contenu
    if grep -q "admin" "$route_file" 2>/dev/null; then
        echo "   ✅ Contient routes admin" >> "$ANALYSIS_FILE"
        echo "   Structure détectée:" >> "$ANALYSIS_FILE"
        grep -n "Route::" "$route_file" | head -3 >> "$ANALYSIS_FILE"
    fi
done

# --- 3. STRUCTURE DB RÉELLE ---
echo "" >> "$ANALYSIS_FILE"
echo "🗄️ STRUCTURE DB RÉELLE:" >> "$ANALYSIS_FILE"
echo "-----------------------" >> "$ANALYSIS_FILE"

# Scanner toutes les migrations
if [ -d "database/migrations" ]; then
    echo "Migrations trouvées:" >> "$ANALYSIS_FILE"
    ls -la database/migrations/*.php | while read migration; do
        migration_file=$(echo "$migration" | awk '{print $9}')
        if [ -f "$migration_file" ]; then
            table_name=$(grep -o "Schema::create('[^']*'" "$migration_file" 2>/dev/null | cut -d"'" -f2 | head -1)
            if [ ! -z "$table_name" ]; then
                echo "   - Table: $table_name ($(basename "$migration_file"))" >> "$ANALYSIS_FILE"
            fi
        fi
    done
else
    echo "❌ Dossier migrations introuvable" >> "$ANALYSIS_FILE"
fi

# --- 4. PERMISSIONS RÉELLES ---
echo "" >> "$ANALYSIS_FILE"
echo "🔐 PERMISSIONS RÉELLES:" >> "$ANALYSIS_FILE"
echo "----------------------" >> "$ANALYSIS_FILE"

# Scanner tous les seeders
find database/seeders -name "*.php" 2>/dev/null | while read seeder; do
    echo "Seeder: $(basename "$seeder")" >> "$ANALYSIS_FILE"
    
    if grep -q "Permission\|permission" "$seeder" 2>/dev/null; then
        echo "   Permissions trouvées:" >> "$ANALYSIS_FILE"
        grep -o "'[a-zA-Z0-9._-]*'" "$seeder" 2>/dev/null | grep -E "(view|create|edit|delete)" | head -5 | while read perm; do
            echo "     $perm" >> "$ANALYSIS_FILE"
        done
    fi
    
    if grep -q "Role\|role" "$seeder" 2>/dev/null; then
        echo "   Rôles trouvés:" >> "$ANALYSIS_FILE"
        grep -o "'[a-zA-Z0-9_-]*'" "$seeder" 2>/dev/null | grep -E "(admin|user|super|membre)" | head -5 | while read role; do
            echo "     $role" >> "$ANALYSIS_FILE"
        done
    fi
done

# --- 5. CONTRÔLEURS RÉELS ---
echo "" >> "$ANALYSIS_FILE"
echo "🎛️ CONTRÔLEURS TROUVÉS:" >> "$ANALYSIS_FILE"
echo "----------------------" >> "$ANALYSIS_FILE"

# Scanner tous les contrôleurs
find app/Http/Controllers -name "*.php" 2>/dev/null | while read controller; do
    controller_path=$(echo "$controller" | sed 's|app/Http/Controllers/||')
    echo "Contrôleur: $controller_path" >> "$ANALYSIS_FILE"
    
    # Vérifier le middleware
    if grep -q "public static function middleware" "$controller" 2>/dev/null; then
        echo "   ✅ Middleware Laravel 12 (statique)" >> "$ANALYSIS_FILE"
    elif grep -q "function __construct" "$controller" 2>/dev/null; then
        echo "   ❌ Middleware ancien format (constructeur)" >> "$ANALYSIS_FILE"
    fi
    
    # Méthodes trouvées
    methods=$(grep -o "public function [a-zA-Z]*" "$controller" 2>/dev/null | cut -d' ' -f3 | head -5 | tr '\n' ' ')
    if [ ! -z "$methods" ]; then
        echo "   Méthodes: $methods" >> "$ANALYSIS_FILE"
    fi
done

# --- 6. ASSETS RÉELS ---
echo "" >> "$ANALYSIS_FILE"
echo "🏗️ CONFIGURATION ASSETS RÉELLE:" >> "$ANALYSIS_FILE"
echo "-------------------------------" >> "$ANALYSIS_FILE"

# Vérifier les fichiers de config
if [ -f "vite.config.js" ]; then
    echo "✅ vite.config.js trouvé" >> "$ANALYSIS_FILE"
    echo "   Contenu:" >> "$ANALYSIS_FILE"
    head -10 vite.config.js >> "$ANALYSIS_FILE"
fi

if [ -f "webpack.mix.js" ]; then
    echo "✅ webpack.mix.js trouvé" >> "$ANALYSIS_FILE"
fi

if [ -f "tailwind.config.js" ]; then
    echo "✅ tailwind.config.js trouvé" >> "$ANALYSIS_FILE"
fi

if [ -f "package.json" ]; then
    echo "✅ package.json - dépendances CSS:" >> "$ANALYSIS_FILE"
    grep -A 10 -B 10 "tailwind\|bootstrap\|adminlte" package.json 2>/dev/null >> "$ANALYSIS_FILE"
fi

# --- 7. GÉNÉRATION PROMPT BASÉ SUR L'ANALYSE RÉELLE ---
echo "" >> "$ANALYSIS_FILE"
echo "🤖 PROMPT GÉNÉRÉ AUTOMATIQUEMENT:" >> "$ANALYSIS_FILE"
echo "=================================" >> "$ANALYSIS_FILE"

echo "UTILISEZ CE PROMPT EXACT AVEC CLAUDE :" >> "$ANALYSIS_FILE"
echo "" >> "$ANALYSIS_FILE"
echo "Voici l'analyse COMPLÈTE de mon projet (fichier joint: project_analysis.txt)." >> "$ANALYSIS_FILE"
echo "AVANT tout code, tu DOIS :" >> "$ANALYSIS_FILE"
echo "1. Lire ce fichier d'analyse" >> "$ANALYSIS_FILE"
echo "2. Me confirmer ce que tu as détecté" >> "$ANALYSIS_FILE"
echo "3. Respecter EXACTEMENT mon architecture existante" >> "$ANALYSIS_FILE"
echo "" >> "$ANALYSIS_FILE"
echo "INTERDICTIONS ABSOLUES :" >> "$ANALYSIS_FILE"
echo "❌ Supposer ou deviner quoi que ce soit" >> "$ANALYSIS_FILE"
echo "❌ Mélanger les frameworks" >> "$ANALYSIS_FILE"
echo "❌ Inventer des noms de tables/permissions" >> "$ANALYSIS_FILE"
echo "❌ Changer mon architecture" >> "$ANALYSIS_FILE"
echo "" >> "$ANALYSIS_FILE"
echo "OBLIGATION : Confirmer d'abord 'J'ai analysé votre fichier, vous utilisez [X, Y, Z]'" >> "$ANALYSIS_FILE"

echo ""
echo "✅ ANALYSE TERMINÉE !"
echo "📄 Résultat : $ANALYSIS_FILE"
echo ""
echo "🔗 COMMANDE POUR CLAUDE :"
echo "========================="
echo "Analyse mon projet avec ce fichier project_analysis.txt"
echo "CONFIRME d'abord ce que tu détectes avant de proposer du code."

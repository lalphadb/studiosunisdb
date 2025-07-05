#!/bin/bash

echo "🎛️ MISE À JOUR SÉCURISÉE DES CONTRÔLEURS"
echo "========================================"

BACKUP_CONTROLLERS="backup_controllers_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_CONTROLLERS"

# Backup complet des contrôleurs
echo "📦 Backup contrôleurs..."
cp -r app/Http/Controllers/ "$BACKUP_CONTROLLERS/"

# Lister les contrôleurs à modifier
echo ""
echo "🔍 CONTRÔLEURS À MODIFIER:"
find app/Http/Controllers/Admin -name "*.php" -exec grep -l "view.*admin\." {} \; | while read controller; do
    echo "  📄 $(basename $controller)"
done

echo ""
echo "🔄 MISE À JOUR PROGRESSIVE..."

# Fonction de mise à jour sécurisée
update_controller_references() {
    local controller_file="$1"
    local backup_file="${controller_file}.backup_$(date +%H%M%S)"
    
    # Backup individuel
    cp "$controller_file" "$backup_file"
    
    # Remplacements sécurisés
    sed -i.tmp \
        -e "s/view('admin\.dashboard/view('pages.admin.dashboard/g" \
        -e "s/view(\"admin\.dashboard/view(\"pages.admin.dashboard/g" \
        -e "s/view('admin\.users/view('pages.admin.users/g" \
        -e "s/view(\"admin\.users/view(\"pages.admin.users/g" \
        -e "s/view('admin\.cours/view('pages.admin.cours/g" \
        -e "s/view(\"admin\.cours/view(\"pages.admin.cours/g" \
        -e "s/view('admin\.ceintures/view('pages.admin.ceintures/g" \
        -e "s/view(\"admin\.ceintures/view(\"pages.admin.ceintures/g" \
        -e "s/view('admin\.presences/view('pages.admin.presences/g" \
        -e "s/view(\"admin\.presences/view(\"pages.admin.presences/g" \
        -e "s/view('admin\.paiements/view('pages.admin.paiements/g" \
        -e "s/view(\"admin\.paiements/view(\"pages.admin.paiements/g" \
        -e "s/view('admin\.sessions/view('pages.admin.sessions/g" \
        -e "s/view(\"admin\.sessions/view(\"pages.admin.sessions/g" \
        -e "s/view('admin\.seminaires/view('pages.admin.seminaires/g" \
        -e "s/view(\"admin\.seminaires/view(\"pages.admin.seminaires/g" \
        -e "s/view('admin\.ecoles/view('pages.admin.ecoles/g" \
        -e "s/view(\"admin\.ecoles/view(\"pages.admin.ecoles/g" \
        "$controller_file"
    
    # Vérifier que le fichier est toujours valide PHP
    if php -l "$controller_file" >/dev/null 2>&1; then
        echo "    ✅ $(basename $controller_file) - Syntaxe OK"
        rm "${controller_file}.tmp" 2>/dev/null
    else
        echo "    ❌ $(basename $controller_file) - ERREUR SYNTAXE - RESTAURATION"
        mv "$backup_file" "$controller_file"
        rm "${controller_file}.tmp" 2>/dev/null
    fi
}

# Appliquer les mises à jour
find app/Http/Controllers/Admin -name "*.php" -exec grep -l "view.*admin\." {} \; | while read controller; do
    echo "  🔄 Mise à jour: $(basename $controller)"
    update_controller_references "$controller"
done

echo ""
echo "✅ Mise à jour contrôleurs terminée"
echo "📦 Backup: $BACKUP_CONTROLLERS"

# Test syntax check
echo ""
echo "🧪 VÉRIFICATION SYNTAXE FINALE:"
syntax_errors=0
find app/Http/Controllers/Admin -name "*.php" | while read controller; do
    if ! php -l "$controller" >/dev/null 2>&1; then
        echo "❌ Erreur syntaxe: $controller"
        syntax_errors=$((syntax_errors + 1))
    fi
done

if [ $syntax_errors -eq 0 ]; then
    echo "✅ Toutes les syntaxes sont correctes"
else
    echo "❌ $syntax_errors erreurs de syntaxe détectées"
fi

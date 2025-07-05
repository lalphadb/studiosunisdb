#!/bin/bash

echo "📁 MIGRATION PROGRESSIVE ADMIN → PAGES"
echo "======================================"

BACKUP_MIGRATION="backup_migration_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_MIGRATION"

# Backup du dossier admin complet
echo "📦 Backup dossier admin..."
cp -r resources/views/admin/ "$BACKUP_MIGRATION/" 2>/dev/null

# Créer nouvelle structure
echo "🏗️ Création nouvelle structure pages/admin..."
mkdir -p resources/views/pages/admin/{dashboard,users,cours,ceintures,presences,paiements,sessions,seminaires,ecoles,exports,logs}

# Migration MODULE PAR MODULE avec vérification

echo ""
echo "📁 MIGRATION MODULE DASHBOARD:"
if [ -d "resources/views/admin/dashboard" ]; then
    cp -r resources/views/admin/dashboard/* resources/views/pages/admin/dashboard/ 2>/dev/null
    echo "  ✅ Dashboard migré ($(ls resources/views/pages/admin/dashboard/ | wc -l) fichiers)"
else
    echo "  ⚠️ Dossier dashboard non trouvé"
fi

echo ""
echo "📁 MIGRATION MODULE USERS:"
if [ -d "resources/views/admin/users" ]; then
    cp -r resources/views/admin/users/* resources/views/pages/admin/users/ 2>/dev/null
    echo "  ✅ Users migré ($(ls resources/views/pages/admin/users/ | wc -l) fichiers)"
else
    echo "  ⚠️ Dossier users non trouvé"
fi

echo ""
echo "📁 MIGRATION MODULE COURS:"
if [ -d "resources/views/admin/cours" ]; then
    cp -r resources/views/admin/cours/* resources/views/pages/admin/cours/ 2>/dev/null
    echo "  ✅ Cours migré ($(ls resources/views/pages/admin/cours/ | wc -l) fichiers)"
else
    echo "  ⚠️ Dossier cours non trouvé"
fi

# Continuer pour les autres modules principaux
for module in ceintures presences paiements sessions seminaires ecoles exports logs; do
    echo ""
    echo "📁 MIGRATION MODULE ${module^^}:"
    if [ -d "resources/views/admin/$module" ]; then
        cp -r resources/views/admin/$module/* resources/views/pages/admin/$module/ 2>/dev/null
        count=$(ls resources/views/pages/admin/$module/ 2>/dev/null | wc -l)
        echo "  ✅ $module migré ($count fichiers)"
    else
        echo "  ⚠️ Dossier $module non trouvé"
    fi
done

echo ""
echo "✅ Migration terminée"
echo "📊 RÉSULTATS:"
echo "- Total fichiers migrés: $(find resources/views/pages/admin -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Modules créés: $(find resources/views/pages/admin -type d -mindepth 1 -maxdepth 1 | wc -l)"

# NE PAS SUPPRIMER L'ANCIEN DOSSIER ADMIN ENCORE - SÉCURITÉ
echo ""
echo "⚠️ IMPORTANT: Ancien dossier admin conservé pour sécurité"
echo "   Il sera supprimé après validation complète"

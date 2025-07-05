#!/bin/bash

echo "🛡️ PRÉPARATION SÉCURISÉE STANDARDISATION STUDIOSDB"
echo "================================================="

# Créer backup complet
BACKUP_DIR="BACKUP_COMPLET_$(date +%Y%m%d_%H%M%S)"
echo "📦 Création backup complet: $BACKUP_DIR"

mkdir -p "$BACKUP_DIR"
cp -r resources/ "$BACKUP_DIR/"
cp -r app/Http/Controllers/ "$BACKUP_DIR/"
cp -r routes/ "$BACKUP_DIR/"

echo "✅ Backup créé dans: $BACKUP_DIR"

# Vérifier l'état actuel du projet
echo ""
echo "🔍 DIAGNOSTIC PRÉ-STANDARDISATION:"
echo "================================="

# Tester si le projet fonctionne
if command -v php &> /dev/null; then
    echo "✅ PHP disponible"
    
    # Test Artisan
    if php artisan --version 2>/dev/null; then
        echo "✅ Laravel fonctionne"
    else
        echo "❌ Laravel a des problèmes"
        exit 1
    fi
else
    echo "⚠️ PHP non disponible pour tests"
fi

# Vérifier routes critiques
echo ""
echo "🛣️ Routes actuelles admin:"
php artisan route:list --path=admin 2>/dev/null | head -10 || echo "❌ Impossible de lister les routes"

# Compter fichiers actuels
echo ""
echo "📊 ÉTAT ACTUEL:"
echo "- Controllers Admin: $(find app/Http/Controllers/Admin -name '*.php' 2>/dev/null | wc -l)"
echo "- Vues Admin: $(find resources/views/admin -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Components: $(find resources/views/components -name '*.blade.php' 2>/dev/null | wc -l)"
echo "- Partials: $(find resources/views/partials -name '*.blade.php' 2>/dev/null | wc -l)"

echo ""
echo "🔧 VÉRIFICATIONS CRITIQUES:"

# Vérifier BaseAdminController (CRITIQUE)
if grep -r "extends BaseAdminController" app/Http/Controllers/Admin/ >/dev/null 2>&1; then
    echo "✅ BaseAdminController est utilisé"
else
    echo "❌ ERREUR CRITIQUE: BaseAdminController non utilisé!"
    echo "   Vérification manuelle requise avant standardisation"
fi

# Vérifier admin-navigation
if grep -q "admin-navigation" resources/views/layouts/admin.blade.php 2>/dev/null; then
    echo "✅ Admin navigation référencée dans layout"
else
    echo "❌ Admin navigation non trouvée dans layout"
fi

# Vérifier l'utilisateur de référence
echo ""
echo "👤 UTILISATEUR DE RÉFÉRENCE (Lalpha):"
php artisan tinker --execute="
try {
    \$user = App\\Models\\User::find(5);
    if (\$user) {
        echo '✅ Utilisateur Lalpha trouvé: ' . \$user->name . '\\n';
        echo '   Email: ' . \$user->email . '\\n';
        echo '   Permissions: ' . \$user->getAllPermissions()->count() . '\\n';
    } else {
        echo '❌ Utilisateur Lalpha (ID 5) non trouvé\\n';
    }
} catch (Exception \$e) {
    echo '❌ Erreur: ' . \$e->getMessage() . '\\n';
}
" 2>/dev/null || echo "⚠️ Impossible de vérifier l'utilisateur"

echo ""
echo "✅ Préparation terminée. Backup disponible dans: $BACKUP_DIR"
echo ""
echo "⚠️ AVANT DE CONTINUER:"
echo "1. Vérifiez que votre application fonctionne actuellement"
echo "2. Confirmez que vous avez un backup"
echo "3. Testez quelques pages admin manuellement"

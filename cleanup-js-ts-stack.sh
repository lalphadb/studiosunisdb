#!/bin/bash
echo "=== NETTOYAGE STACK JS/TS STUDIOSDB ==="

# Sauvegarde avant suppression
mkdir -p backups/ts-cleanup-$(date +%Y%m%d_%H%M%S)
echo "Sauvegarde des fichiers TS avant suppression..."

# Copier les fichiers TS vers backup
cp resources/js/app.ts backups/ts-cleanup-*/app.ts.backup 2>/dev/null || true
cp resources/js/bootstrap.ts backups/ts-cleanup-*/bootstrap.ts.backup 2>/dev/null || true

# Vérifier que les fichiers JS sont bien utilisés
echo "Vérification configuration active :"
echo "- vite.config.js utilise :"
grep -o "resources/js/app\.js" vite.config.js || echo "⚠️  app.js non trouvé dans vite.config.js"

echo "- app.blade.php utilise :"  
grep -o "resources/js/app\.js" resources/views/app.blade.php || echo "⚠️  app.js non trouvé dans app.blade.php"

# Rechercher si theme.ts est utilisé
echo ""
echo "Recherche utilisation theme.ts :"
grep -r "import.*theme" resources/js/ --include="*.vue" --include="*.js" || echo "❌ theme.ts non importé"
grep -r "from.*theme" resources/js/ --include="*.vue" --include="*.js" || echo "❌ theme.ts non importé"

# Supprimer les fichiers TS obsolètes (sauf theme.ts pour le moment)
echo ""
echo "Suppression fichiers TypeScript obsolètes :"
rm -v resources/js/app.ts
rm -v resources/js/bootstrap.ts

echo ""
echo "Conservation temporaire theme.ts (à valider si utilisé)"
ls -la resources/js/theme.ts

echo ""
echo "✅ Nettoyage terminé - Stack JavaScript principal unifié"
echo "📁 Backups disponibles dans backups/ts-cleanup-*/"
echo "🔍 Valider usage theme.ts avant suppression finale"

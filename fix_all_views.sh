#!/bin/bash
echo "🔧 Correction de toutes les vues admin..."

# Corriger toutes les vues avec des erreurs similaires
find resources/views/admin -name "*.blade.php" -exec grep -l "\$.*->id.*old(" {} \; | while read file; do
    echo "Correction de $file..."
    # Backup
    cp "$file" "$file.backup"
    
    # Correction basique des erreurs null
    sed -i 's/{{ old(\(.*\)) == \$\([^-]*\)->id/{{ old(\1) == (isset(\$\2) ? \$\2->id : null)/g' "$file"
done

echo "✅ Correction des vues terminée!"

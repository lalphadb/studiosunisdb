#!/bin/bash

echo "🔧 Correction des références obsolètes dans les vues..."

# Backup des vues avant modification
cp -r resources/views resources/views.backup.$(date +%Y%m%d_%H%M%S)

# Correction des références 'membre_id' vers 'user_id'
find resources/views -name "*.blade.php" -exec sed -i 's/membre_id/user_id/g' {} \;

# Correction des variables $membre vers $user
find resources/views -name "*.blade.php" -exec sed -i 's/\$membre\b/\$user/g' {} \;

# Correction des variables $membres vers $users  
find resources/views -name "*.blade.php" -exec sed -i 's/\$membres\b/\$users/g' {} \;

# Correction des références de modèle 'App\Models\Membre' vers 'App\Models\User'
find resources/views -name "*.blade.php" -exec sed -i 's/App\\Models\\Membre/App\\Models\\User/g' {} \;

# Correction des méthodes de relation
find resources/views -name "*.blade.php" -exec sed -i 's/->membre\b/->user/g' {} \;

echo "✅ Correction terminée !"
echo "📁 Backup créé dans: resources/views.backup.$(date +%Y%m%d_%H%M%S)"
echo ""
echo "📋 Fichiers modifiés:"
find resources/views -name "*.blade.php" -exec grep -l "user_id\|\\$user\|\\$users" {} \; | head -10

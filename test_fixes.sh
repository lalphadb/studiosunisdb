#!/bin/bash

echo "🧪 Test des corrections..."

# Test 1: Vérifier les routes
echo "1. Test des routes admin..."
php artisan route:list --name=admin | head -10

# Test 2: Vérifier les permissions
echo "2. Test des permissions..."
php artisan permission:show | head -10

# Test 3: Vérifier les modèles
echo "3. Test des modèles..."
php artisan tinker --execute="
use App\Models\User;
use App\Models\Seminaire;
echo 'Users: ' . User::count() . \"\n\";
echo 'Seminaires: ' . Seminaire::count() . \"\n\";
"

# Test 4: Vérifier les vues
echo "4. Test des vues..."
if [ -f "resources/views/admin/seminaires/inscrire.blade.php" ]; then
    echo "✅ Vue inscrire.blade.php existe"
else
    echo "❌ Vue inscrire.blade.php manquante"
fi

echo "🎉 Tests terminés !"

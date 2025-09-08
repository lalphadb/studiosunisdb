#!/bin/bash

echo "=== NETTOYAGE RÉFÉRENCES MEMBRES ==="

# 1. Exécuter les migrations
echo "1. Application des migrations..."
php artisan migrate

# 2. Nettoyer les routes (déjà redirigées)
echo "2. Routes déjà configurées avec redirections"

# 3. Remplacer les références dans les modèles
echo "3. Mise à jour des modèles..."

# Paiement.php
sed -i "s/membre_id/user_id/g" app/Models/Paiement.php
sed -i "s/belongsTo(Membre::class/belongsTo(User::class/g" app/Models/Paiement.php

# Presence.php  
sed -i "s/membre_id/user_id/g" app/Models/Presence.php
sed -i "s/belongsTo(Membre::class/belongsTo(User::class/g" app/Models/Presence.php

# ProgressionCeinture.php
sed -i "s/membre_id/user_id/g" app/Models/ProgressionCeinture.php
sed -i "s/belongsTo(Membre::class/belongsTo(User::class/g" app/Models/ProgressionCeinture.php

# Examen.php
sed -i "s/membre_id/user_id/g" app/Models/Examen.php
sed -i "s/belongsTo(Membre::class/belongsTo(User::class/g" app/Models/Examen.php

# 4. Remplacer dans les contrôleurs
echo "4. Mise à jour des contrôleurs..."
find app/Http/Controllers -name "*.php" -exec sed -i 's/membre_id/user_id/g' {} \;
find app/Http/Controllers -name "*.php" -exec sed -i 's/->membre/->user/g' {} \;

# 5. Remplacer dans les vues Vue.js
echo "5. Mise à jour des composants Vue..."
find resources/js -name "*.vue" -exec sed -i 's/membre_id/user_id/g' {} \;
find resources/js -name "*.vue" -exec sed -i 's/membre\./user\./g' {} \;

# 6. Compiler les assets
echo "6. Compilation des assets..."
npm run build

# 7. Clear caches
echo "7. Nettoyage des caches..."
php artisan optimize:clear

echo "=== NETTOYAGE TERMINÉ ==="

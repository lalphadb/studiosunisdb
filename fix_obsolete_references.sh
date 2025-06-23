#!/bin/bash

echo "🧹 Nettoyage des références obsolètes 'membre'..."

# Sauvegarder le contrôleur original
cp app/Http/Controllers/Admin/InscriptionSeminaireController.php app/Http/Controllers/Admin/InscriptionSeminaireController.php.backup

# Corriger les références dans UserController
sed -i "s/\$metrics\['membres'\]/\$metrics['users']/g" app/Http/Controllers/Admin/UserController.php
sed -i "s/'membres' => 0/'users' => 0/g" app/Http/Controllers/Admin/UserController.php

# Corriger LogController
sed -i "s/'App\\\\Models\\\\Membre'/'App\\\\Models\\\\User'/g" app/Http/Controllers/Admin/LogController.php

# Vider les caches
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear

echo "✅ Nettoyage terminé !"

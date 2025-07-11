#!/bin/bash

echo "=== ÉTAPE 1: Nettoyer la base de données ==="
php artisan migrate:fresh
echo "✓ Base de données réinitialisée"

echo -e "\n=== ÉTAPE 2: Exécuter les seeders de base ==="
php artisan db:seed --class=EcolesSeeder
php artisan db:seed --class=CompletePermissionsSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=CeinturesSeeder
echo "✓ Seeders de base exécutés"

echo -e "\n=== ÉTAPE 3: Tester le TestDataSeeder ==="
php artisan db:seed --class=TestDataSeeder
echo "✓ Données de test créées"

echo -e "\n=== ÉTAPE 4: Vérifier les données ==="
php artisan tinker --execute="echo 'Écoles: ' . App\Models\Ecole::count()"
php artisan tinker --execute="echo 'Utilisateurs: ' . App\Models\User::count()"
php artisan tinker --execute="echo 'Cours: ' . App\Models\Cours::count()"

echo -e "\n=== ÉTAPE 5: Committer les modèles ==="
git add app/Models/*.php
git add database/seeders/UserSeeder.php
git add config/sanctum.php
git add database/migrations/2025_07_10_201832_create_personal_access_tokens_table.php

# Nettoyer les seeders supprimés
git rm database/seeders/AdminUsersSeeder.php
git rm database/seeders/AuthUsersSeeder.php
git rm database/seeders/CreateLoginUsersSeeder.php
git rm database/seeders/CreateLouisUserSeeder.php
git rm database/seeders/FixLouisUserSeeder.php
git rm database/seeders/PermissionsSeeder.php
git rm database/seeders/SuperAdminSeeder.php
git rm database/seeders/TestUsersSeeder.php
git rm database/seeders/UsersTestSeeder.php

# Ajouter les modifications des seeders
git add database/seeders/DatabaseSeeder.php
git add database/seeders/TestDataSeeder.php
git add database/seeders/CeinturesSeeder.php
git add database/seeders/EcolesSeeder.php

# Committer
git commit -m "feat: ajout des modèles manquants et nettoyage des seeders

- Ajout des modèles : Ceinture, UserCeinture, CoursHoraire, InscriptionCours, Paiement, Seminaire, InscriptionSeminaire
- Configuration Sanctum pour API
- Nettoyage des seeders obsolètes
- Mise à jour des seeders existants
- Base de données prête pour le développement"

echo -e "\n=== ÉTAPE 6: Afficher le nouveau status ==="
git log --oneline -3
echo
git status

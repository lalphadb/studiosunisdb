#!/bin/bash

# Script de diagnostic pour StudiosDB
# Exécution : bash diagnostic.sh > rapport_diagnostic.txt 2>&1

echo "======================================"
echo "DIAGNOSTIC STUDIOSDB - $(date)"
echo "======================================"
echo

echo "=== 1. INFORMATIONS GIT ==="
echo "--- Branche actuelle ---"
git branch --show-current
echo

echo "--- 20 derniers commits ---"
git log --oneline -20
echo

echo "--- Détails du dernier commit ---"
git show --name-status
echo

echo "--- État Git actuel ---"
git status
echo

echo "=== 2. STRUCTURE DU PROJET ==="
echo "--- Répertoire racine ---"
pwd
ls -la
echo

echo "--- Structure des dossiers ---"
if command -v tree &> /dev/null; then
    tree -L 2 -I 'vendor|node_modules|.git'
else
    find . -maxdepth 2 -type d | grep -v -E '(vendor|node_modules|\.git)' | sort
fi
echo

echo "=== 3. VÉRIFICATION LARAVEL ==="
echo "--- Version PHP ---"
php -v | head -1
echo

echo "--- Laravel installé ? ---"
if [ -f "artisan" ]; then
    php artisan --version
else
    echo "ERREUR: Fichier artisan non trouvé!"
fi
echo

echo "--- Composer installé ? ---"
if [ -f "composer.json" ]; then
    echo "composer.json trouvé"
    composer show laravel/framework 2>/dev/null || echo "Laravel framework non installé via Composer"
else
    echo "ERREUR: composer.json non trouvé!"
fi
echo

echo "=== 4. FICHIERS CRITIQUES ==="
echo "--- Vérification des fichiers essentiels ---"
for file in "composer.json" "artisan" ".env" ".env.example" "bootstrap/app.php"; do
    if [ -f "$file" ]; then
        echo "✓ $file existe"
    else
        echo "✗ $file MANQUANT!"
    fi
done
echo

echo "=== 5. RÉPERTOIRES LARAVEL ==="
echo "--- app/ ---"
ls -la app/ 2>/dev/null || echo "Dossier app/ non trouvé"
echo

echo "--- database/migrations/ ---"
ls -la database/migrations/ 2>/dev/null || echo "Dossier migrations/ non trouvé"
echo

echo "--- database/seeders/ ---"
ls -la database/seeders/ 2>/dev/null || echo "Dossier seeders/ non trouvé"
echo

echo "--- routes/ ---"
ls -la routes/ 2>/dev/null || echo "Dossier routes/ non trouvé"
echo

echo "=== 6. BASE DE DONNÉES ==="
if [ -f "artisan" ] && [ -f ".env" ]; then
    echo "--- État des migrations ---"
    php artisan migrate:status 2>/dev/null || echo "Impossible d'exécuter migrate:status"
    echo
    
    echo "--- Configuration BD (.env) ---"
    grep -E "^DB_" .env | sed 's/DB_PASSWORD=.*/DB_PASSWORD=****/'
else
    echo "Impossible de vérifier la BD - fichiers manquants"
fi
echo

echo "=== 7. TABLES MySQL (si accessible) ==="
if command -v mysql &> /dev/null && [ -f ".env" ]; then
    DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
    DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
    echo "Tentative de connexion à la BD: $DB_DATABASE"
    echo "Entrez le mot de passe MySQL si demandé:"
    mysql -u $DB_USERNAME -p $DB_DATABASE -e "SHOW TABLES;" 2>/dev/null || echo "Connexion MySQL échouée"
else
    echo "MySQL non accessible ou .env manquant"
fi
echo

echo "=== 8. RÉSUMÉ DES PROBLÈMES DÉTECTÉS ==="
problems=0

if [ ! -f "artisan" ]; then
    echo "❌ Laravel n'est pas installé (artisan manquant)"
    ((problems++))
fi

if [ ! -f "composer.json" ]; then
    echo "❌ composer.json manquant"
    ((problems++))
fi

if [ ! -f ".env" ]; then
    echo "❌ Fichier .env manquant"
    ((problems++))
fi

if [ ! -d "database/migrations" ] || [ -z "$(ls -A database/migrations 2>/dev/null)" ]; then
    echo "❌ Aucune migration trouvée"
    ((problems++))
fi

if [ $problems -eq 0 ]; then
    echo "✅ Aucun problème critique détecté"
else
    echo
    echo "Total: $problems problème(s) critique(s) détecté(s)"
fi

echo
echo "======================================"
echo "FIN DU DIAGNOSTIC - $(date)"
echo "======================================"

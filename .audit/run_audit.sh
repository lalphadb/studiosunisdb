#!/bin/bash
echo "🔍 Audit StudiosDB en cours..."

# Créer dossier d'audit
mkdir -p .audit
cd .audit

# Collecter les informations
echo "📊 Collection des données..."
php artisan about > laravel_about.txt 2>&1
php artisan migrate:status > migration_status.txt 2>&1
php artisan route:list > routes_list.txt 2>&1
composer show > composer_packages.txt 2>&1
git log --oneline -30 > git_history.txt
git branch -a > git_branches.txt
tree -L 3 -I 'vendor|node_modules|storage' .. > tree_structure.txt 2>&1

# Afficher un résumé
echo "✅ Audit terminé!"
echo ""
echo "=== LARAVEL INFO ==="
head -20 laravel_about.txt
echo ""
echo "=== DERNIERS COMMITS ==="
head -10 git_history.txt
echo ""
echo "=== STRUCTURE ==="
head -30 tree_structure.txt

cd ..

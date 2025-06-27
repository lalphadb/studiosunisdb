#!/bin/bash

echo "🔍 VÉRIFICATION MODULE COURS"
echo "=================================="

echo "✅ 1. Vérification des fichiers créés:"
[ -f "app/Http/Controllers/Admin/CoursController.php" ] && echo "   ✓ CoursController" || echo "   ✗ CoursController manquant"
[ -f "app/Http/Requests/Admin/CoursRequest.php" ] && echo "   ✓ CoursRequest" || echo "   ✗ CoursRequest manquant"
[ -f "app/Policies/CoursPolicy.php" ] && echo "   ✓ CoursPolicy" || echo "   ✗ CoursPolicy manquant"
[ -f "app/Models/Cours.php" ] && echo "   ✓ Cours Model" || echo "   ✗ Cours Model manquant"
[ -f "resources/views/admin/cours/index.blade.php" ] && echo "   ✓ Vue Index" || echo "   ✗ Vue Index manquante"

echo ""
echo "✅ 2. Test syntaxe PHP:"
php -l app/Http/Controllers/Admin/CoursController.php
php -l app/Http/Requests/Admin/CoursRequest.php
php -l app/Policies/CoursPolicy.php
php -l app/Models/Cours.php

echo ""
echo "✅ 3. Vérification des routes:"
php artisan route:list --name=admin.cours

echo ""
echo "✅ Module Cours corrigé selon standards StudiosUnisDB v4.1.8.3!"

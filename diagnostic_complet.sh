#!/bin/bash

echo "🔍 DIAGNOSTIC COMPLET StudiosUnisDB v4.1.8.6-DEV"
echo "================================================"

echo "=== MODÈLES TROUVÉS ==="
ls -la app/Models/ | grep "\.php"
echo ""

echo "=== FORMREQUESTS COURS/SEMINAIRE ==="
ls -la app/Http/Requests/ | grep -E "(Cours|Seminaire|cours|seminaire)"
echo ""

echo "=== TEST PHPUNIT ==="
composer show | grep phpunit || echo "PHPUnit non installé"
echo ""

echo "=== COMPOSANT X-MODULE-HEADER ==="
head -10 resources/views/components/module-header.blade.php
echo ""

echo "=== ROUTES ADMIN INCLUSION ==="
grep -n "require.*admin" routes/web.php || echo "admin.php non inclus"
echo ""

echo "=== BINDING ROUTES COURS ==="
grep -r "{cour}" routes/ || echo "Binding {cour} non trouvé"
grep -r "{cours}" routes/ || echo "Binding {cours} non trouvé"


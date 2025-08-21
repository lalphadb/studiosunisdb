#!/usr/bin/env bash
set -euo pipefail
echo "== Audit Modules Membres =="

echo "-- Contrôleurs --"
ls -la app/Http/Controllers/*Membre* app/Http/Controllers/*Member* 2>/dev/null || true

echo "-- Routes --"
php artisan route:list | grep -E 'membres|member' || echo "Aucune route membres détectée"

echo "-- Références contrôleurs --"
grep -Rni "MembreController" routes app 2>/dev/null || true
grep -Rni "MemberController" routes app 2>/dev/null || true

echo "-- Export Excel --"
grep -Rni "maatwebsite/excel" composer.* vendor 2>/dev/null || echo "Maatwebsite Excel absent"

echo "-- ActivityLog --"
grep -Rni "spatie/laravel-activitylog" composer.* vendor 2>/dev/null || echo "ActivityLog absent"

echo "-- Policies/authorizeResource --"
grep -Rni "authorizeResource(.*Membre" app 2>/dev/null || echo "authorizeResource pour Membre: à ajouter"
grep -Rni "MembrePolicy" app 2>/dev/null || echo "MembrePolicy absente ou non référencée"

echo "-- Containers max-width (peuvent créer l'espace vide) --"
grep -Rni "max-w-7xl\\|container mx-auto" resources/js/Pages/Membres 2>/dev/null || echo "OK: pas de max-width bloquante (côté Membres)"

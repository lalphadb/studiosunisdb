#!/bin/bash

echo "🔧 RÉPARATION PERMISSIONS STUDIOSUNISDB"
echo "======================================="

# Nettoyer les caches d'abord
php artisan permission:cache-reset
php artisan optimize:clear

echo "✅ Permissions réparées"
echo "🔑 Testez avec: lalpha@4lb.ca / QwerTfc443-studios!"

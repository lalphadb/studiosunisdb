#!/bin/bash
echo "🔒 AUDIT SÉCURITÉ StudiosUnisDB"

echo "1️⃣ Vérification configuration..."
# Vérifier APP_DEBUG
if grep -q "APP_DEBUG=false" .env.production; then
    echo "✅ APP_DEBUG désactivé"
else
    echo "❌ APP_DEBUG doit être false en production"
fi

# Vérifier APP_ENV
if grep -q "APP_ENV=production" .env.production; then
    echo "✅ APP_ENV=production"
else
    echo "❌ APP_ENV doit être production"
fi

echo "2️⃣ Test headers sécurité..."
# Test headers sécurité (nécessite serveur lancé)
curl -I http://localhost:8001 2>/dev/null | grep -E "(X-Content-Type-Options|X-Frame-Options|X-XSS-Protection)"

echo "3️⃣ Permissions fichiers..."
ls -la .env* | head -3

echo "4️⃣ Test middleware Telescope..."
php artisan tinker --execute="echo 'Telescope: ' . (config('telescope.enabled') ? 'ON' : 'OFF');"

echo "5️⃣ Test rôles/permissions..."
php artisan permission:show

echo "✅ AUDIT SÉCURITÉ TERMINÉ"

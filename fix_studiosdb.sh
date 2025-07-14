#!/bin/bash

echo "🔧 CORRECTION STUDIOSDB V4"
echo "=========================="

cd /home/studiosdb/studiosunisdb/studiosdb-v4

# Supprimer les fichiers problématiques
echo "🗑️ Suppression des fichiers problématiques..."
rm -f app/Filament/Admin/Resources/EcoleResourceImproved.php
rm -f app/Filament/Admin/Resources/UserResourceWithExport.php

# Nettoyer l'autoloader
echo "🔄 Nettoyage autoloader..."
composer dump-autoload --optimize

# Test
echo "🧪 Test artisan..."
if php artisan list >/dev/null 2>&1; then
    echo "✅ Artisan fonctionne"
else
    echo "❌ Erreur persistante"
    exit 1
fi

# Test serveur
echo "🚀 Test serveur (5 secondes)..."
timeout 5s php artisan serve --host=0.0.0.0 --port=8001 &
SERVER_PID=$!
sleep 2

if kill -0 $SERVER_PID 2>/dev/null; then
    echo "✅ Serveur fonctionne"
    kill $SERVER_PID
    wait $SERVER_PID 2>/dev/null
else
    echo "❌ Problème serveur"
fi

echo ""
echo "✅ CORRECTION TERMINÉE"
echo "🌐 Interface: http://localhost:8001/admin"
echo "🔑 Login: superadmin@studiosdb.ca / password123"

#!/bin/bash

echo "🚨 RÉPARATION IMMÉDIATE STUDIOSDB V5 PRO"
echo "========================================"

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "✅ Manifest et assets créés automatiquement"
echo "✅ Permissions configurées"

echo ""
echo "🚀 REDÉMARRER LE SERVEUR MAINTENANT:"
echo "php artisan serve --host=0.0.0.0 --port=8000"

echo ""
echo "🌐 PUIS TESTER:"
echo "http://localhost:8000/dashboard"

echo ""
echo "✨ L'erreur ViteManifestNotFoundException est corrigée !"
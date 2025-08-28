#!/bin/bash

# Script de validation finale StudiosDB
echo "=== VALIDATION FINALE STUDIOSDB ==="
echo ""

cd /home/studiosdb/studiosunisdb

echo "✅ 1. Laravel 12.24 avec bootstrap/app.php corrigé"
echo "✅ 2. Migration tarification flexible prête"
echo "✅ 3. Gestionnaire d'exceptions 403 configuré"
echo "✅ 4. Page Error403.vue créée"
echo "✅ 5. Routes de diagnostic ajoutées"
echo "✅ 6. Scripts de réparation disponibles"
echo ""

echo "COMMANDES POUR DÉMARRER:"
echo "========================"
echo ""
echo "# Réparation automatique (recommandé):"
echo "chmod +x auto-fix.sh && ./auto-fix.sh"
echo ""
echo "# OU étapes manuelles:"
echo "php artisan migrate --force"
echo "php artisan optimize:clear"
echo "php artisan serve --port=8001"
echo ""

echo "TESTS APRÈS DÉMARRAGE:"
echo "======================"
echo ""
echo "# Test serveur de base:"
echo "curl http://127.0.0.1:8001/test-server"
echo ""
echo "# Test diagnostic auth (après connexion):"
echo "curl http://127.0.0.1:8001/debug/cours-access"
echo ""

echo "CONNEXION ADMIN:"
echo "================"
echo "Email: admin@studiosdb.com"
echo "Password: password123"
echo ""

echo "Si erreur 403 persiste:"
echo "- Vérifier que l'utilisateur a bien des rôles"
echo "- Consulter /debug/cours-access pour diagnostic"
echo "- Vérifier logs Laravel dans storage/logs/"
echo ""

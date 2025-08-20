#!/bin/bash

echo "🔄 TEST FINAL DE CONNEXION STUDIOSDB V5 PRO"
echo "==========================================="
echo ""

# Attendre que le serveur démarre
echo "⏳ Attente du démarrage du serveur..."
sleep 3

# Test de l'accès à la page de login
echo "🔍 Test de la page de login..."
if curl -s http://localhost:8001/login | grep -q "StudiosDB"; then
    echo "✅ Page de login accessible"
else
    echo "❌ Page de login inaccessible"
    exit 1
fi

echo ""
echo "🎯 INSTRUCTIONS DE TEST MANUEL:"
echo "================================"
echo ""
echo "1. 🌐 Ouvrez votre navigateur"
echo "2. 📍 Allez à: http://localhost:8001/login"
echo "3. 📧 Entrez: louis@4lb.ca"
echo "4. 🔑 Entrez: password123"
echo "5. 🔐 Cliquez sur 'Se connecter'"
echo ""
echo "✅ Le dashboard devrait s'afficher !"
echo ""
echo "🆘 En cas de problème:"
echo "======================"
echo "- Assurez-vous que JavaScript est activé"
echo "- Vérifiez la console du navigateur (F12)"
echo "- Essayez de rafraîchir la page"
echo "- Essayez en mode navigation privée"
echo ""
echo "🔧 Alternative - Page de test:"
echo "=============================="
echo "📍 http://localhost:8001/test-login"
echo ""

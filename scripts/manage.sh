#!/bin/bash

echo "🛠️  STUDIOSUNISDB - GESTIONNAIRE DE MAINTENANCE"
echo "==============================================="
echo ""
echo "Choisissez une action :"
echo ""
echo "1) 🔍 Diagnostic rapide"
echo "2) 🔍 Vérification complète" 
echo "3) 🔧 Réparer permissions"
echo "4) 👥 Réparer utilisateurs"
echo "5) 🚀 Post-déploiement complet"
echo "6) ❌ Quitter"
echo ""
read -p "Votre choix (1-6): " choice

case $choice in
    1)
        echo "🔍 Lancement diagnostic..."
        ./scripts/diagnose.sh
        ;;
    2)
        echo "🔍 Lancement vérification complète..."
        ./scripts/verify-deployment.sh
        ;;
    3)
        echo "🔧 Réparation permissions..."
        ./scripts/fix-permissions.sh
        ;;
    4)
        echo "👥 Réparation utilisateurs..."
        ./scripts/fix-users.sh
        ;;
    5)
        echo "🚀 Post-déploiement complet..."
        ./scripts/post-deploy-fix.sh
        ;;
    6)
        echo "👋 Au revoir !"
        exit 0
        ;;
    *)
        echo "❌ Choix invalide"
        exit 1
        ;;
esac

echo ""
echo "✅ Action terminée"

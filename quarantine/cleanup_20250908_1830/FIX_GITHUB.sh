#!/bin/bash
echo "🐙 GITHUB STUDIOSDB - DIAGNOSTIC & RÉSOLUTION"
echo "=============================================="
cd /home/studiosdb/studiosunisdb

echo ""
echo "🎯 PROBLÈME RAPPORTÉ:"
echo "   \"Je ne vois jamais les sauvegardes\""
echo "   \"Le main remonte à 3 semaines\""
echo ""

echo "🔍 DIAGNOSTIC AUTOMATIQUE..."
echo ""

# Rendre scripts exécutables
chmod +x *.sh 2>/dev/null

# Diagnostic rapide
if [ -f "CHECK_GIT.sh" ]; then
    ./CHECK_GIT.sh
    CHECK_RESULT=$?
else
    echo "❌ Script de vérification manquant"
    CHECK_RESULT=1
fi

echo ""
echo "=============================================="

if [ $CHECK_RESULT -eq 0 ]; then
    echo "✅ DIAGNOSTIC INITIAL RÉUSSI"
    echo ""
    echo "🔄 ACTIONS DISPONIBLES:"
    echo ""
    echo "1) 🐙 MENU GITHUB COMPLET"
    echo "   └─ ./GITHUB.sh"
    echo "      Analyse + Configuration + Synchronisation"
    echo ""
    echo "2) 🚀 SYNCHRONISATION DIRECTE"  
    echo "   └─ ./SYNC_GITHUB.sh"
    echo "      Push commits vers GitHub maintenant"
    echo ""
    echo "3) 📖 GUIDE DÉTAILLÉ"
    echo "   └─ cat GITHUB_GUIDE.md"
    echo "      Instructions complètes"
    echo ""
    
    read -p "👉 Action recommandée : MENU GITHUB (1) ? [Y/n] " -r
    echo ""
    
    if [[ ! $REPLY =~ ^[Nn]$ ]]; then
        echo "🚀 LANCEMENT MENU GITHUB..."
        exec ./GITHUB.sh
    else
        echo "⏸️ Menu principal reporté"
        echo ""
        echo "💡 COMMANDES MANUELLES:"
        echo "   ./GITHUB.sh          # Menu complet"
        echo "   ./SYNC_GITHUB.sh     # Sync directe"  
        echo "   cat GITHUB_GUIDE.md  # Guide détaillé"
    fi
else
    echo "❌ PROBLÈME CONFIGURATION DÉTECTÉ"
    echo ""
    echo "🔧 CONFIGURATION REQUISE:"
    echo ""
    echo "1) ⚙️ CONFIGURATION INITIALE"
    echo "   └─ ./SETUP_GITHUB.sh"
    echo "      Connecter projet à GitHub"
    echo ""
    echo "2) 📖 GUIDE COMPLET"
    echo "   └─ cat GITHUB_GUIDE.md"  
    echo "      Instructions détaillées"
    echo ""
    
    read -p "👉 Lancer configuration maintenant ? [Y/n] " -r
    echo ""
    
    if [[ ! $REPLY =~ ^[Nn]$ ]]; then
        echo "🚀 LANCEMENT CONFIGURATION..."
        exec ./SETUP_GITHUB.sh
    else
        echo "⏸️ Configuration reportée"
        echo ""
        echo "💡 LANCEZ QUAND PRÊT:"
        echo "   ./SETUP_GITHUB.sh    # Configuration"
        echo "   cat GITHUB_GUIDE.md  # Guide"
    fi
fi

echo ""
echo "=============================================="
echo "🐙 STUDIOSDB GITHUB - Support Intégré"
echo "=============================================="

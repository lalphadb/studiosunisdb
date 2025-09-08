#!/bin/bash
echo "================================================"
echo "🐙 STUDIOSDB - GESTION GITHUB COMPLÈTE"
echo "================================================"
cd /home/studiosdb/studiosunisdb

echo ""
echo "🎯 DIAGNOSTIC & SYNCHRONISATION GITHUB"
echo "   StudiosDB - Studios Unis St-Émile"
echo "   Date: $(date)"
echo ""

# Rendre scripts exécutables
chmod +x *.sh 2>/dev/null

echo "📋 ACTIONS DISPONIBLES:"
echo ""
echo "1) 🔍 ANALYSER état GitHub actuel"
echo "2) ⚙️ CONFIGURER GitHub (première fois)"
echo "3) 🔄 SYNCHRONISER avec GitHub"
echo "4) 📊 RAPPORT complet (analyse + sync si nécessaire)"
echo "5) ❌ Quitter"
echo ""

read -p "👉 Choisissez une action (1-5): " choice

case $choice in
    1)
        echo ""
        echo "🔍 ANALYSE GITHUB EN COURS..."
        echo "==============================="
        if [ -f "ANALYZE_GITHUB.sh" ]; then
            ./ANALYZE_GITHUB.sh
        else
            echo "❌ Script d'analyse manquant"
        fi
        ;;
        
    2)
        echo ""
        echo "⚙️ CONFIGURATION GITHUB..."
        echo "=========================="
        if [ -f "SETUP_GITHUB.sh" ]; then
            ./SETUP_GITHUB.sh
        else
            echo "❌ Script de configuration manquant"
        fi
        ;;
        
    3)
        echo ""
        echo "🔄 SYNCHRONISATION GITHUB..."
        echo "============================"
        if [ -f "SYNC_GITHUB.sh" ]; then
            ./SYNC_GITHUB.sh
        else
            echo "❌ Script de synchronisation manquant"
        fi
        ;;
        
    4)
        echo ""
        echo "📊 RAPPORT COMPLET GITHUB..."
        echo "============================"
        
        # Étape 1: Analyse
        echo "🔍 PHASE 1: ANALYSE"
        echo "-------------------"
        if [ -f "ANALYZE_GITHUB.sh" ]; then
            ./ANALYZE_GITHUB.sh > /tmp/github_analysis.log 2>&1
            
            # Extraire informations clés
            COMMITS_AHEAD=$(grep "Commits en avance:" /tmp/github_analysis.log | cut -d: -f2 | tr -d ' ')
            REMOTE_URL=$(grep "Remote origin:" /tmp/github_analysis.log | cut -d: -f2- | tr -d ' ')
            
            echo "📊 RÉSULTATS ANALYSE:"
            echo "   🔗 Remote: ${REMOTE_URL:-Non configuré}"
            echo "   📈 Commits non pushés: ${COMMITS_AHEAD:-0}"
            
            # Vérifier si synchronisation nécessaire
            if [ -n "$COMMITS_AHEAD" ] && [ "$COMMITS_AHEAD" -gt 0 ] 2>/dev/null; then
                echo "   ⚠️ Synchronisation requise"
                NEED_SYNC=true
            elif [ "$REMOTE_URL" = "Pas de remote origin" ]; then
                echo "   ⚠️ Configuration GitHub requise"
                NEED_CONFIG=true
            else
                echo "   ✅ GitHub synchronisé"
                NEED_SYNC=false
            fi
        else
            echo "❌ Script d'analyse manquant"
            exit 1
        fi
        
        echo ""
        
        # Étape 2: Action automatique si nécessaire
        if [ "$NEED_CONFIG" = true ]; then
            echo "🔧 PHASE 2: CONFIGURATION REQUISE"
            echo "--------------------------------"
            read -p "Configuration GitHub maintenant ? (y/N) " -r
            if [[ $REPLY =~ ^[Yy]$ ]]; then
                ./SETUP_GITHUB.sh
            else
                echo "⏸️ Configuration reportée"
                echo "💡 Lancez ./SETUP_GITHUB.sh quand prêt"
            fi
            
        elif [ "$NEED_SYNC" = true ]; then
            echo "🔄 PHASE 2: SYNCHRONISATION RECOMMANDÉE"
            echo "--------------------------------------"
            echo "💡 $COMMITS_AHEAD commits locaux non pushés"
            read -p "Synchroniser maintenant ? (y/N) " -r
            if [[ $REPLY =~ ^[Yy]$ ]]; then
                ./SYNC_GITHUB.sh
            else
                echo "⏸️ Synchronisation reportée"
                echo "💡 Lancez ./SYNC_GITHUB.sh plus tard"
            fi
        else
            echo "✅ PHASE 2: AUCUNE ACTION REQUISE"
            echo "--------------------------------"
            echo "GitHub déjà synchronisé"
        fi
        
        echo ""
        echo "📋 RÉSUMÉ RAPPORT:"
        echo "=================="
        cat /tmp/github_analysis.log | grep -E "(PROBLÈME IDENTIFIÉ|SOLUTION|✅|❌)" | head -10
        
        # Nettoyer fichier temporaire
        rm -f /tmp/github_analysis.log
        ;;
        
    5)
        echo ""
        echo "👋 Au revoir !"
        exit 0
        ;;
        
    *)
        echo ""
        echo "❌ Choix invalide"
        echo "💡 Relancez le script et choisissez 1-5"
        exit 1
        ;;
esac

echo ""
echo "================================================"
echo "✅ ACTION TERMINÉE"
echo "================================================"

echo ""
echo "📞 AUTRES SCRIPTS DISPONIBLES:"
echo "   ./STATUS.sh           # État projet"
echo "   ./BACKUP.sh           # Sauvegarde complète"
echo "   ./HELP.sh             # Aide générale"
echo ""

echo "🐙 GITHUB STUDIOSDB:"
echo "   ./GITHUB.sh           # Ce menu (re-lancer)"
echo ""

# Afficher statut final github si disponible
if [ -f ".sync_status" ]; then
    echo "📊 DERNIÈRE SYNC:"
    tail -1 .sync_status
    echo ""
fi

echo "================================================"

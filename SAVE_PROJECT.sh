#!/bin/bash
echo "================================================"
echo "🏆 STUDIOSDB - SAUVEGARDE PROJET COMPLET"  
echo "================================================"
echo ""
echo "🎯 SAUVEGARDE INTÉGRALE STUDIOSDB"
echo "   • Application Laravel complète"
echo "   • Base de données (structure + données)"  
echo "   • Documentation et scripts"
echo "   • Commit git avec changelog"
echo "   • État système complet"
echo ""

cd /home/studiosdb/studiosunisdb

# Rendre tous les scripts exécutables
echo "🔧 Préparation scripts..."
chmod +x *.sh 2>/dev/null

echo ""
echo "================================"
echo "🔍 ÉTAPE 1: VÉRIFICATIONS"
echo "================================"

if [ -f "CHECK_BEFORE_BACKUP.sh" ]; then
    ./CHECK_BEFORE_BACKUP.sh
    CHECK_RESULT=$?
    
    if [ $CHECK_RESULT -ne 0 ]; then
        echo ""
        echo "🚨 PRÉREQUIS NON REMPLIS"
        echo "❌ Sauvegarde annulée pour éviter les erreurs"
        echo ""
        echo "🔧 Corrigez les problèmes détectés et relancez"
        exit $CHECK_RESULT
    fi
else
    echo "⚠️ Script de vérification manquant, poursuite..."
fi

echo ""
echo "================================"  
echo "🚀 ÉTAPE 2: SAUVEGARDE"
echo "================================"

echo ""
read -p "🎯 Lancer la sauvegarde complète maintenant ? (Y/n) " -r
echo ""

if [[ ! $REPLY =~ ^[Nn]$ ]]; then
    echo "🚀 DÉMARRAGE SAUVEGARDE COMPLÈTE..."
    echo ""
    
    if [ -f "SAUVEGARDE_COMPLETE_PROJET.sh" ]; then
        ./SAUVEGARDE_COMPLETE_PROJET.sh
        BACKUP_RESULT=$?
        
        echo ""
        echo "================================"
        echo "🎯 ÉTAPE 3: RÉSULTATS"  
        echo "================================"
        
        if [ $BACKUP_RESULT -eq 0 ]; then
            echo ""
            echo "🎉 ✅ SAUVEGARDE COMPLÈTE RÉUSSIE !"
            echo ""
            
            if [ -f ".backup_status" ]; then
                echo "📊 STATUT:"
                cat .backup_status
                echo ""
            fi
            
            echo "📁 CONTENU SAUVEGARDÉ:"
            echo "   ✅ Code application complet"
            echo "   ✅ Base de données (structure + données)"
            echo "   ✅ Documentation projet"
            echo "   ✅ Scripts utilitaires"  
            echo "   ✅ Commit git documenté"
            echo "   ✅ État système"
            echo ""
            
            echo "🎯 PROJET STUDIOSDB:"
            echo "   📋 État: STABLE (3/6 modules terminés)"
            echo "   🏗️ Module Cours: 100% OPÉRATIONNEL"  
            echo "   🚀 Prêt pour: Module Utilisateurs (J4)"
            echo ""
            
            echo "================================================"
            echo "✨ SAUVEGARDE COMPLÈTE TERMINÉE AVEC SUCCÈS"
            echo "================================================"
            
        else
            echo ""
            echo "🚨 ❌ ERREUR DURANT LA SAUVEGARDE"
            echo ""
            echo "🔧 Actions suggérées:"
            echo "   - Vérifiez les permissions du système de fichiers"
            echo "   - Vérifiez l'espace disque disponible"  
            echo "   - Consultez les messages d'erreur ci-dessus"
            echo "   - Relancez après correction"
            echo ""
            exit $BACKUP_RESULT
        fi
    else
        echo "❌ Script SAUVEGARDE_COMPLETE_PROJET.sh manquant"
        exit 1
    fi
else
    echo ""
    echo "⏸️ SAUVEGARDE ANNULÉE PAR L'UTILISATEUR"
    echo ""
    echo "📞 Pour lancer plus tard:"
    echo "   ./SAVE_PROJECT.sh"
    echo ""
    echo "📊 Pour voir l'état actuel:"
    echo "   ./STATUS.sh"
    echo ""
fi

echo ""
echo "🎯 AUTRES ACTIONS DISPONIBLES:"
echo ""
echo "📊 ÉTAT RAPIDE:"
echo "   ./STATUS.sh"
echo ""  
echo "🔧 MODULE COURS:"
echo "   ./FIX_COMPLET_COURS.sh    # Corrections"
echo "   ./TEST_SIMULATION.sh      # Tests"
echo ""
echo "🌐 INTERFACE:"
echo "   php artisan serve --port=8001"
echo "   → http://127.0.0.1:8001"
echo ""

echo "================================================"
echo "📞 STUDIOSDB - Sauvegarde disponible 24/7"
echo "================================================"

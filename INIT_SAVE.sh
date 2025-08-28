#!/bin/bash
cd /home/studiosdb/studiosunisdb

echo "========================================="
echo "🏆 STUDIOSDB - SAUVEGARDE MODULE COURS"
echo "========================================="
echo ""

# Rendre tous les scripts exécutables
chmod +x *.sh

echo "📋 SCRIPTS DISPONIBLES :"
echo ""
echo "🔍 STATUT RAPIDE"  
echo "   ./STATUS.sh"
echo ""
echo "💾 SAUVEGARDE COMPLÈTE"
echo "   ./SAVE.sh"
echo ""
echo "🔧 CORRECTION MODULE COURS"
echo "   ./FIX_COMPLET_COURS.sh"
echo ""
echo "🧪 TEST INTERFACE"
echo "   php artisan serve --port=8001"
echo ""

echo "========================================="
echo "🎯 ACTION RECOMMANDÉE MAINTENANT :"
echo ""
echo "   ./SAVE.sh"
echo ""
echo "Cette commande va :"
echo "• Sauvegarder tous les fichiers"
echo "• Créer un commit git documenté"
echo "• Générer rapport complet"
echo "• Marquer Module Cours comme STABLE"
echo "========================================="
echo ""

read -p "🚀 Voulez-vous lancer la sauvegarde maintenant ? (y/N) " -r
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo ""
    echo "🚀 LANCEMENT SAUVEGARDE..."
    exec ./SAVE.sh
else
    echo ""
    echo "⏸️  Sauvegarde reportée."
    echo "📞 Lancez ./SAVE.sh quand vous serez prêt."
    echo "📊 Ou ./STATUS.sh pour voir l'état actuel."
fi

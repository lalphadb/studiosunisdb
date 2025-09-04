#!/bin/bash
echo "🚀 INITIALISATION SCRIPTS STUDIOSDB"
cd /home/studiosdb/studiosunisdb

echo ""
echo "🔧 Rendu des scripts exécutables..."
chmod +x *.sh

echo "✅ Scripts préparés"
echo ""

# Compter scripts disponibles
SCRIPT_COUNT=$(ls -1 *.sh 2>/dev/null | wc -l)
echo "📋 $SCRIPT_COUNT scripts disponibles"

echo ""
echo "================================================"
echo "🏆 STUDIOSDB - SCRIPTS PRÊTS"
echo "================================================"
echo ""
echo "🎯 ACTION PRINCIPALE:"
echo ""
echo "   ./BACKUP.sh"
echo "   └─ Sauvegarde complète projet"
echo ""
echo "📚 AUTRES ACTIONS:"
echo ""  
echo "   ./HELP.sh     # Liste complète scripts"
echo "   ./STATUS.sh   # État projet rapide"
echo ""
echo "================================================"
echo ""

# Auto-lancer l'aide si demandé
if [ "$1" = "--help" ] || [ "$1" = "-h" ]; then
    exec ./HELP.sh
else
    echo "💡 Lancez ./HELP.sh pour voir toutes les options"
    echo "🚀 Ou ./BACKUP.sh pour sauvegarde complète"
    echo ""
fi

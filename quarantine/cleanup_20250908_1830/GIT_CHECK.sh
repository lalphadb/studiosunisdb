#!/bin/bash
echo "🚀 DIAGNOSTIC RAPIDE GITHUB STUDIOSDB"
cd /home/studiosdb/studiosunisdb
chmod +x *.sh

echo ""
echo "📊 VÉRIFICATIONS ESSENTIELLES:"

# Git installé ?
if command -v git >/dev/null 2>&1; then
    echo "✅ Git installé"
else
    echo "❌ Git manquant"
    exit 1
fi

# Repository git ?
if [ -d ".git" ]; then
    echo "✅ Repository git présent"
else
    echo "❌ Pas de repository git"
    echo "💡 Lancez: ./SETUP_GITHUB.sh"
    exit 1
fi

# Remote configuré ?
REMOTE=$(git remote get-url origin 2>/dev/null || echo "NONE")
if [ "$REMOTE" != "NONE" ]; then
    echo "✅ Remote GitHub configuré"
    echo "   🔗 $REMOTE"
else
    echo "❌ Pas de remote GitHub"
    echo "💡 Lancez: ./SETUP_GITHUB.sh"
    exit 1
fi

# Commits locaux ?
COMMIT_COUNT=$(git rev-list --count HEAD 2>/dev/null || echo "0")
echo "📊 Commits locaux: $COMMIT_COUNT"

# Status
MODIFIED=$(git status --porcelain | wc -l)
echo "📝 Fichiers modifiés: $MODIFIED"

# Derniers commits
echo ""
echo "📋 DERNIERS COMMITS LOCAUX:"
git log --oneline -5 2>/dev/null

echo ""
echo "🎯 DIAGNOSTIC RAPIDE TERMINÉ"
echo ""
echo "🔧 POUR ANALYSE COMPLÈTE:"
echo "   ./ANALYZE_GITHUB.sh"
echo ""
echo "🔄 POUR SYNCHRONISATION:"
echo "   ./SYNC_GITHUB.sh"
echo ""
echo "🐙 POUR MENU COMPLET:"
echo "   ./GITHUB.sh"

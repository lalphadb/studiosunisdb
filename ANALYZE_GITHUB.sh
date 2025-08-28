#!/bin/bash
echo "=== ANALYSE GITHUB STUDIOSDB ==="
cd /home/studiosdb/studiosunisdb

echo "🔍 DIAGNOSTIC GIT & GITHUB"
echo "Date/Heure: $(date)"
echo "Répertoire: $(pwd)"
echo ""

echo "📋 1. ÉTAT REPOSITORY LOCAL"
echo "- Branche actuelle:"
git branch --show-current 2>/dev/null || echo "  Erreur: pas de git ou pas de branche"

echo ""
echo "- Toutes les branches locales:"
git branch -a 2>/dev/null || echo "  Erreur: git non initialisé"

echo ""
echo "- Statut git actuel:"
git status --porcelain 2>/dev/null | head -10

if [ $(git status --porcelain 2>/dev/null | wc -l) -gt 10 ]; then
    TOTAL_CHANGES=$(git status --porcelain 2>/dev/null | wc -l)
    echo "  ... ($TOTAL_CHANGES fichiers modifiés total)"
fi

echo ""
echo "📡 2. CONFIGURATION REMOTES"
echo "- Remotes configurés:"
git remote -v 2>/dev/null || echo "  Aucun remote configuré"

echo ""
echo "- URL GitHub actuel:"
GITHUB_URL=$(git remote get-url origin 2>/dev/null || echo "Pas de remote origin")
echo "  $GITHUB_URL"

echo ""
echo "📊 3. HISTORIQUE COMMITS LOCAUX"
echo "- 10 derniers commits locaux:"
git log --oneline -10 2>/dev/null || echo "  Erreur: pas d'historique git"

echo ""
echo "🔄 4. COMPARAISON LOCAL vs REMOTE"
echo "- Fetch remote info..."
git fetch --all 2>/dev/null || echo "  Erreur fetch (remote inaccessible?)"

echo ""
echo "- Commits en avance (LOCAL vs ORIGIN):"
if git rev-parse origin/main >/dev/null 2>&1; then
    AHEAD_COUNT=$(git rev-list --count origin/main..HEAD 2>/dev/null || echo "0")
    echo "  Commits en avance: $AHEAD_COUNT"
    
    if [ "$AHEAD_COUNT" -gt 0 ]; then
        echo "  Commits non pushés:"
        git log --oneline origin/main..HEAD 2>/dev/null | head -5
        if [ "$AHEAD_COUNT" -gt 5 ]; then
            echo "    ... et $((AHEAD_COUNT - 5)) autres commits"
        fi
    fi
else
    echo "  Remote origin/main introuvable"
fi

echo ""
echo "- Commits en retard (REMOTE vs LOCAL):"
if git rev-parse origin/main >/dev/null 2>&1; then
    BEHIND_COUNT=$(git rev-list --count HEAD..origin/main 2>/dev/null || echo "0")
    echo "  Commits en retard: $BEHIND_COUNT"
    
    if [ "$BEHIND_COUNT" -gt 0 ]; then
        echo "  Commits distants non récupérés:"
        git log --oneline HEAD..origin/main 2>/dev/null | head -3
    fi
else
    echo "  Remote origin/main introuvable"
fi

echo ""
echo "📅 5. DATES DERNIERS COMMITS"
echo "- Dernier commit local:"
git log -1 --format="%h - %s (%cr)" 2>/dev/null || echo "  Aucun commit local"

echo ""
echo "- Dernier commit remote (si disponible):"
if git rev-parse origin/main >/dev/null 2>&1; then
    git log origin/main -1 --format="%h - %s (%cr)" 2>/dev/null || echo "  Erreur lecture remote"
else
    echo "  Remote origin/main pas accessible"
fi

echo ""
echo "🔍 6. ANALYSE FICHIERS RÉCENTS"
echo "- Derniers fichiers modifiés (git tracked):"
git ls-files -z | xargs -0 -I {} stat -f "%m %N" {} 2>/dev/null | sort -nr | head -5 | while read timestamp file; do
    date -r $timestamp "+%Y-%m-%d %H:%M - $file"
done 2>/dev/null || echo "  Commande stat non supportée sur ce système"

echo ""
echo "- Fichiers dans staging area:"
git diff --cached --name-status 2>/dev/null || echo "  Rien en staging"

echo ""
echo "- Fichiers non trackés:"
git ls-files --others --exclude-standard 2>/dev/null | head -5

echo ""
echo "🚨 7. PROBLÈMES POTENTIELS"

# Vérifier .gitignore
if [ ! -f ".gitignore" ]; then
    echo "⚠️ Pas de .gitignore (risque de push fichiers sensibles)"
else
    echo "✅ .gitignore présent"
fi

# Vérifier si .env est tracké
if git ls-files | grep -q "^\.env$"; then
    echo "🚨 .env est tracké par git (SÉCURITÉ: contient mots de passe!)"
else
    echo "✅ .env non tracké (sécurité OK)"
fi

# Vérifier node_modules
if git ls-files | grep -q "node_modules/"; then
    echo "⚠️ node_modules/ partiellement tracké (peut causer problèmes)"
else
    echo "✅ node_modules/ non tracké"
fi

# Vérifier vendor
if git ls-files | grep -q "vendor/"; then
    echo "⚠️ vendor/ partiellement tracký (peut causer problèmes)"
else
    echo "✅ vendor/ non tracké"
fi

echo ""
echo "📋 8. RÉSUMÉ DIAGNOSTIC"

# Calculer état général
TOTAL_UNTRACKED=$(git ls-files --others --exclude-standard 2>/dev/null | wc -l)
TOTAL_MODIFIED=$(git diff --name-only 2>/dev/null | wc -l)
TOTAL_STAGED=$(git diff --cached --name-only 2>/dev/null | wc -l)

echo "📊 ÉTAT FICHIERS:"
echo "  - Non trackés: $TOTAL_UNTRACKED"
echo "  - Modifiés: $TOTAL_MODIFIED"  
echo "  - En staging: $TOTAL_STAGED"
echo ""

if [ -n "$AHEAD_COUNT" ] && [ "$AHEAD_COUNT" -gt 0 ]; then
    echo "🎯 PROBLÈME IDENTIFIÉ:"
    echo "  ❌ $AHEAD_COUNT commits locaux NON PUSHÉS sur GitHub"
    echo "  ↳ Cause probable: pas de 'git push' depuis 3 semaines"
    echo ""
    echo "💡 SOLUTION:"
    echo "  git push origin main"
    echo ""
elif [ "$GITHUB_URL" = "Pas de remote origin" ]; then
    echo "🎯 PROBLÈME IDENTIFIÉ:"
    echo "  ❌ Aucun remote GitHub configuré"
    echo "  ↳ Repository local pas connecté à GitHub"
    echo ""
    echo "💡 SOLUTION:"
    echo "  git remote add origin https://github.com/VOTRE_USERNAME/REPO_NAME.git"
    echo "  git push -u origin main"
    echo ""
else
    echo "✅ Configuration git semble correcte"
    echo "💡 Vérifiez manuellement votre GitHub pour voir les commits"
fi

echo "=== FIN ANALYSE ==="

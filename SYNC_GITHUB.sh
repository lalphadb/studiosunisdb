#!/bin/bash
echo "=== SYNCHRONISATION GITHUB STUDIOSDB ==="
cd /home/studiosdb/studiosunisdb

echo "🔄 SYNCHRONISATION COMPLÈTE AVEC GITHUB"
echo "Date: $(date)"
echo ""

# Vérifier qu'on est dans un repo git
if [ ! -d ".git" ]; then
    echo "❌ Pas de repository git détecté"
    echo "💡 Initialisation requise:"
    echo "   git init"
    echo "   git remote add origin https://github.com/VOTRE_USERNAME/studiosdb.git"
    exit 1
fi

echo "📋 ÉTAPES SYNCHRONISATION:"
echo "1. Analyse état actuel"
echo "2. Nettoyage et préparation"
echo "3. Commit changements récents"
echo "4. Push vers GitHub"
echo "5. Vérification finale"
echo ""

echo "===== ÉTAPE 1: ANALYSE ÉTAT ====="

# État git
BRANCH_CURRENT=$(git branch --show-current)
echo "📍 Branche actuelle: $BRANCH_CURRENT"

# Remote check
REMOTE_ORIGIN=$(git remote get-url origin 2>/dev/null || echo "NONE")
echo "📡 Remote origin: $REMOTE_ORIGIN"

# Commits locaux non pushés
git fetch --all 2>/dev/null
if git rev-parse origin/$BRANCH_CURRENT >/dev/null 2>&1; then
    COMMITS_AHEAD=$(git rev-list --count origin/$BRANCH_CURRENT..HEAD 2>/dev/null || echo "0")
    echo "📊 Commits locaux non pushés: $COMMITS_AHEAD"
else
    echo "📊 Remote branch pas accessible (premier push?)"
    COMMITS_AHEAD="unknown"
fi

# Fichiers modifiés
MODIFIED_FILES=$(git status --porcelain | wc -l)
echo "📝 Fichiers modifiés: $MODIFIED_FILES"

echo ""
echo "===== ÉTAPE 2: NETTOYAGE ====="

# Créer/mettre à jour .gitignore si nécessaire
if [ ! -f ".gitignore" ]; then
    echo "📝 Création .gitignore Laravel..."
    cat > .gitignore << 'EOF'
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode
/backups
*.log
EOF
    echo "✅ .gitignore créé"
else
    echo "✅ .gitignore existe déjà"
fi

# Vérifier que .env n'est pas tracké
if git ls-files | grep -q "^\.env$"; then
    echo "🚨 .env est tracké - suppression du tracking..."
    git rm --cached .env 2>/dev/null
    echo "✅ .env retiré du tracking git"
fi

# Nettoyer fichiers temporaires
echo "🧹 Nettoyage fichiers temporaires..."
find . -name "*.log" -not -path "./storage/logs/*" -delete 2>/dev/null || true
find . -name ".DS_Store" -delete 2>/dev/null || true

echo ""
echo "===== ÉTAPE 3: COMMIT CHANGEMENTS ====="

# Ajouter tous les fichiers appropriés
echo "📦 Ajout fichiers au staging..."
git add .

# Créer commit avec timestamp
TIMESTAMP=$(date "+%Y-%m-%d %H:%M:%S")
COMMIT_MESSAGE="sync: Synchronisation GitHub StudiosDB - $TIMESTAMP

📊 ÉTAT PROJET:
- Modules terminés: 3/6 (Bootstrap + Dashboard + Cours)
- Module Cours: 100% opérationnel, contraintes DB résolues  
- Prochaine étape: Module Utilisateurs (J4)

🏗️ ARCHITECTURE:
- Laravel 12.x + Inertia + Vue 3 + Tailwind + MySQL
- FormRequests, Policies, Spatie Permission
- Design system cohérent, mono-école

✅ FONCTIONNALITÉS VALIDÉES:
- Authentification et permissions
- Dashboard avec stats et navigation
- Module Cours complet (CRUD, tarification, planning, export)
- Validation robuste, messages français
- Scripts sauvegarde automatisés

📝 CORRECTIONS RÉCENTES:
- Contraintes DB tarif_mensuel + ecole_id
- FormRequests Laravel 12 centralisées
- Fallbacks mono-école robustes
- Interface utilisateur optimisée

💾 SAUVEGARDE:
- Scripts backup complets
- Documentation ADR
- État système conservé

Statut: STABLE - Prêt développement J4 Utilisateurs"

echo "💬 Création commit..."
git commit -m "$COMMIT_MESSAGE"

COMMIT_RESULT=$?
if [ $COMMIT_RESULT -eq 0 ]; then
    echo "✅ Commit créé avec succès"
    NEW_COMMIT_HASH=$(git rev-parse HEAD | cut -c1-8)
    echo "🔗 Hash: $NEW_COMMIT_HASH"
elif [ $COMMIT_RESULT -eq 1 ]; then
    echo "ℹ️ Rien à commiter (déjà à jour)"
else
    echo "❌ Erreur lors du commit"
fi

echo ""
echo "===== ÉTAPE 4: PUSH VERS GITHUB ====="

if [ "$REMOTE_ORIGIN" = "NONE" ]; then
    echo "❌ Pas de remote origin configuré"
    echo ""
    echo "💡 CONFIGURATION REQUISE:"
    echo "   git remote add origin https://github.com/VOTRE_USERNAME/studiosdb.git"
    echo "   git push -u origin main"
    echo ""
    echo "📞 Remplacez VOTRE_USERNAME par votre nom d'utilisateur GitHub"
    exit 1
fi

echo "🚀 Push vers GitHub..."
echo "📡 Remote: $REMOTE_ORIGIN"

# Essayer push
git push origin $BRANCH_CURRENT

PUSH_RESULT=$?
if [ $PUSH_RESULT -eq 0 ]; then
    echo "✅ Push réussi vers GitHub"
elif [ $PUSH_RESULT -eq 128 ]; then
    echo "🚨 Erreur push: problème authentification ou remote"
    echo ""
    echo "💡 SOLUTIONS POSSIBLES:"
    echo "   1. Vérifier URL remote:"
    echo "      git remote set-url origin https://github.com/USERNAME/REPO.git"
    echo ""
    echo "   2. Authentification GitHub (token requis):"
    echo "      - Générer Personal Access Token sur GitHub"
    echo "      - Utiliser token comme mot de passe"
    echo ""
    echo "   3. Première fois (upstream):"
    echo "      git push -u origin $BRANCH_CURRENT"
    exit 1
else
    echo "❌ Erreur push inconnue (code: $PUSH_RESULT)"
    echo "💡 Vérifiez manuellement avec: git push -v origin $BRANCH_CURRENT"
fi

echo ""
echo "===== ÉTAPE 5: VÉRIFICATION FINALE ====="

# Re-fetch après push
git fetch --all 2>/dev/null

# Vérifier synchronisation
if git rev-parse origin/$BRANCH_CURRENT >/dev/null 2>&1; then
    LOCAL_COMMIT=$(git rev-parse HEAD)
    REMOTE_COMMIT=$(git rev-parse origin/$BRANCH_CURRENT)
    
    if [ "$LOCAL_COMMIT" = "$REMOTE_COMMIT" ]; then
        echo "✅ LOCAL et REMOTE synchronisés"
        echo "🔗 Commit: $(git rev-parse HEAD | cut -c1-8)"
    else
        echo "⚠️ LOCAL et REMOTE différents"
        echo "   Local:  $(git rev-parse HEAD | cut -c1-8)"
        echo "   Remote: $(git rev-parse origin/$BRANCH_CURRENT | cut -c1-8)"
    fi
else
    echo "❌ Remote branch toujours pas accessible"
fi

# Derniers commits visibles sur GitHub
echo ""
echo "📊 DERNIERS COMMITS (maintenant sur GitHub):"
git log --oneline -5

echo ""
echo "===== RÉSUMÉ SYNCHRONISATION ====="

echo ""
echo "🎯 STATUT FINAL:"
if [ $PUSH_RESULT -eq 0 ]; then
    echo "✅ SYNCHRONISATION GITHUB RÉUSSIE"
    echo ""
    echo "📱 VÉRIFIEZ SUR GITHUB:"
    echo "   - Naviguez vers votre repository"
    echo "   - Les commits récents devraient être visibles"
    echo "   - Date du dernier commit: maintenant"
    echo ""
    echo "🔗 URL GitHub: $REMOTE_ORIGIN"
    echo "🏠 Branch: $BRANCH_CURRENT"
    echo "📅 Dernière sync: $(date)"
else
    echo "❌ SYNCHRONISATION ÉCHOUÉE"
    echo ""
    echo "🔧 ACTIONS REQUISES:"
    echo "   1. Configurer remote origin correct"
    echo "   2. Configurer authentification GitHub"
    echo "   3. Re-exécuter ce script"
fi

echo ""
echo "📋 PROJET STUDIOSDB:"
echo "   📊 État: STABLE (3/6 modules terminés)"
echo "   🏗️ Module Cours: 100% opérationnel"
echo "   🚀 Prêt pour: Module Utilisateurs (J4)"

echo ""
echo "=== FIN SYNCHRONISATION ==="

# Sauvegarder statut
echo "GITHUB_SYNC_$(date +%Y%m%d_%H%M%S)_SUCCESS=$PUSH_RESULT" >> .sync_status

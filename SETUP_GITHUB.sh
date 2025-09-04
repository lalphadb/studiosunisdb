#!/bin/bash
echo "=== CONFIGURATION GITHUB STUDIOSDB ==="
cd /home/studiosdb/studiosunisdb

echo "⚙️ ASSISTANT CONFIGURATION GITHUB"
echo "Date: $(date)"
echo ""

echo "📋 CET ASSISTANT VA:"
echo "1. Vérifier configuration git locale"
echo "2. Configurer remote GitHub"
echo "3. Préparer première synchronisation"
echo "4. Donner instructions authentification"
echo ""

read -p "🚀 Continuer ? (y/N) " -r
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Configuration annulée"
    exit 0
fi

echo ""
echo "===== ÉTAPE 1: VÉRIFICATION GIT LOCAL ====="

# Vérifier git installé
if ! command -v git >/dev/null 2>&1; then
    echo "❌ Git pas installé"
    echo "💡 Installation requise: sudo apt install git"
    exit 1
fi

echo "✅ Git installé: $(git --version)"

# Vérifier repository initialisé
if [ ! -d ".git" ]; then
    echo "📝 Initialisation repository git..."
    git init
    echo "✅ Repository git initialisé"
else
    echo "✅ Repository git existe"
fi

# Configuration utilisateur git
GIT_USER=$(git config --global user.name 2>/dev/null)
GIT_EMAIL=$(git config --global user.email 2>/dev/null)

if [ -z "$GIT_USER" ]; then
    echo ""
    echo "⚙️ Configuration utilisateur Git requise"
    read -p "📝 Votre nom complet: " USER_NAME
    git config --global user.name "$USER_NAME"
    echo "✅ Nom configuré: $USER_NAME"
else
    echo "✅ Utilisateur git: $GIT_USER"
fi

if [ -z "$GIT_EMAIL" ]; then
    echo ""
    read -p "📧 Votre email GitHub: " USER_EMAIL
    git config --global user.email "$USER_EMAIL"
    echo "✅ Email configuré: $USER_EMAIL"
else
    echo "✅ Email git: $GIT_EMAIL"
fi

echo ""
echo "===== ÉTAPE 2: CONFIGURATION REMOTE GITHUB ====="

# Vérifier remote existant
CURRENT_REMOTE=$(git remote get-url origin 2>/dev/null || echo "NONE")

if [ "$CURRENT_REMOTE" != "NONE" ]; then
    echo "📡 Remote origin existant: $CURRENT_REMOTE"
    read -p "🔄 Voulez-vous le changer ? (y/N) " -r
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        CHANGE_REMOTE=true
    else
        CHANGE_REMOTE=false
    fi
else
    echo "📡 Aucun remote origin configuré"
    CHANGE_REMOTE=true
fi

if [ "$CHANGE_REMOTE" = true ]; then
    echo ""
    echo "🔗 CONFIGURATION REMOTE GITHUB"
    echo ""
    echo "💡 Formats URL GitHub valides:"
    echo "   HTTPS: https://github.com/USERNAME/REPO_NAME.git"
    echo "   SSH:   git@github.com:USERNAME/REPO_NAME.git"
    echo ""
    
    read -p "📝 URL complète de votre repository GitHub: " GITHUB_URL
    
    if [[ $GITHUB_URL =~ ^https://github\.com/.+/.+\.git$ ]] || [[ $GITHUB_URL =~ ^git@github\.com:.+/.+\.git$ ]]; then
        if [ "$CURRENT_REMOTE" != "NONE" ]; then
            git remote set-url origin "$GITHUB_URL"
            echo "✅ Remote origin modifié"
        else
            git remote add origin "$GITHUB_URL"
            echo "✅ Remote origin ajouté"
        fi
        echo "🔗 URL: $GITHUB_URL"
    else
        echo "❌ Format URL invalide"
        echo "💡 Exemple correct: https://github.com/monusername/studiosdb.git"
        exit 1
    fi
fi

echo ""
echo "===== ÉTAPE 3: PRÉPARATION SYNCHRONISATION ====="

# Créer .gitignore si absent
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
    git add .gitignore
    echo "✅ .gitignore créé et ajouté"
fi

# Vérifier branche principale
CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" != "main" ]; then
    echo "🔄 Renommage branche vers 'main'..."
    git branch -M main
    echo "✅ Branche renommée en 'main'"
fi

echo "✅ Branche principale: main"

echo ""
echo "===== ÉTAPE 4: INSTRUCTIONS AUTHENTIFICATION ====="

GITHUB_URL=$(git remote get-url origin 2>/dev/null)

if [[ $GITHUB_URL =~ ^https:// ]]; then
    echo "🔐 AUTHENTIFICATION HTTPS REQUISE"
    echo ""
    echo "📋 ÉTAPES REQUISES SUR GITHUB.COM:"
    echo ""
    echo "1. 👤 Connectez-vous sur github.com"
    echo "2. ⚙️ Settings > Developer settings > Personal access tokens > Tokens (classic)"
    echo "3. 🆕 Generate new token (classic)"
    echo "4. 📝 Note: 'StudiosDB Token'"
    echo "5. ⏱️ Expiration: 90 days (ou plus)"
    echo "6. ✅ Sélectionnez scopes:"
    echo "   • repo (accès complet aux repositories)"
    echo "   • workflow (pour GitHub Actions si utilisé)"
    echo "7. 🎯 Generate token"
    echo "8. 📋 COPIEZ le token (il ne sera plus visible!)"
    echo ""
    echo "💾 LORS DU PUSH:"
    echo "   Username: votre_nom_utilisateur_github"
    echo "   Password: COLLEZ_LE_TOKEN_GÉNÉRÉ"
    echo ""
elif [[ $GITHUB_URL =~ ^git@github\.com ]]; then
    echo "🔐 AUTHENTIFICATION SSH REQUISE"
    echo ""
    echo "📋 CONFIGURATION CLÉ SSH:"
    echo ""
    echo "1. 🔑 Générer clé SSH (si pas existante):"
    echo "   ssh-keygen -t ed25519 -C 'votre-email@example.com'"
    echo ""
    echo "2. 📋 Copier clé publique:"
    echo "   cat ~/.ssh/id_ed25519.pub"
    echo ""
    echo "3. 🌐 Sur github.com:"
    echo "   Settings > SSH and GPG keys > New SSH key"
    echo "   Coller la clé publique"
    echo ""
    echo "4. 🧪 Tester connexion:"
    echo "   ssh -T git@github.com"
    echo ""
fi

echo ""
echo "===== RÉSUMÉ CONFIGURATION ====="

echo ""
echo "✅ CONFIGURATION TERMINÉE"
echo ""
echo "📊 ÉTAT FINAL:"
echo "   🔗 Remote: $(git remote get-url origin)"
echo "   🏠 Branch: $(git branch --show-current)"
echo "   👤 User: $(git config --global user.name)"
echo "   📧 Email: $(git config --global user.email)"
echo ""

echo "🚀 PROCHAINES ÉTAPES:"
echo ""
echo "1. 📱 Créer repository sur github.com (même nom que URL)"
echo "2. 🔐 Configurer authentification (voir instructions ci-dessus)"
echo "3. 🔄 Synchroniser avec:"
echo "   ./SYNC_GITHUB.sh"
echo ""

echo "💡 SI PREMIÈRE FOIS:"
echo "   git push -u origin main"
echo ""

echo "🎯 VÉRIFICATION:"
echo "   Après push, vérifiez sur github.com que les commits apparaissent"

echo ""
echo "=== CONFIGURATION GITHUB TERMINÉE ==="

# Sauvegarder configuration
cat > .github_config << EOF
CONFIGURED_AT=$(date)
REMOTE_URL=$(git remote get-url origin)
BRANCH=main
USER_NAME=$(git config --global user.name)
USER_EMAIL=$(git config --global user.email)
EOF

echo "📄 Configuration sauvegardée dans .github_config"

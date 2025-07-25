#!/bin/bash

# 🥋 StudiosDB v5 Pro - Script de Restauration Automatique
# Restaure complètement le projet à partir d'une archive tar.gz

echo "🚀 StudiosDB v5 Pro - Restauration Automatique"
echo "=============================================="

# Vérifier si un fichier de backup est fourni
if [ $# -eq 0 ]; then
    echo "❌ Erreur: Aucun fichier de backup spécifié"
    echo "📋 Usage: $0 <fichier_backup.tar.gz>"
    echo "📋 Exemple: $0 studiosdb_v5_pro_backup_20250725_125923.tar.gz"
    exit 1
fi

BACKUP_FILE="$1"
RESTORE_DIR=$(basename "$BACKUP_FILE" .tar.gz)

# Vérifier que le fichier existe
if [ ! -f "$BACKUP_FILE" ]; then
    echo "❌ Erreur: Fichier de backup '$BACKUP_FILE' introuvable"
    exit 1
fi

echo "📁 Fichier de backup: $BACKUP_FILE"
echo "📂 Répertoire de restauration: $RESTORE_DIR"
echo ""

# 1. Extraction de l'archive
echo "📦 Extraction de l'archive..."
tar -xzf "$BACKUP_FILE"

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de l'extraction"
    exit 1
fi

cd "$RESTORE_DIR"

echo "✅ Archive extraite avec succès"
echo ""

# 2. Installation des dépendances PHP
echo "🔧 Installation des dépendances PHP (Composer)..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader
    echo "✅ Dépendances PHP installées"
else
    echo "⚠️  Composer non trouvé. Installez Composer puis exécutez:"
    echo "   composer install --optimize-autoloader"
fi
echo ""

# 3. Installation des dépendances JavaScript
echo "📦 Installation des dépendances JavaScript (NPM)..."
if command -v npm &> /dev/null; then
    npm install
    echo "✅ Dépendances JavaScript installées"
else
    echo "⚠️  NPM non trouvé. Installez Node.js puis exécutez:"
    echo "   npm install"
fi
echo ""

# 4. Configuration de l'environnement
echo "⚙️  Configuration de l'environnement..."
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "✅ Fichier .env créé à partir de .env.example"
    else
        echo "⚠️  Fichier .env.example introuvable"
        echo "   Créez manuellement votre fichier .env"
    fi
else
    echo "✅ Fichier .env déjà présent"
fi

# 5. Génération de la clé d'application
echo ""
echo "🔑 Génération de la clé d'application Laravel..."
if command -v php &> /dev/null; then
    php artisan key:generate --force
    echo "✅ Clé d'application générée"
else
    echo "⚠️  PHP non trouvé. Exécutez manuellement:"
    echo "   php artisan key:generate"
fi

# 6. Configuration des permissions
echo ""
echo "🔒 Configuration des permissions..."
chmod -R 755 .
chmod -R 775 storage bootstrap/cache 2>/dev/null
echo "✅ Permissions configurées"

# 7. Création du lien symbolique storage
echo ""
echo "🔗 Création du lien symbolique storage..."
if command -v php &> /dev/null; then
    php artisan storage:link
    echo "✅ Lien symbolique créé"
else
    echo "⚠️  Exécutez manuellement: php artisan storage:link"
fi

# 8. Build des assets
echo ""
echo "🎨 Compilation des assets frontend..."
if command -v npm &> /dev/null; then
    npm run build
    echo "✅ Assets compilés"
else
    echo "⚠️  Exécutez manuellement: npm run build"
fi

echo ""
echo "🎉 RESTAURATION TERMINÉE AVEC SUCCÈS!"
echo "======================================"
echo ""
echo "📋 ÉTAPES SUIVANTES MANUELLES:"
echo "1. 🗄️  Configurer la base de données dans .env:"
echo "   DB_DATABASE=studiosdb_v5_pro"
echo "   DB_USERNAME=votre_utilisateur"
echo "   DB_PASSWORD=votre_mot_de_passe"
echo ""
echo "2. 🔄 Exécuter les migrations:"
echo "   php artisan migrate"
echo ""
echo "3. 🌱 Exécuter les seeders (optionnel):"
echo "   php artisan db:seed"
echo ""
echo "4. 🚀 Démarrer le serveur de développement:"
echo "   php artisan serve"
echo ""
echo "🌐 Votre application sera accessible sur:"
echo "   http://localhost:8000"
echo ""
echo "👤 Compte de test par défaut:"
echo "   Email: louis@4lb.ca"
echo "   Mot de passe: password123"
echo ""
echo "🥋 StudiosDB v5 Pro restauré et prêt! 🚀"

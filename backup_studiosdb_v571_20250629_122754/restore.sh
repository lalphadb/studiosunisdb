#!/bin/bash
echo "🔄 Restauration StudiosDB v5.7.1..."

# Vérifier les prérequis
if [ ! -f "database_studiosdb_v571.sql" ]; then
    echo "❌ Fichier database_studiosdb_v571.sql manquant"
    exit 1
fi

if [ ! -f "studiosdb_files_v571.tar.gz" ]; then
    echo "❌ Fichier studiosdb_files_v571.tar.gz manquant"
    exit 1
fi

# Restaurer les fichiers
echo "📁 Extraction des fichiers..."
tar -xzf studiosdb_files_v571.tar.gz

# Restaurer la configuration
echo "⚙️ Restauration configuration..."
cp env_backup .env

# Restaurer les dépendances
echo "📦 Installation dépendances..."
composer install --no-dev --optimize-autoloader
npm install

# Restaurer la base de données
echo "🗄️ Restauration base de données..."
echo "Entrez le mot de passe MySQL root (LkmP0km1) :"
mysql -u root -p studiosdb < database_studiosdb_v571.sql

# Finalisation
echo "🎨 Compilation assets..."
npm run build

echo "🔧 Cache Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ StudiosDB v5.7.1 restauré avec succès !"
echo "🌐 Accès : http://localhost:8001"
echo "👤 Test : lalpha@4lb.ca / password123"

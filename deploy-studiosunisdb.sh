#!/bin/bash

# =================================================================
# 🥋 STUDIOSUNISDB v3.9.0.0 - SCRIPT DE DÉPLOIEMENT PROFESSIONNEL
# Basé sur l'environnement de développement fonctionnel
# =================================================================

set -e  # Arrêter en cas d'erreur

# Variables de configuration
DEPLOYMENT_DIR="/var/www/html/studiosunisdb"
SOURCE_DIR="/home/studiosdb/studiosunisdb"
DB_NAME="studiosunisdb_prod"
DB_USER="studiosunisdb_user"
DB_PASS="StudiosUnis2025!SecureProd"
DOMAIN="4lb.ca"

echo "🚀 DÉPLOIEMENT STUDIOSUNISDB v3.9.0.0"
echo "======================================"
echo "Source: $SOURCE_DIR"
echo "Destination: $DEPLOYMENT_DIR"
echo "Base: $DB_NAME"
echo ""

# 1. VÉRIFICATIONS PRÉALABLES
echo "📋 1. Vérifications système..."
command -v git >/dev/null 2>&1 || { echo "❌ Git requis"; exit 1; }
command -v composer >/dev/null 2>&1 || { echo "❌ Composer requis"; exit 1; }
command -v npm >/dev/null 2>&1 || { echo "❌ Node.js/npm requis"; exit 1; }
command -v php >/dev/null 2>&1 || { echo "❌ PHP requis"; exit 1; }
command -v mysql >/dev/null 2>&1 || { echo "❌ MySQL requis"; exit 1; }

# Vérifier que l'environnement source existe
if [ ! -d "$SOURCE_DIR" ]; then
    echo "❌ Répertoire source $SOURCE_DIR introuvable"
    exit 1
fi

echo "✅ Vérifications système réussies"

# 2. NETTOYAGE ET PRÉPARATION
echo ""
echo "🧹 2. Nettoyage et préparation..."
sudo rm -rf $DEPLOYMENT_DIR
sudo mkdir -p $DEPLOYMENT_DIR
sudo chown $USER:www-data $DEPLOYMENT_DIR
echo "✅ Répertoire de déploiement préparé"

# 3. COPIE DU PROJET FONCTIONNEL
echo ""
echo "📥 3. Copie du projet depuis l'environnement fonctionnel..."
cp -r $SOURCE_DIR/* $DEPLOYMENT_DIR/
cp $SOURCE_DIR/.env.example $DEPLOYMENT_DIR/
cp $SOURCE_DIR/.gitignore $DEPLOYMENT_DIR/

# Nettoyer les fichiers de dev
rm -rf $DEPLOYMENT_DIR/node_modules
rm -rf $DEPLOYMENT_DIR/vendor
rm -f $DEPLOYMENT_DIR/.env

echo "✅ Projet copié"

# 4. CONFIGURATION PRODUCTION
echo ""
echo "⚙️ 4. Configuration production..."
cd $DEPLOYMENT_DIR

# Créer .env production
cat > .env << EOF
APP_NAME="StudiosUnisDB"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=America/Montreal
APP_URL=https://$DOMAIN

LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASS

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=admin@$DOMAIN
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@$DOMAIN
MAIL_FROM_NAME="StudiosUnisDB Production"

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=$DOMAIN
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

SANCTUM_STATEFUL_DOMAINS=$DOMAIN,www.$DOMAIN
VITE_APP_NAME="\${APP_NAME}"

STUDIOSUNISDB_VERSION="v3.9.0.0"
STUDIOSUNISDB_ENV="production"
EOF

echo "✅ Configuration .env créée"

# 5. INSTALLATION DÉPENDANCES
echo ""
echo "📦 5. Installation des dépendances..."
composer install --optimize-autoloader --no-dev --quiet
npm install --silent
npm run build --silent
echo "✅ Dépendances installées"

# 6. GÉNÉRATION CLÉ
echo ""
echo "🔑 6. Génération clé application..."
php artisan key:generate --force
echo "✅ Clé générée"

# 7. BASE DE DONNÉES
echo ""
echo "🗄️ 7. Configuration base de données..."

# Supprimer et recréer la base
mysql -u root -pLkmP0km1 << EOF
DROP DATABASE IF EXISTS $DB_NAME;
CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DROP USER IF EXISTS '$DB_USER'@'localhost';
CREATE USER '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF

echo "✅ Base de données créée"

# 8. IMPORT DU SCHÉMA ET DONNÉES DE RÉFÉRENCE
echo ""
echo "📊 8. Import du schéma et données..."

# Importer le schéma depuis l'environnement fonctionnel
mysql -u $DB_USER -p$DB_PASS $DB_NAME < $SOURCE_DIR/schema_working.sql

# Importer les données de référence
mysql -u $DB_USER -p$DB_PASS $DB_NAME < $SOURCE_DIR/data_reference.sql

echo "✅ Schéma et données importés"

# 9. CRÉATION DU PREMIER SUPERADMIN
echo ""
echo "👤 9. Création du superadmin..."

php artisan tinker --execute="
use App\Models\User;
use App\Models\Ecole;
use Spatie\Permission\Models\Role;

try {
    \$user = User::create([
        'name' => 'Administrateur Principal StudiosUnisDB',
        'email' => 'admin@studiosunisdb.com',
        'password' => bcrypt('StudiosUnisDB2025!Admin'),
        'email_verified_at' => now(),
        'ecole_id' => 1,
        'telephone' => '418-555-0001',
        'statut' => 'actif'
    ]);
    
    \$superadminRole = Role::where('name', 'superadmin')->first();
    if (\$superadminRole) {
        \$user->assignRole('superadmin');
        echo 'Superadmin créé: admin@studiosunisdb.com / StudiosUnisDB2025!Admin' . PHP_EOL;
    } else {
        echo 'Erreur: Rôle superadmin introuvable' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Erreur création superadmin: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "✅ Superadmin configuré"

# 10. PERMISSIONS PRODUCTION
echo ""
echo "🔒 10. Configuration permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache public
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 755 public
sudo chmod 600 .env
sudo chown $USER:www-data .env

echo "✅ Permissions configurées"

# 11. OPTIMISATIONS PRODUCTION
echo ""
echo "⚡ 11. Optimisations production..."
php artisan config:cache --quiet
php artisan route:cache --quiet
php artisan view:cache --quiet
php artisan optimize --quiet

echo "✅ Optimisations appliquées"

# 12. VÉRIFICATIONS FINALES
echo ""
echo "🔍 12. Vérifications finales..."

# Test connexion DB
php artisan tinker --execute="
try {
    \$count = DB::table('ecoles')->count();
    echo 'DB OK - ' . \$count . ' écoles trouvées' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Erreur DB: ' . \$e->getMessage() . PHP_EOL;
}
"

# Test permissions
if [ -w "storage/logs" ]; then
    echo "✅ Permissions storage OK"
else
    echo "❌ Problème permissions storage"
fi

# Test .env
if php artisan config:show app.name | grep -q "StudiosUnisDB"; then
    echo "✅ Configuration .env OK"
else
    echo "❌ Problème configuration .env"
fi

echo ""
echo "🎉 DÉPLOIEMENT TERMINÉ AVEC SUCCÈS !"
echo "======================================"
echo "📍 Application: $DEPLOYMENT_DIR"
echo "🗄️ Base de données: $DB_NAME"
echo "👤 Superadmin: admin@studiosunisdb.com"
echo "🔑 Mot de passe: StudiosUnisDB2025!Admin"
echo "🌐 URL: https://$DOMAIN"
echo ""
echo "📋 Prochaines étapes:"
echo "   • Configurer Nginx/Apache pour pointer vers $DEPLOYMENT_DIR/public"
echo "   • Configurer SSL/TLS"
echo "   • Tester l'accès web"
echo ""

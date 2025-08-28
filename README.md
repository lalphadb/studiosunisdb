# StudiosDB - Système de Gestion d'École de Karaté

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)
![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)
![License](https://img.shields.io/badge/License-Proprietary-yellow.svg)

StudiosDB est un système de gestion moderne et complet pour écoles de karaté, développé avec Laravel 12, Inertia.js et Vue 3. Il offre une interface intuitive pour gérer membres, cours, présences, paiements et progression des ceintures.

## ✨ Fonctionnalités

### 🎯 Modules Disponibles
- **Dashboard Analytics** - Statistiques temps réel avec design responsive
- **Gestion Cours** - Planning, instructeurs, niveaux avec actions hover
- **Gestion Membres** - CRUD complet, photos, liens familiaux, progression ceintures
- **Système Utilisateurs** - Rôles granulaires, sécurité par école
- **Présences** - Suivi assiduité avec exports
- **Paiements** - Facturation, relances, rapports financiers
- **Inscription Self-Service** - Workflow multi-étapes sécurisé

### 🔐 Sécurité & Conformité
- **Multi-écoles** - Isolation complète des données par école
- **Rôles & Permissions** - Système granulaire (superadmin, admin_ecole, instructeur, membre)
- **Loi 25 (RGPD)** - Gestion consentements, exports, droit à l'oubli
- **Rate Limiting** - Protection DDoS sur formulaires publics
- **Audit Trails** - Journalisation des actions sensibles

### 🎨 Interface Utilisateur
- **Dark Mode** - Design moderne avec gradients et glassmorphism
- **Responsive** - Optimisé mobile, tablet, desktop
- **Hover Actions** - UX cohérente avec actions au survol
- **Accessibilité** - ARIA labels, navigation clavier
- **Performance** - SPA avec Inertia.js, lazy loading

## 🚀 Installation

### Prérequis
```bash
- PHP 8.3+ avec extensions : BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Composer 2.6+
- Node.js 18+ et NPM 9+
- MySQL 8.0+ ou MariaDB 10.6+
- Git
```

### 1. Clonage et Installation

```bash
# Cloner le repository
git clone https://github.com/votre-org/studiosdb.git
cd studiosdb

# Installer les dépendances PHP
composer install --optimize-autoloader

# Installer les dépendances NPM
npm install

# Copier le fichier d'environnement
cp .env.example .env
```

### 2. Configuration de Base

```bash
# Générer la clé d'application
php artisan key:generate

# Créer le lien de stockage
php artisan storage:link
```

### 3. Configuration Base de Données

Éditer le fichier `.env` avec vos paramètres :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=studiosdb
DB_USERNAME=votre_user
DB_PASSWORD=votre_password

# Configuration Mail (pour notifications)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@votre-ecole.com"
MAIL_FROM_NAME="StudiosDB"

# reCAPTCHA (pour inscription publique)
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key

# Session et Cache
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### 4. Migration et Données de Base

```bash
# Créer la base de données (MySQL)
mysql -u root -p -e "CREATE DATABASE studiosdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Exécuter les migrations
php artisan migrate

# Insérer les données de base (ceintures, rôles)
php artisan db:seed --class=CeinturesSeeder
php artisan db:seed --class=RolesSeeder

# Créer le premier utilisateur super-admin
php artisan studiosdb:create-superadmin
```

### 5. Compilation des Assets

```bash
# Développement
npm run dev

# Production
npm run build
```

### 6. Configuration Serveur Web

#### Apache (.htaccess déjà inclus)
```apache
DocumentRoot /path/to/studiosdb/public
```

#### Nginx
```nginx
server {
    listen 80;
    server_name studiosdb.local;
    root /path/to/studiosdb/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 7. Démarrage Rapide (Développement)

```bash
# Serveur de développement
php artisan serve --port=8000

# Dans un autre terminal - Watcher assets
npm run dev

# Accès : http://localhost:8000
```

## 🔧 Configuration Avancée

### Permissions Laravel
```bash
# Définir les permissions (Linux/Mac)
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Alternative développement
chmod -R 777 storage bootstrap/cache
```

### Variables d'Environnement Importantes

```env
# Sécurité
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key

# Multi-écoles
DEFAULT_ECOLE_ID=1
ALLOW_ECOLE_SWITCHING=false

# Uploads
MAX_UPLOAD_SIZE=10240  # KB
ALLOWED_IMAGE_TYPES=jpg,jpeg,png,gif,webp

# Rate Limiting
LOGIN_RATE_LIMIT=5      # tentatives/minute
REGISTER_RATE_LIMIT=3   # inscriptions/heure

# Features Flags
ENABLE_PUBLIC_REGISTRATION=true
ENABLE_FAMILY_LINKS=true
ENABLE_PHOTO_UPLOAD=true
REQUIRE_EMAIL_VERIFICATION=true
```

### Queue Workers (Production)
```bash
# Installer supervisor
sudo apt install supervisor

# Configuration /etc/supervisor/conf.d/studiosdb-worker.conf
[program:studiosdb-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/studiosdb/artisan queue:work --sleep=3 --tries=3
directory=/path/to/studiosdb
autostart=true
autorestart=true
user=www-data
numprocs=2
```

## 🧪 Tests

```bash
# Installation PHPUnit/Pest
composer install --dev

# Exécuter les tests
php artisan test

# Tests avec couverture
php artisan test --coverage

# Tests spécifiques
php artisan test --filter=CourTest
php artisan test tests/Feature/Auth/
```

## 📊 Maintenance

### Commandes Utiles

```bash
# Nettoyer les caches
php artisan optimize:clear

# Optimiser pour production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Maintenance mode
php artisan down
php artisan up

# Backup base de données
php artisan studiosdb:backup-db

# Générer rapport mensuel
php artisan studiosdb:monthly-report
```

### Monitoring Logs
```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Logs Nginx/Apache
tail -f /var/log/nginx/studiosdb_access.log
tail -f /var/log/nginx/studiosdb_error.log
```

## 🏗️ Architecture

### Structure du Projet
```
studiosdb/
├── app/
│   ├── Http/Controllers/     # Contrôleurs par module
│   ├── Models/              # Modèles Eloquent avec scoping
│   ├── Policies/            # Autorizations par rôle/école
│   └── Services/            # Logique métier
├── resources/
│   ├── js/
│   │   ├── Components/      # Composants Vue réutilisables
│   │   ├── Pages/          # Pages Inertia.js
│   │   └── Layouts/        # Layouts d'application
│   └── views/              # Templates Blade
├── database/
│   ├── migrations/         # Migrations avec scoping école
│   └── seeders/           # Données de base
└── tests/                 # Tests Pest/PHPUnit
```

### Stack Technologique
- **Backend** : Laravel 12.x, PHP 8.3+, MySQL 8.0+
- **Frontend** : Inertia.js, Vue 3 (Composition API), Tailwind CSS
- **Build** : Vite, PostCSS, Autoprefixer
- **Auth** : Laravel Breeze + Spatie Permission
- **Files** : Laravel Storage (local/S3)
- **Cache** : Redis (optionnel), File
- **Queue** : Database, Redis (optionnel)

## 🤝 Contribution

### Workflow de Développement
1. Fork le projet
2. Créer une branche feature : `git checkout -b feature/nouvelle-fonctionnalite`
3. Commits avec convention : `feat(module): description`
4. Tests : `php artisan test`
5. Push et Pull Request

### Standards Code
- **PSR-12** pour PHP
- **Vue 3 Composition API** pour JavaScript
- **Tailwind Utility-First** pour CSS
- **Tests Pest** pour la couverture

## 📝 Changelog

### v2.1.0 (Actuel)
- ✅ Corrections responsive design (Dashboard, StatCard, Cours)
- ✅ UX cohérente : boutons hover-only dans toutes les tables
- ✅ Performance : police responsive, anti-débordement
- ✅ Sécurité : scoping école strict, validation renforcée

### v2.0.0
- 🎯 Refonte complète interface utilisateur (Dark Mode)
- 🔐 Système multi-écoles avec isolation données
- 📱 Design responsive mobile-first
- 🚀 Migration Vue 3 + Inertia.js + Tailwind

## 📞 Support

### Documentation
- **Wiki** : [GitHub Wiki](https://github.com/votre-org/studiosdb/wiki)
- **API** : `/docs/api` (Swagger/OpenAPI)
- **Changelog** : `CHANGELOG.md`

### Aide
- **Issues** : [GitHub Issues](https://github.com/votre-org/studiosdb/issues)
- **Discussions** : [GitHub Discussions](https://github.com/votre-org/studiosdb/discussions)
- **Email** : support@studiosdb.com

## 📄 Licence

Ce projet est sous licence propriétaire. Tous droits réservés.

**Copyright © 2024 StudiosDB. Tous droits réservés.**

---

> **Note** : Pour un déploiement en production, consultez notre guide complet de déploiement dans le wiki du projet.

**Version actuelle** : v2.1.0  
**Dernière mise à jour** : Août 2024  
**Compatibilité** : Laravel 12.x, PHP 8.3+, Vue 3, MySQL 8.0+

# 🥋 StudiosDB v5 Pro

**Système de Gestion Ultra-Professionnel pour Écoles d'Arts Martiaux**

[![Version](https://img.shields.io/badge/version-5.4.0-blue.svg)](https://github.com/studiosdb/studiosdb-v5-pro)
[![Laravel](https://img.shields.io/badge/Laravel-12.21-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.5-green.svg)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.3-purple.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-orange.svg)](LICENSE)

## 📋 Description

StudiosDB v5 Pro est une solution complète et moderne de gestion d'écoles d'arts martiaux, spécialement conçue pour **École de Karaté Studiosunis St-Émile**. 

Le système offre une interface ultra-professionnelle pour gérer tous les aspects d'une école de karaté : membres, cours, présences, paiements, progressions de ceintures, et bien plus.

## ✨ Fonctionnalités Principales

### 👥 **Gestion des Membres**
- ✅ Inscription et profils détaillés
- ✅ Suivi des progressions de ceintures
- ✅ Historique complet des présences
- ✅ Gestion des contacts d'urgence
- ✅ Conformité Loi 25 (protection des données)
- ✅ Export des données personnelles

### 📚 **Gestion des Cours**
- ✅ Planning dynamique avec instructeurs
- ✅ Gestion des horaires et capacités
- ✅ Duplication de cours
- ✅ Vue calendrier intégrée
- ✅ Inscription automatique des membres

### 📋 **Interface Présences Tablette**
- ✅ Interface tactile optimisée
- ✅ Marquage rapide des présences
- ✅ Gestion des statuts (présent/absent/retard/excusé)
- ✅ Synchronisation temps réel
- ✅ Rapports de présence automatiques

### 💳 **Gestion Financière**
- ✅ Suivi des paiements et échéances
- ✅ Facturation automatique
- ✅ Gestion des retards de paiement
- ✅ Tableau de bord financier
- ✅ Exports comptables

### 🥋 **Système Ceintures & Examens**
- ✅ Gestion des grades et progressions
- ✅ Planification d'examens
- ✅ Enregistrement des résultats
- ✅ Suivi du parcours des élèves
- ✅ Certificats automatiques

### 📊 **Dashboard Ultra-Professionnel**
- ✅ Design moderne avec thème clair/sombre
- ✅ KPI temps réel avec graphiques
- ✅ Analytics avancées spécialisées karaté
- ✅ Métriques de performance
- ✅ Interface responsive

## 🏗️ Architecture Technique

### **Stack Technologique**
```
Backend Framework    : Laravel 12.21.x
Frontend Framework   : Vue.js 3.5 + Composition API + TypeScript
CSS Framework        : Tailwind CSS + Headless UI
SPA Solution         : Inertia.js avec SSR
Build Tool           : Vite avec hot reload
Authentication       : Laravel Fortify + Sanctum + Breeze
Base de Données      : MySQL 8.0+
Cache/Queue          : Redis 7.0+
Serveur Web          : Nginx + PHP-FPM 8.3
```

### **Architecture Multi-Tenant**
```
Stratégie           : Database per Tenant (Stancl/Tenancy)
Base Centrale       : studiosdb_central
Template            : studiosdb_template  
Écoles              : studiosdb_ecole_mtl001, studiosdb_ecole_qbc002
Préfixe configuré   : studiosdb_ecole_
Domaines exempts    : studiosdb.local, app.studiosdb.local
```

### **Sécurité & Permissions**
```
Système Rôles       : Spatie/Laravel-Permission
Rôles Disponibles   : super-admin, admin, gestionnaire, instructeur, membre
Permissions         : Granulaires par module
Multi-Factor Auth   : Supporté via Laravel Fortify
```

## 📦 Installation

### **Prérequis**
- PHP 8.3+
- Composer 2.5+
- Node.js 18+ & NPM 9+
- MySQL 8.0+
- Redis 7.0+
- Nginx ou Apache

### **Installation Rapide**

```bash
# 1. Cloner le projet
git clone https://github.com/studiosdb/studiosdb-v5-pro.git
cd studiosdb-v5-pro

# 2. Installation des dépendances
composer install --optimize-autoloader
npm install

# 3. Configuration environnement
cp .env.example .env
php artisan key:generate

# 4. Configuration base de données
# Modifier .env avec vos paramètres DB
php artisan migrate --seed

# 5. Compilation des assets
npm run build

# 6. Permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache

# 7. Cache optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Lancement serveur de développement
php artisan serve
```

## ⚙️ Configuration

### **Variables d'Environnement Principales**

```env
# Application
APP_NAME="StudiosDB v5 Pro"
APP_ENV=production
APP_URL=https://studiosdb.local
APP_LOCALE=fr
APP_TIMEZONE=America/Montreal

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=studiosdb_central
DB_USERNAME=studiosdb
DB_PASSWORD=your_secure_password

# Multi-tenant
TENANCY_DATABASE_PREFIX=studiosdb_ecole_
CENTRAL_DOMAINS=studiosdb.local,app.studiosdb.local

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_FROM_NAME="${APP_NAME}"
```

### **Configuration Nginx**

```nginx
server {
    listen 80;
    server_name studiosdb.local *.studiosdb.local;
    root /path/to/studiosdb-v5-pro/public;
    
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

## 🚀 Utilisation

### **Accès par Rôles**

| Rôle | URL d'accès | Fonctionnalités |
|------|-------------|-----------------|
| **Super Admin** | `/admin/global` | Gestion multi-écoles, analytics globales |
| **Admin École** | `/dashboard` | Gestion complète de son école |
| **Gestionnaire** | `/dashboard` | Inscriptions, paiements, planning |
| **Instructeur** | `/instructeur` | Ses cours, présences, progressions |
| **Membre** | `/membre` | Profil, progression, paiements |

### **Premiers Pas**

1. **Connexion Admin** : `admin@studiosdb.local` / `password`
2. **Créer des Cours** : Dashboard → Gestion Cours → Nouveau Cours
3. **Ajouter des Membres** : Dashboard → Gestion Membres → Nouveau Membre
4. **Interface Présences** : `/presences/tablette` (optimisée tablette)

### **Commands Artisan Personnalisées**

```bash
# Générer données de test
php artisan studiosdb:seed-demo

# Backup base de données
php artisan studiosdb:backup

# Nettoyage système
php artisan studiosdb:cleanup

# Génération rapports
php artisan studiosdb:generate-reports
```

## 📊 Métriques & Analytics

Le système inclut des analytics avancées :

- **Taux de présence** temps réel
- **Progression ceintures** par membre
- **Performance financière** mensuelle/annuelle
- **Rétention membres** avec prédictions
- **Statistiques instructeurs** détaillées

## 🛠️ Développement

### **Structure du Projet**

```
studiosdb-v5-pro/
├── app/
│   ├── Http/Controllers/     # Contrôleurs métier
│   ├── Models/              # Modèles Eloquent
│   ├── Policies/            # Autorizations
│   └── Services/            # Services métier
├── resources/
│   ├── js/
│   │   ├── Pages/           # Vues Vue.js/Inertia
│   │   ├── Components/      # Composants réutilisables
│   │   └── Layouts/         # Layouts principaux
│   ├── css/                 # Styles Tailwind
│   └── views/               # Vues Blade (minimal)
├── database/
│   ├── migrations/          # Migrations DB
│   ├── seeders/            # Seeders
│   └── factories/          # Factories
├── routes/
│   ├── web.php             # Routes web principales
│   ├── tenant.php          # Routes tenant
│   └── api.php             # API routes
└── config/                 # Configuration
```

### **Standards de Code**

- **PSR-12** pour PHP
- **ESLint + Prettier** pour JavaScript/Vue
- **PHPUnit** pour les tests PHP
- **Jest** pour les tests JavaScript

### **Tests**

```bash
# Tests PHP
php artisan test

# Tests JavaScript
npm run test

# Tests E2E
npm run test:e2e
```

## 🔄 Changelog

### **v5.4.0** (2025-08-05) - Current
- ✅ Dashboard ultra-professionnel avec thème clair
- ✅ Correction erreurs SQL avec gestion adaptative des tables
- ✅ Interface présences tablette optimisée
- ✅ Système de cache intelligent
- ✅ Gestion d'erreurs robuste

### **v5.3.0** (2025-07-20)
- ✅ Architecture multi-tenant complète
- ✅ Système rôles et permissions granulaires
- ✅ Modules membres, cours, présences
- ✅ Base Laravel 12.21 + Vue 3

### **v5.2.0** (2025-07-01)
- ✅ Migration depuis v4
- ✅ Nouvelle architecture moderne
- ✅ Design system Tailwind

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/amazing-feature`)
3. Commit vos changements (`git commit -m 'Add amazing feature'`)
4. Push vers la branche (`git push origin feature/amazing-feature`)
5. Ouvrir une Pull Request

## 📞 Support

- **Documentation** : [docs.studiosdb.local](https://docs.studiosdb.local)
- **Issues** : [GitHub Issues](https://github.com/studiosdb/studiosdb-v5-pro/issues)
- **Email Support** : support@studiosdb.local
- **Discord** : [StudiosDB Community](https://discord.gg/studiosdb)

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 🏆 Crédits

**Développé avec ❤️ pour l'École de Karaté Studiosunis St-Émile**

- **Framework** : Laravel Team
- **Frontend** : Vue.js Team  
- **Design** : Tailwind CSS Team
- **Icons** : Heroicons
- **Développement** : StudiosDB Team

---

<div align="center">

**🥋 StudiosDB v5 Pro - La Solution Professionnelle pour Écoles d'Arts Martiaux**

[![Stars](https://img.shields.io/github/stars/studiosdb/studiosdb-v5-pro?style=social)](https://github.com/studiosdb/studiosdb-v5-pro)
[![Forks](https://img.shields.io/github/forks/studiosdb/studiosdb-v5-pro?style=social)](https://github.com/studiosdb/studiosdb-v5-pro)

</div>
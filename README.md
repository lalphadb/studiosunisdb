# 🥋 StudiosDB v5 Pro

> **Système de gestion ultra-professionnel pour écoles d'arts martiaux**  
> *École Studiosunis St-Émile - Solution complète Laravel + Vue.js*

[![Laravel](https://img.shields.io/badge/Laravel-12.20-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-yellow.svg)]()
[![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen.svg)]()

---

## 🎯 Vue d'ensemble

**StudiosDB v5 Pro** est une solution complète de gestion pour écoles d'arts martiaux, spécialement conçue pour l'**École Studiosunis St-Émile**. Cette application web moderne offre une interface intuitive pour gérer tous les aspects d'une école de karaté : membres, cours, présences, progressions ceintures, paiements et bien plus.

### ✨ Fonctionnalités Principales

- 🧑‍🎓 **Gestion Membres** - Profils complets, suivi médical, consentements Loi 25
- 📅 **Planning Cours** - Horaires, instructeurs, inscriptions, capacités
- ✅ **Présences Tablette** - Interface tactile optimisée pour marquage rapide
- 🥋 **Système Ceintures** - Progressions, examens, certifications
- 💰 **Gestion Financière** - Paiements, facturation, rapports comptables
- 👥 **Multi-Rôles** - Admin, gestionnaire, instructeur, membre
- 🏢 **Multi-Tenant** - Support plusieurs écoles (architecture évolutive)
- 📊 **Analytics** - Tableaux de bord, statistiques, rapports avancés

---

## 🏗️ Architecture Technique

### Stack Technologique

| Composant | Version | Description |
|-----------|---------|-------------|
| **Backend** | Laravel 12.20 | Framework PHP moderne |
| **Frontend** | Vue.js 3.x | Interface utilisateur réactive |
| **SPA** | Inertia.js 2.x | Single Page Application |
| **CSS** | Tailwind CSS | Framework CSS utilitaire |
| **Build** | Vite | Outil de build ultra-rapide |
| **Database** | MySQL 8.0+ | Base de données relationnelle |
| **Cache** | Redis 7.x | Cache haute performance |
| **Server** | Nginx | Serveur web optimisé |

### Architecture Multi-Tenant

```
📊 Base Centrale (studiosdb_central)
├── 🏢 École MTL (studiosdb_ecole_mtl001)
├── 🏢 École QBC (studiosdb_ecole_qbc002)
└── 🏢 École STE (studiosdb_ecole_ste003)
```

**Stratégie :** Database per Tenant avec isolation complète  
**Package :** Stancl/Tenancy v3.9+  
**Domaines :** `*.4lb.ca` (wildcard support)

---

## 🚀 Installation & Configuration

### Prérequis Système

```bash
# Serveur Ubuntu 24.04 LTS
- PHP 8.3+ (avec extensions: mysql, redis, gd, zip, xml)
- MySQL 8.0+ 
- Redis 7.x
- Nginx
- Node.js 18+ & NPM
- Composer 2.x
```

### Installation Rapide

```bash
# 1. Cloner le repository
git clone https://github.com/[username]/studiosdb-v5-pro.git
cd studiosdb-v5-pro

# 2. Installation dépendances
composer install --optimize-autoloader
npm ci

# 3. Configuration environnement
cp .env.example .env
php artisan key:generate

# 4. Base de données
php artisan migrate --seed

# 5. Compilation assets
npm run build

# 6. Permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Accès Application

- 🌐 **URL Production :** http://4lb.ca/dashboard
- 👤 **Admin :** louis@4lb.ca
- 🔑 **Setup :** Mot de passe configuré lors de l'installation

---

## 👥 Système de Rôles

### Hiérarchie des Permissions

```
🔑 super-admin
├── ✅ Accès toutes écoles
├── ✅ Gestion tenants
└── ✅ Configuration système

🔑 admin (Louis - louis@4lb.ca)
├── ✅ Gestion complète école
├── ✅ Rapports financiers
└── ✅ Configuration branding

🔑 gestionnaire
├── ✅ Inscriptions/paiements
├── ✅ Planning cours
└── ✅ Rapports opérationnels

🔑 instructeur
├── ✅ Ses cours uniquement
├── ✅ Présences groupes
└── ✅ Propositions examens

🔑 membre
├── ✅ Profil personnel
├── ✅ Historique progression
└── ✅ Paiements/factures
```

---

## 📊 Fonctionnalités Implémentées

### ✅ OPÉRATIONNEL

- **Authentification & Sécurité**
  - ✅ Login/Register Laravel Breeze
  - ✅ Système rôles/permissions Spatie
  - ✅ Dashboard adaptatif selon rôle
  - ✅ Multi-tenant isolation

- **Interface Moderne**
  - ✅ Design responsive Tailwind CSS
  - ✅ Composants Vue 3 réutilisables  
  - ✅ SPA Inertia.js fluide
  - ✅ Navigation adaptative rôles

- **Gestion Membres**
  - ✅ CRUD complet membres
  - ✅ Profils détaillés
  - ✅ Système consentements (Loi 25)
  - ✅ Export données personnelles

### 🔄 EN DÉVELOPPEMENT

- **Modules Business Complets**
  - 🔄 Gestion cours & planning
  - 🔄 Interface présences tablette
  - 🔄 Système ceintures & examens
  - 🔄 Facturation automatique

---

## 🛣️ Structure Projet

```
studiosdb-v5-pro/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php ✅
│   │   ├── MembreController.php ✅
│   │   └── ...
│   ├── Models/
│   │   ├── User.php ✅
│   │   ├── Membre.php ✅
│   │   └── ...
│   └── Services/
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Dashboard/ ✅
│   │   │   ├── Membres/ ✅
│   │   │   └── ...
│   │   ├── Layouts/ ✅
│   │   └── Components/
│   └── views/
├── database/
│   ├── migrations/ ✅
│   └── seeders/
├── config/
│   ├── tenancy.php ✅
│   └── permission.php ✅
└── public/
    └── build/ ✅
```

---

## 🔧 Configuration Serveur

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name 4lb.ca *.4lb.ca;
    root /home/studiosdb/studiosunisdb/studiosdb_v5_pro/public;
    
    # Headers Inertia.js
    location ~ \.php$ {
        fastcgi_param HTTP_X_INERTIA $http_x_inertia;
        fastcgi_param HTTP_X_INERTIA_VERSION $http_x_inertia_version;
        # ... configuration FastCGI
    }
}
```

### Environment Production

```env
APP_NAME="StudiosDB v5 Pro"
APP_ENV=production
APP_URL=http://4lb.ca
APP_LOCALE=fr
APP_TIMEZONE=America/Montreal

DB_CONNECTION=mysql
DB_DATABASE=studiosdb_central
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

---

## 🚀 Déploiement

### Commandes Essentielles

```bash
# Compilation production
npm run build

# Cache optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## 📈 Performance

### Optimisations Actives

- ✅ **Cache Redis** pour sessions et données
- ✅ **Assets Vite** compilation optimisée
- ✅ **Laravel Cache** configuration/routes/vues
- ✅ **Nginx Gzip** compression activée
- ✅ **Database Indexes** requêtes optimisées

---

## 🔒 Sécurité

### Protections Implémentées

- 🔐 **CSRF Protection** Laravel native
- 🛡️ **XSS Prevention** validation stricte
- 🔑 **SQL Injection** protection Eloquent
- 📝 **Activity Logging** actions utilisateurs
- 🚫 **Rate Limiting** API endpoints
- 🔒 **Headers Sécurité** Nginx configuré

---

## 🗺️ Roadmap

### v5.0.0-beta (Actuel) ✅
- ✅ Architecture & authentification
- ✅ Dashboard adaptatif
- ✅ Interface de base

### v5.1.0 (Août 2025) 🔄
- 🔄 Modules business complets
- 🔄 Interface tablette présences
- 🔄 Système examens ceintures

### v5.2.0 (Septembre 2025) 📅
- 📅 Facturation automatique
- 📅 Rapports analytics
- 📅 Notifications parents

---

## 👥 Équipe

- **École :** Studiosunis St-Émile
- **Admin :** Louis (louis@4lb.ca)
- **Développement :** StudiosDB Team
- **Version :** 5.0.0-beta

---

## 📄 Licence

Copyright (c) 2025 École Studiosunis St-Émile  
Tous droits réservés.

---

**StudiosDB v5 Pro** - *Révolutionner la gestion des écoles d'arts martiaux* 🥋

*Fait avec ❤️ au Québec*

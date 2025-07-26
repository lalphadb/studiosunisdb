# 🥋 StudiosDB V5 Pro

**Système de gestion ultra-professionnel pour écoles d'arts martiaux**

## 🎯 Description

StudiosDB V5 est une solution complète de gestion pour l'école de karaté **Studiosunis St-Émile**. 
Cette application Laravel 11 moderne offre une interface intuitive pour gérer membres, cours, présences, paiements et progressions de ceintures.

## 🛠️ Stack Technique

- **Backend:** Laravel 11.x + PHP 8.3
- **Frontend:** Vue 3 + TypeScript + Inertia.js
- **CSS:** Tailwind CSS + Headless UI
- **Base de données:** MySQL 8.0+
- **Cache:** Redis
- **Build:** Vite avec hot reload

## ⚡ Installation

```bash
# 1. Cloner le projet
git clone <repository-url>
cd studiosdb_v5_pro

# 2. Installer dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Base de données
php artisan migrate --seed

# 5. Compiler assets
npm run build

# 6. Démarrer serveur
php artisan serve
```

## 🎨 Fonctionnalités

### ✅ Modules Opérationnels

- **👥 Gestion Membres:** CRUD complet, profils détaillés, conformité Loi 25
- **🥋 Système Ceintures:** 7 grades avec progression automatique
- **📊 Dashboard:** Interface adaptative par rôle
- **🔐 Authentification:** Laravel Breeze + rôles Spatie
- **📱 Responsive:** Interface moderne pour tous écrans

### 🔄 Modules À Développer

- **📅 Gestion Cours:** Planning, inscriptions, capacités
- **✅ Présences:** Interface tablette, statistiques
- **💰 Paiements:** Facturation, rappels, exports
- **🏢 Multi-tenant:** Gestion multi-écoles

## 🗃️ Base de Données

### Tables Principales

```
users            → Authentification
membres          → Profils élèves  
ceintures        → Système grades
cours            → Planning cours
presences        → Suivi assiduité
paiements        → Gestion financière
```

### Seeding

```bash
# Installer données de test
php artisan db:seed --class=CeintureSeeder
```

## 🎯 Utilisateurs

### Accès Admin

- **Email:** louis@4lb.ca
- **Password:** password123
- **Rôle:** Administrateur complet

### Hiérarchie des Rôles

1. **super-admin** → Multi-écoles
2. **admin** → Propriétaire école
3. **gestionnaire** → Administration
4. **instructeur** → Enseignement
5. **membre** → Élève/Parent

## 🌐 URLs Principales

- `/dashboard` → Tableau de bord
- `/membres` → Gestion membres
- `/debug` → Diagnostic système
- `/test` → API de test

## 🔧 Développement

### Scripts Utiles

```bash
# Tests
php artisan test

# Cache
php artisan optimize
php artisan optimize:clear

# Debug
tail -f storage/logs/laravel.log
```

### Structure Vue.js

```
resources/js/Pages/
├── Dashboard.vue        → Accueil
├── Membres/
│   ├── Index.vue       → Liste
│   ├── Create.vue      → Création
│   ├── Show.vue        → Détails
│   └── Edit.vue        → Modification
└── Auth/               → Authentification
```

## 📊 Statistiques Projet

- **Migrations:** 15+ tables
- **Vues Vue.js:** 20+ composants
- **Routes:** 50+ endpoints
- **Modèles:** Architecture enterprise
- **Tests:** Suite complète PHPUnit

## 🎖️ Conformité

- **Loi 25 (Québec):** Gestion consentements
- **PSR-12:** Standards PHP
- **Laravel Best Practices:** Architecture MVC
- **Security:** CSRF, Auth, Validation

## 🚀 Déploiement

### Production

```bash
# Build optimisé
composer install --optimize-autoloader --no-dev
npm run build

# Cache production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chmod -R 755 storage bootstrap/cache
```

### Serveur Web

- **Nginx:** Configuration fournie
- **PHP-FPM:** 8.3+
- **MySQL:** 8.0+
- **Redis:** Cache & sessions

## 📞 Support

- **École:** Studiosunis St-Émile
- **Admin:** Louis (louis@4lb.ca)
- **Tech:** StudiosDB Team

## 📄 Licence

Propriétaire - École Studiosunis St-Émile

---

**🏆 StudiosDB V5 - LA solution pour écoles d'arts martiaux au Québec!**

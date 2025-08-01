# 🥋 StudiosDB v5 Pro - Système de Gestion d'École d'Arts Martiaux

[![Laravel](https://img.shields.io/badge/Laravel-12.21.0-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.3.6+-blue.svg)](https://php.net)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen.svg)](https://github.com/lalphadb/studiosunisdb.git)

> **Mis à jour:** 01/08/2025 à 09:34 | **Version:** 5.1.2

## 📋 Vue d'Ensemble

**StudiosDB v5 Pro** est un système de gestion ultra-moderne pour écoles d'arts martiaux, développé pour **École Studiosunis St-Émile**. Solution complète avec architecture multi-tenant, interface Vue 3, et performance exceptionnelle.

## ✨ Caractéristiques

- 🏗️ **Multi-tenant** - Plusieurs écoles
- 🎨 **Interface Moderne** - Vue 3 + Tailwind CSS
- ⚡ **Performance** - Dashboard 15ms
- 🔐 **Sécurité** - RGPD/Loi 25 compliant
- 📱 **Tablette** - Interface présences tactile
- 💰 **Financier** - Paiements automatisés
- 🥋 **Ceintures** - Progressions personnalisées

## 🚀 Installation Rapide

```bash
git clone https://github.com/lalphadb/studiosunisdb.git
cd studiosdb_v5_pro
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

## ⚡ Performance Record

```yaml
Dashboard: 15ms (vs 500ms avant)
Requêtes SQL: 1 (vs 15+ avant)
Amélioration: +95% plus rapide
Cache: Redis intelligent
```

## 📚 Modules

### 👥 Gestion Membres
- CRUD complet avec consentements
- Relations familiales
- Données médicales chiffrées
- Exports conformes

### 📅 Planning Cours
- Horaires flexibles
- Inscriptions en ligne
- Vue calendrier interactive
- Tarification modulaire

### 📱 Présences Tablette
- Interface tactile optimisée
- Marquage ultra-rapide
- Mode offline
- Statistiques temps réel

### 💰 Gestion Financière
- Paiements multiples
- Facturation automatique
- Rappels intelligents
- Analytics revenus

### 🥋 Système Ceintures
- Progressions personnalisées
- Examens planifiés
- Évaluations techniques
- Certificats automatiques

## 🏗️ Architecture

```yaml
Backend: Laravel 12.21.0 + PHP 8.3.6+
Frontend: Vue 3 + Inertia.js + Tailwind
Database: MySQL 8.0+ + Redis Cache
Multi-tenant: Stancl/Tenancy
```

## 👥 Rôles

- **super-admin**: Multi-écoles
- **admin**: Propriétaire école (louis@4lb.ca)
- **gestionnaire**: Administration
- **instructeur**: Enseignement
- **membre**: Élève/Parent

## 🧪 Tests & Qualité

```bash
php artisan test                # Tests
./vendor/bin/pint              # PSR-12
./vendor/bin/phpstan analyse   # Analyse statique
```

## 🚀 Production

```bash
APP_ENV=production
CACHE_DRIVER=redis
php artisan optimize
```

## 📞 Support

- 📧 Email: support@studiosdb.ca
- 🐛 Issues: [GitHub](https://github.com/lalphadb/studiosunisdb.git/issues)
- 📖 Wiki: [Documentation](https://github.com/lalphadb/studiosunisdb.git/wiki)

---

<div align="center">

**StudiosDB v5 Pro** - *Excellence en Arts Martiaux* 🥋

Développé avec ❤️ pour École Studiosunis St-Émile

</div>

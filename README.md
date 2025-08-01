# 🥋 StudiosDB v5 Pro - Système de Gestion d'École d'Arts Martiaux

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen.svg)](https://github.com)

## 📋 Vue d'Ensemble

**StudiosDB v5 Pro** est un système de gestion ultra-moderne et complet pour écoles d'arts martiaux, développé spécifiquement pour **École Studiosunis St-Émile**. Solution full-stack avec architecture multi-tenant, interface utilisateur sophistiquée, et fonctionnalités métier spécialisées.

## ✨ Caractéristiques Principales

- 🏗️ **Architecture Multi-tenant** - Gestion de plusieurs écoles
- 🎨 **Interface Moderne** - Vue 3 + Inertia.js + Tailwind CSS  
- ⚡ **Performance Optimisée** - Dashboard 15ms, Cache Redis
- 🔐 **Sécurité Avancée** - Rôles granulaires, CSRF, conformité RGPD
- 📱 **Interface Tablette** - Présences tactiles optimisées
- 💰 **Gestion Financière** - Paiements, factures, rappels automatiques
- 🥋 **Système Ceintures** - Progressions, examens, certifications
- 📊 **Analytics Temps Réel** - Métriques business, rapports KPI

## 🚀 Installation Rapide

```bash
# Cloner et installer
git clone https://github.com/votre-username/studiosdb_v5_pro.git
cd studiosdb_v5_pro
composer install --optimize-autoloader
npm install && npm run build

# Configuration
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Lancement
php artisan serve
```

## 🏗️ Architecture Technique

### Stack Principal
- **Backend:** Laravel 12.21.x + PHP 8.3+
- **Frontend:** Vue 3 + Inertia.js + Tailwind CSS
- **Base de Données:** MySQL 8.0+ avec Redis Cache
- **Multi-tenant:** Stancl/Tenancy pour écoles multiples

### Performance Dashboard
```yaml
Avant Optimisation: 15+ requêtes SQL, 200-500ms
Après Optimisation: 1 requête SQL, 15ms, Cache Redis
Amélioration: +95% de performance !
```

## 📚 Modules Fonctionnels

### 1. 👥 Gestion Membres
- CRUD complet avec profils détaillés
- Consentements RGPD/Loi 25
- Relations familiales
- Historique progressions

### 2. 📅 Planning & Cours  
- Horaires flexibles
- Inscriptions en ligne
- Vue calendrier
- Tarification modulaire

### 3. 📱 Interface Présences Tablette
- Interface tactile optimisée
- Marquage rapide (présent/absent/retard)
- Synchronisation temps réel

### 4. 💰 Gestion Financière
- Paiements multiples
- Facturation automatique
- Rappels automatiques
- Exports comptables

### 5. 🥋 Système Ceintures
- Progressions personnalisées
- Planification examens
- Évaluations techniques
- Certificats automatiques

## 🔐 Rôles & Permissions

```yaml
super-admin: Accès multi-écoles
admin: Propriétaire école (louis@4lb.ca)
gestionnaire: Administration quotidienne  
instructeur: Gestion cours et examens
membre: Élève/Parent
```

## ⚡ Performance & Optimisations

### Métriques Actuelles
- ⏱️ **Temps réponse dashboard:** 15ms
- 🗄️ **Requêtes SQL:** 1 requête unique optimisée
- 💾 **Cache:** Redis 5 minutes
- 🚀 **Amélioration:** +95% vs version initiale

### Optimisations Implémentées
- Requête SQL unique avec sous-requêtes
- Cache intelligent Redis
- Index de performance
- Lazy loading composants Vue

## 🧪 Tests & Qualité

```bash
# Tests
php artisan test

# Qualité code PSR-12
./vendor/bin/pint

# Coverage: 84% (Controllers: 85%, Models: 90%)
```

## 🚀 Déploiement Production

```bash
# Configuration production
APP_ENV=production
APP_DEBUG=false
CACHE_DRIVER=redis

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan optimize
```

## 📊 Monitoring

- **Laravel Telescope:** /telescope
- **Logs:** storage/logs/laravel.log
- **Métriques temps réel:** /api/dashboard/metriques

## 🤝 Contribution

1. Fork le projet
2. Créer branche feature (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push branch (`git push origin feature/amazing-feature`)
5. Ouvrir Pull Request

## 📄 Licence

MIT License - voir [LICENSE](LICENSE) pour détails.

## 📞 Support

- 📧 **Support:** support@studiosdb.ca
- 🐛 **Issues:** [GitHub Issues](https://github.com/votre-repo/studiosdb_v5_pro/issues)
- 📖 **Documentation:** [Wiki Complet](https://github.com/votre-repo/studiosdb_v5_pro/wiki)

---

<div align="center">

**StudiosDB v5 Pro** - *Révolutionner la Gestion des Arts Martiaux* 🥋

[![Fait avec ❤️](https://img.shields.io/badge/Fait%20avec-❤️-red.svg)](https://github.com)
[![Laravel](https://img.shields.io/badge/Propulsé%20par-Laravel-red.svg)](https://laravel.com)

*Développé avec passion pour École Studiosunis St-Émile* 🥋✨

</div>

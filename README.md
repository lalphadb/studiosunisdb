# 🥋 StudiosDB v4.1.10.2

## Système de Gestion pour Écoles d'Arts Martiaux

[![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3.6-blue.svg)](https://php.net)
[![Filament](https://img.shields.io/badge/Filament-3.2-orange.svg)](https://filamentphp.com)

### 🚀 Statut du Projet
- ✅ **Version Stable** : v4.1.10.2
- ✅ **Dashboard Opérationnel** : Interface Filament complète
- ✅ **Multi-tenant** : Isolation par école
- ✅ **Performance Optimisée** : Widgets sans polling

### 🏗️ Architecture

```
StudiosDB v4/
├── 🏫 Multi-tenant (par école)
├── 👥 Gestion Utilisateurs
├── 🥋 Cours et Horaires
├── 📊 Présences et Statistiques
├── 💰 Paiements et Finances
├── 🎯 Séminaires et Événements
└── ⚙️ Administration
```

### 📦 Technologies

- **Backend**: Laravel 12, PHP 8.3.6
- **Frontend**: Filament 3.2, Tailwind CSS
- **Database**: MySQL 8.0+
- **Auth**: Spatie Laravel Permission
- **Locale**: fr_CA (Québec)

### 🔧 Installation

```bash
# Cloner le projet
git clone https://github.com/VOTRE_USERNAME/studiosdb-v4.git
cd studiosdb-v4

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Démarrer le serveur
php artisan serve --port=8001
```

### 🌐 Accès

- **Admin Panel**: http://localhost:8001/admin
- **Utilisateur Test**: 
  - Email: `lalpha@4lb.ca`
  - Password: `password123`
  - Rôle: `super-admin`

### 📊 Widgets Dashboard

1. **📈 StatsOverview** - Statistiques générales
2. **📊 PresencesChart** - Graphique des présences
3. **🕒 RecentActivity** - Activités récentes

### 🏫 Modules Principaux

- **Écoles** - Gestion multi-tenant
- **Utilisateurs** - Membres, instructeurs, admins
- **Cours** - Planning, horaires, capacités
- **Présences** - Suivi temps réel
- **Ceintures** - Système de grades
- **Paiements** - Facturation et reçus
- **Séminaires** - Événements spéciaux

### 🔒 Sécurité

- **Authentification**: Laravel Sanctum
- **Autorisation**: Spatie Permissions
- **Multi-tenant**: Isolation par `ecole_id`
- **RGPD/Loi 25**: Soft deletes + anonymisation

### 📈 Performance

- **Caching**: Redis recommandé
- **Queues**: Database driver
- **Widgets**: Polling désactivé
- **Optimisations**: Eager loading, indexes

### 🧪 Tests

```bash
# Tests unitaires
php artisan test

# Tests navigateur
php artisan dusk
```

### 📚 Documentation

- [Architecture Complète](docs/ARCHITECTURE.md)
- [Guide Multi-tenant](docs/MULTI_TENANT.md)
- [API Documentation](docs/API.md)

### 🤝 Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b feature/nouvelle-fonctionnalite`)
3. Commit (`git commit -am 'Ajout nouvelle fonctionnalité'`)
4. Push (`git push origin feature/nouvelle-fonctionnalite`)
5. Pull Request

### 📞 Support

- **Email**: support@studiosdb.com
- **Documentation**: https://docs.studiosdb.com
- **Issues**: https://github.com/VOTRE_USERNAME/studiosdb-v4/issues

### 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

**StudiosDB v4.1.10.2** - Système de gestion moderne pour écoles d'arts martiaux 🥋

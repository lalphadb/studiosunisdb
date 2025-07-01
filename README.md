# StudiosDB v5.7.1

<div align="center">

![StudiosDB Logo](https://img.shields.io/badge/StudiosDB-v5.7.1-blue?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-12.19.3-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3.6-purple?style=for-the-badge&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Système de gestion complet pour écoles de karaté**

[Documentation](#documentation) • [Installation](#installation) • [Fonctionnalités](#fonctionnalités) • [Changelog](CHANGELOG.md)

</div>

---

## 🥋 Description

StudiosDB est un système de gestion moderne et complet conçu spécifiquement pour les écoles de karaté. Il offre une solution tout-en-un pour gérer les membres, les cours, les progressions de ceintures, les paiements et bien plus.

### ✨ Caractéristiques principales

- **🔐 Multi-tenant sécurisé** - Gestion de plusieurs écoles avec permissions granulaires
- **👥 Gestion des membres** - Profils complets, suivi des progressions
- **📚 Planning des cours** - Organisation et inscriptions simplifiées
- **🥋 Système de ceintures** - Suivi automatisé des progressions
- **💰 Gestion financière** - Paiements, factures, rapports
- **📱 Interface moderne** - Design responsive et intuitif
- **🛡️ Conformité Loi 25** - Protection des données personnelles

---

## 🚀 Fonctionnalités

### Modules principaux

| Module | Description | Statut |
|--------|-------------|--------|
| 👤 **Utilisateurs** | Gestion complète des membres | ✅ Stable |
| 🏫 **Écoles** | Réseau d'écoles de karaté | ✅ Stable |
| 📚 **Cours** | Planning et inscriptions | ✅ Stable |
| 🥋 **Ceintures** | Système de progression | ✅ Stable |
| 🎯 **Séminaires** | Événements spéciaux | ✅ Stable |
| 💰 **Paiements** | Gestion financière | ✅ Stable |
| ✅ **Présences** | Suivi d'assiduité | ✅ Stable |

### Rôles et permissions

- **🔴 Superadmin** - Accès complet au système
- **🟡 Admin École** - Gestion de son école uniquement
- **🟢 Instructeur** - Consultation et enseignement
- **🔵 Membre** - Dashboard personnel et inscriptions

---

## 🛠️ Installation

### Prérequis

- PHP 8.3+
- MySQL 8.0+
- Composer
- Node.js & npm
- Git

### Installation rapide

\`\`\`bash
# Cloner le projet
git clone https://github.com/lalphadb/studiosunisdb.git
cd studiosunisdb

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Assets
npm run build

# Lancer le serveur
php artisan serve
\`\`\`

### Configuration détaillée

Voir le fichier [INSTALL.md](INSTALL.md) pour les instructions complètes.

---

## 📚 Documentation

### Comptes de test

| Rôle | Email | Mot de passe | Accès |
|------|-------|--------------|-------|
| Superadmin | lalpha@4lb.ca | password123 | Système complet |
| Admin École | louis@4lb.ca | password123 | École assignée |

### Structure du projet

\`\`\`
studiosdb/
├── app/
│   ├── Http/Controllers/Admin/    # Contrôleurs administration
│   ├── Models/                    # Modèles Eloquent
│   └── Policies/                  # Autorisations
├── resources/views/
│   ├── admin/                     # Vues administration
│   ├── auth/                      # Authentification
│   └── components/                # Composants Blade
└── routes/
    ├── web.php                    # Routes principales
    └── admin.php                  # Routes administration
\`\`\`

---

## 🔧 Technologies

- **Backend** : Laravel 12.19.3 LTS
- **Frontend** : Tailwind CSS, Alpine.js
- **Base de données** : MySQL 8.0.42
- **Authentification** : Laravel Breeze + Spatie Permission
- **Monitoring** : Laravel Telescope
- **Validation** : FormRequest, Policies

---

## 🤝 Contribution

Les contributions sont les bienvenues ! Veuillez consulter notre [guide de contribution](CONTRIBUTING.md).

### Développement

\`\`\`bash
# Tests
php artisan test

# Code Style
./vendor/bin/pint

# Analyse statique
./vendor/bin/phpstan analyse
\`\`\`

---

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## 🏷️ Versioning

Ce projet utilise le [Versioning Sémantique](https://semver.org/lang/fr/). 
Pour les versions disponibles, consultez les [tags de ce dépôt](https://github.com/lalphadb/studiosunisdb/tags).

**Version actuelle : v5.7.1**

---

## 📞 Support

- 📧 Email : support@studiosdb.com
- 🌐 Site web : [studiosdb.com](https://studiosdb.com)
- 🐛 Issues : [GitHub Issues](https://github.com/lalphadb/studiosunisdb/issues)
- 📋 Discussions : [GitHub Discussions](https://github.com/lalphadb/studiosunisdb/discussions)

---

<div align="center">

**Fait avec ❤️ pour la communauté du karaté**

[⬆ Retour en haut](#studiosdb-v100)

</div>

## ⚠️ Problèmes Connus

### Dashboard Admin École
- **Erreur 403** sur route `/admin`
- **Cause**: Permission `view-dashboard` manquante
- **Solution**: En cours de correction
- **Workaround**: Accès via routes modules spécifiques

Voir [KNOWN_ISSUES.md](KNOWN_ISSUES.md) pour détails complets.

## 🎯 Roadmap

- [ ] Fix permissions dashboard (priorité haute)
- [ ] Tests sécurité complets  
- [ ] Validation multi-tenant
- [ ] Release stable v4.1.11

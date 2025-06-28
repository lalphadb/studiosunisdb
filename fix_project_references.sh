#!/bin/bash
echo "🔧 CORRECTION RÉFÉRENCES PROJET"
echo "==============================="

# Informations correctes du projet
PROJECT_NAME="StudiosDB"
GITHUB_USER="lalphadb"
REPO_NAME="studiosunisdb"
DOMAIN="studiosdb.com"
EMAIL="support@studiosdb.com"

echo "📝 Correction avec les bonnes informations :"
echo "• Projet: $PROJECT_NAME"
echo "• GitHub: https://github.com/$GITHUB_USER/$REPO_NAME"
echo "• Domaine: $DOMAIN"
echo "• Email: $EMAIL"
echo ""

# 1. Corriger README.md
echo "📝 1. Correction README.md..."

cat > README.md << "README_EOF"
# StudiosDB v1.0.0

<div align="center">

![StudiosDB Logo](https://img.shields.io/badge/StudiosDB-v1.0.0-blue?style=for-the-badge)
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

**Version actuelle : v1.0.0**

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
README_EOF

echo "✅ README.md corrigé"

# 2. Corriger CHANGELOG.md
echo ""
echo "📝 2. Correction CHANGELOG.md..."

cat > CHANGELOG.md << "CHANGELOG_EOF"
# Changelog - StudiosDB

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère au [Versioning Sémantique](https://semver.org/lang/fr/).

## [Non publié]

### À venir
- Système de notifications
- API REST complète
- Interface mobile responsive
- Module de reporting avancé

---

## [1.0.0] - $(date +%Y-%m-%d)

### 🎉 Version initiale stable

#### Ajouté
- **Système d'authentification complet**
  - Inscription avec formulaire détaillé
  - Connexion sécurisée
  - Conformité Loi 25 (protection des données)
  - Gestion des rôles avec Spatie Permission

- **Architecture multi-tenant**
  - Superadmin : accès global
  - Admin école : gestion de son école uniquement
  - Instructeur : consultation de son école
  - Membre : dashboard personnel

- **7 Modules fonctionnels**
  - 👤 **Utilisateurs** : Gestion complète des membres
  - 🏫 **Écoles** : Réseau d'écoles de karaté
  - 📚 **Cours** : Planning et inscriptions
  - 🥋 **Ceintures** : Système de progression
  - 🎯 **Séminaires** : Événements spéciaux
  - 💰 **Paiements** : Gestion financière
  - ✅ **Présences** : Suivi d'assiduité

- **Interface d'administration**
  - Dashboard avec statistiques en temps réel
  - Design dark mode professionnel
  - Components Blade réutilisables
  - Navigation intuitive
  - Section outils administratifs avec Telescope

- **Outils de développement**
  - Laravel Telescope intégré
  - Logs système
  - Export de données
  - Cache optimization

#### Technique
- **Framework** : Laravel 12.19.3 LTS
- **Base de données** : MySQL 8.0.42 (28 tables)
- **Frontend** : Tailwind CSS (responsive)
- **Authentication** : Laravel Breeze + Spatie Permission
- **PHP** : 8.3.6
- **Architecture** : MVC avec Policies

#### Sécurité
- Protection CSRF sur tous les formulaires
- Validation complète des données
- Middleware de sécurité HasMiddleware (Laravel 12.19)
- Filtrage multi-tenant strict
- Consentement Loi 25

#### Performance
- Cache des vues optimisé
- Requêtes Eloquent optimisées
- Assets compilés avec Vite
- Structure de base de données normalisée

---

## Légende des types de changements

- **Ajouté** pour les nouvelles fonctionnalités
- **Modifié** pour les changements dans les fonctionnalités existantes
- **Déprécié** pour les fonctionnalités qui seront supprimées
- **Supprimé** pour les fonctionnalités supprimées
- **Corrigé** pour les corrections de bugs
- **Sécurité** pour les vulnérabilités corrigées

CHANGELOG_EOF

echo "✅ CHANGELOG.md corrigé"

# 3. Corriger CONTRIBUTING.md
echo ""
echo "📝 3. Correction CONTRIBUTING.md..."

cat > CONTRIBUTING.md << "CONTRIBUTING_EOF"
# Guide de Contribution - StudiosDB

Merci de votre intérêt pour contribuer à StudiosDB ! 🥋

## 🚀 Comment contribuer

### Signaler un bug

1. Vérifiez que le bug n'a pas déjà été signalé dans les [Issues](https://github.com/lalphadb/studiosunisdb/issues)
2. Créez une nouvelle issue avec le template "Bug Report"
3. Incluez les détails techniques (PHP, Laravel, navigateur)
4. Ajoutez des captures d'écran si pertinent

### Proposer une fonctionnalité

1. Créez une issue avec le template "Feature Request"
2. Décrivez le besoin métier et la solution proposée
3. Attendez l'approbation avant de commencer le développement
4. Utilisez les [Discussions](https://github.com/lalphadb/studiosunisdb/discussions) pour les grandes idées

### Développement

#### Configuration

\`\`\`bash
# Fork et clone
git clone https://github.com/lalphadb/studiosunisdb.git
cd studiosunisdb

# Branche de développement
git checkout -b feature/nom-fonctionnalite

# Installation
composer install
npm install
cp .env.example .env
php artisan key:generate
\`\`\`

#### Standards de code

- Suivre PSR-12 pour le formatage PHP
- Utiliser les FormRequests pour la validation
- Implémenter les Policies pour les autorisations
- Documenter les méthodes publiques avec PHPDoc
- Tests unitaires requis pour les nouvelles fonctionnalités
- Utiliser le système HasMiddleware de Laravel 12.19
- Respecter l'architecture multi-tenant existante

#### Structure du code

\`\`\`php
// Exemple de contrôleur admin
class ExempleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:viewAny,App\\Models\\Exemple', only: ['index'])
        ];
    }
    
    public function index(Request $request)
    {
        // Filtrage multi-tenant obligatoire
        if (auth()->user()->hasRole('admin_ecole')) {
            $query->where('ecole_id', auth()->user()->ecole_id);
        }
    }
}
\`\`\`

#### Commit Messages

Format : `type(scope): description`

Types :
- `feat`: nouvelle fonctionnalité
- `fix`: correction de bug
- `docs`: documentation
- `style`: formatage
- `refactor`: refactoring
- `test`: tests
- `chore`: maintenance

Exemples :
- `feat(users): ajouter export CSV des membres`
- `fix(auth): corriger redirection après login`
- `docs(readme): mettre à jour instructions installation`

### Pull Request

1. Assurez-vous que tous les tests passent
2. Mettez à jour la documentation si nécessaire
3. Décrivez clairement les changements apportés
4. Liez les issues concernées avec `closes #123`
5. Ajoutez des captures d'écran pour les changements UI

## 📋 Checklist PR

- [ ] Tests passent (`php artisan test`)
- [ ] Code formaté avec Pint (`./vendor/bin/pint`)
- [ ] Analyse statique propre (`./vendor/bin/phpstan analyse`)
- [ ] Documentation mise à jour
- [ ] CHANGELOG.md mis à jour si nécessaire
- [ ] Pas de conflits avec main
- [ ] Filtrage multi-tenant respecté
- [ ] Protection CSRF ajoutée aux formulaires POST

## 🧪 Tests

\`\`\`bash
# Lancer tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=UserTest

# Coverage
php artisan test --coverage
\`\`\`

## 🔍 Outils de développement

- **Telescope** : Debugging et monitoring (http://localhost:8001/telescope)
- **Pint** : Formatage automatique du code PHP
- **PHPStan** : Analyse statique du code
- **Laravel Debugbar** : Debug des requêtes et performances

## 📖 Ressources

- [Documentation Laravel 12.x](https://laravel.com/docs/12.x)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Architecture du projet](docs/architecture.md)

## 🏷️ Releases

Les releases suivent le [Semantic Versioning](https://semver.org/lang/fr/) :
- **MAJOR** : changements incompatibles
- **MINOR** : nouvelles fonctionnalités compatibles
- **PATCH** : corrections de bugs

## 📞 Contact

- 📧 Email : support@studiosdb.com
- 💬 Discussions : [GitHub Discussions](https://github.com/lalphadb/studiosunisdb/discussions)
- 🐛 Issues : [GitHub Issues](https://github.com/lalphadb/studiosunisdb/issues)

Merci pour votre contribution ! 🙏
CONTRIBUTING_EOF

echo "✅ CONTRIBUTING.md corrigé"

# 4. Mettre à jour le script de nettoyage avec les bonnes références
echo ""
echo "📝 4. Correction script de nettoyage..."

cat > clean_git_repository.sh << "CLEAN_GIT_EOF"
#!/bin/bash
echo "🧹 NETTOYAGE COMPLET DU DÉPÔT GIT"
echo "================================"

echo "⚠️  ATTENTION : Cette opération va :"
echo "• Supprimer l'historique Git existant"
echo "• Créer un nouveau dépôt propre"
echo "• Faire un commit initial avec v1.0.0"
echo ""
read -p "Continuer ? (y/N): " confirm

if [[ \$confirm != [yY] ]]; then
    echo "Opération annulée."
    exit 0
fi

# Supprimer .git existant
rm -rf .git

# Initialiser nouveau dépôt
git init
git branch -M main

# Ajouter tous les fichiers
git add .

# Commit initial
git commit -m "feat: version initiale v1.0.0

🚀 StudiosDB - Système de gestion d'écoles de karaté

✨ Fonctionnalités principales:
- Architecture multi-tenant sécurisée
- 7 modules complets (Users, Écoles, Cours, Ceintures, etc.)
- Interface d'administration moderne
- Conformité Loi 25
- Laravel 12.19.3 + Tailwind CSS

📋 Modules inclus:
- 👤 Gestion des utilisateurs
- 🏫 Réseau d'écoles
- 📚 Planning des cours
- 🥋 Système de ceintures
- 🎯 Séminaires
- 💰 Paiements
- ✅ Présences

🛡️ Sécurité:
- Authentification Laravel Breeze
- Permissions Spatie
- Protection CSRF
- Validation complète
- Middleware HasMiddleware (Laravel 12.19)

Version: v1.0.0
Laravel: 12.19.3 LTS
PHP: 8.3.6
Database: MySQL 8.0.42"

# Créer tag de version
git tag -a "v1.0.0" -m "Version 1.0.0 - Release initiale

Première version stable de StudiosDB avec tous les modules fonctionnels.

Highlights:
- Système multi-tenant complet
- 7 modules de gestion
- Interface d'administration moderne
- Conformité réglementaire (Loi 25)
- Architecture Laravel professionnelle

Repository: https://github.com/lalphadb/studiosunisdb"

echo ""
echo "✅ DÉPÔT NETTOYÉ ET PRÊT !"
echo ""
echo "📋 PROCHAINES ÉTAPES :"
echo "1. Configurer le remote GitHub :"
echo "   git remote add origin https://github.com/lalphadb/studiosunisdb.git"
echo ""
echo "2. Pousser le code :"
echo "   git push -u origin main"
echo "   git push origin v1.0.0"
echo ""
echo "3. Créer une release sur GitHub avec le tag v1.0.0"
echo "   https://github.com/lalphadb/studiosunisdb/releases/new"
CLEAN_GIT_EOF

chmod +x clean_git_repository.sh

echo "✅ clean_git_repository.sh corrigé"

# 5. Mettre à jour le nom de l'application dans les vues
echo ""
echo "📝 5. Correction nom application dans les vues..."

# Corriger welcome.blade.php
sed -i 's/StudiosUnisDB/StudiosDB/g' resources/views/welcome.blade.php
sed -i 's/studiosunisdb/studiosdb/g' resources/views/welcome.blade.php

# Corriger login.blade.php
sed -i 's/StudiosUnisDB/StudiosDB/g' resources/views/auth/login.blade.php

# Corriger register.blade.php
sed -i 's/StudiosUnisDB/StudiosDB/g' resources/views/auth/register.blade.php

# Corriger layout admin
sed -i 's/StudiosUnisDB/StudiosDB/g' resources/views/layouts/admin.blade.php

echo "✅ Vues corrigées"

# 6. Mettre à jour config/app.php
echo ""
echo "📝 6. Correction configuration application..."

sed -i "s/'name' => env('APP_NAME', 'Laravel')/'name' => env('APP_NAME', 'StudiosDB')/g" config/app.php

echo "✅ Configuration app corrigée"

echo ""
echo "✅ TOUTES LES RÉFÉRENCES CORRIGÉES !"
echo ""
echo "📋 CHANGEMENTS APPLIQUÉS :"
echo "• Nom du projet : StudiosDB"
echo "• GitHub : https://github.com/lalphadb/studiosunisdb"
echo "• Email : support@studiosdb.com"
echo "• Site web : studiosdb.com"
echo "• Version : v1.0.0"
echo ""
echo "🚀 PRÊT POUR LE COMMIT ET PUSH :"
echo "./clean_git_repository.sh"

# Changelog - StudiosDB v5 Pro

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère au [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [5.5.0] - 2025-08-08

### ✨ Ajouté

- Middleware EnsureProfileComplete (forçage complétion profil).
- Pages Vue Privacy/Policy & Consentements (Loi 25) + partages flash.
- Toast notifications Inertia globales.
- Tests: RegistrationConsentTest, CoursCreationAvecHorairesTest, LienFamilialControllerTest.

### 🔧 Modifié

- Import statique du layout AuthenticatedLayout (suppression avertissement Vite).
- Création cours: valeurs par défaut champs legacy & sécurité accès.

### 🐛 Corrigé

- Erreurs NOT NULL et undefined keys lors création de cours.
- Redirections profil incomplet.

### 📦 Maintenance

- Bump version applicative à 5.5.0.


## [5.4.0] - 2025-08-05

### ✨ Ajouté
- **Dashboard Ultra-Professionnel** avec thème clair moderne
- **Gestion adaptative des tables** - Le système vérifie l'existence des tables avant usage
- **Cache intelligent** avec TTL par rôle utilisateur
- **API métriques temps réel** pour analytics dynamiques
- **Gestion d'erreurs robuste** avec fallback automatique
- **Interface présences tablette** optimisée pour écrans tactiles
- **Système de notifications** en temps réel
- **Répartition ceintures** avec visualisation par couleurs

### 🔧 Modifié
- **DashboardController** complètement refactorisé pour éviter les erreurs SQL
- **Navigation** adaptative selon les rôles utilisateur
- **Design system** unifié avec Tailwind CSS avancé
- **Compilation assets** optimisée avec Vite
- **Cache stratégie** améliorée pour les performances

### 🐛 Corrigé
- **Erreur SQL** sur table `progression_ceintures` inexistante
- **Permissions logs** Laravel corrigées définitivement  
- **Compilation assets** stabilisée avec gestion d'erreurs
- **Dashboard responsive** sur tous les écrans
- **Navigation** fonctionnelle entre les modules

### 🗑️ Supprimé
- **Anciens dashboards** de test et debug
- **Code legacy** des versions précédentes
- **Dépendances** non utilisées nettoyées

## [5.3.0] - 2025-07-20

### ✨ Ajouté
- **Architecture multi-tenant** complète avec Stancl/Tenancy
- **Système rôles** granulaire avec Spatie/Permission
- **Module Membres** complet (CRUD + progressions)
- **Module Cours** avec planning dynamique
- **Module Présences** avec interface tablette
- **Module Paiements** avec facturation automatique
- **Authentication** Laravel Breeze + Fortify
- **Design system** Tailwind CSS intégré

### 🔧 Modifié
- **Base Laravel** mise à jour vers 12.21.x
- **Vue.js** migration vers 3.5 avec Composition API
- **Inertia.js** intégré pour SPA fluide
- **Structure projet** organisée en modules

### 🐛 Corrigé
- **Migrations** optimisées pour performance
- **Routes** sécurisées avec middleware
- **Validation** des données renforcée

## [5.2.0] - 2025-07-01

### ✨ Ajouté
- **Migration** depuis StudiosDB v4
- **Nouvelle architecture** Laravel moderne
- **API REST** pour intégrations futures
- **Tests automatisés** PHPUnit + Pest

### 🔧 Modifié
- **Database schema** optimisé avec indexes
- **Performance** améliorée avec cache Redis
- **Sécurité** renforcée CSRF + XSS

## [5.1.0] - 2025-06-15

### ✨ Ajouté
- **Prototype initial** StudiosDB v5
- **Concepts UI/UX** modernes
- **Architecture technique** définie

## [5.0.0] - 2025-06-01

### ✨ Ajouté
- **Projet StudiosDB v5** initialisé
- **Spécifications** fonctionnelles définies
- **Équipe** de développement constituée

---

## Types de changements

- `✨ Ajouté` pour les nouvelles fonctionnalités
- `🔧 Modifié` pour les modifications de fonctionnalités existantes  
- `🐛 Corrigé` pour les corrections de bugs
- `🗑️ Supprimé` pour les fonctionnalités supprimées
- `🔒 Sécurité` pour les correctifs de sécurité
- `📦 Dépendances` pour les mises à jour de dépendances
- `🚀 Performance` pour les améliorations de performance

## Liens utiles

- [Issues GitHub](https://github.com/studiosdb/studiosdb-v5-pro/issues)
- [Milestones](https://github.com/studiosdb/studiosdb-v5-pro/milestones)
- [Releases](https://github.com/studiosdb/studiosdb-v5-pro/releases)
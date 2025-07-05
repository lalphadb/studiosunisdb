# Architecture StudiosDB v4.1.10.2

## Vue d'ensemble
StudiosDB suit strictement les standards Laravel 12 avec une architecture multi-tenant sécurisée.

## Patterns Obligatoires

### BaseAdminController Pattern
Tous les contrôleurs admin héritent de `BaseAdminController` :
- Gestion centralisée des erreurs
- Logging automatique des actions
- Middleware de sécurité standardisé
- Multi-tenant scope application

### Components System Laravel 12
Structure `resources/views/components/` avec :
- Components UI réutilisables
- Navigation standardisée
- Design system cohérent
- Performance optimisée

### Multi-tenant Security
- Global Scopes sur tous les modèles
- Filtrage automatique par `ecole_id`
- Permissions Spatie granulaires
- Isolation stricte des données

## Modules Validés
Chaque module suit le pattern CRUD standardisé avec :
- Controller héritant BaseAdminController
- Form Requests dédiées
- Policies de sécurité
- Views avec components StudiosDB

## Performance
- Cache Laravel optimisé
- Assets compilés avec Vite
- Queries optimisées avec relations
- Pagination standardisée

# 🏷️ STUDIOSDB - SYSTÈME DE VERSIONING GIT PROFESSIONNEL

## 📌 VERSION ACTUELLE: v4.1.10.2

### Structure de Version
```
v[MAJOR].[MINOR].[PATCH].[BUILD]
   │        │       │       └── Numéro de build (fixes mineurs)
   │        │       └────────── Corrections de bugs
   │        └────────────────── Nouvelles fonctionnalités
   └───────────────────────── Changements majeurs/breaking changes
```

## 🌿 STRUCTURE DES BRANCHES

```mermaid
main (v4.1.10.2) - PRODUCTION
├── hotfix/critical-auth-fix
└── release/v4.2.0
    └── develop
        ├── feature/module-reports
        ├── feature/api-mobile
        ├── fix/user-permissions
        └── chore/update-dependencies
```

### Branches Permanentes
- **main**: Production (protégée, tags uniquement)
- **develop**: Développement intégré
- **release/vX.X.X**: Préparation des releases

### Branches Temporaires
- **feature/**: Nouvelles fonctionnalités
- **fix/**: Corrections de bugs
- **hotfix/**: Corrections urgentes de production
- **chore/**: Maintenance, dépendances

## 🏷️ SYSTÈME DE TAGS

### Convention de Nommage
```bash
# Release officielle
v4.2.0

# Release candidate
v4.2.0-rc.1

# Beta
v4.2.0-beta.1

# Checkpoint de développement
checkpoint-20250709-1430

# État stable
stable-4.1.10-production

# Avant modification majeure
before-auth-refactor
```

### Commandes de Tagging
```bash
# Créer un tag de release
git tag -a v4.2.0 -m "Release: Version 4.2.0
- Feature: Module reports
- Feature: Export PDF amélioré
- Fix: Permissions multi-tenant
- Fix: Performance dashboard"

# Créer un checkpoint daté
git tag -a checkpoint-$(date +%Y%m%d-%H%M%S) -m "Checkpoint: État stable avant refactoring auth"

# Créer un tag de feature
git tag -a feature-reports-complete -m "Feature: Module reports complet et testé"

# Pousser les tags
git push origin --tags

# Pousser un tag spécifique
git push origin v4.2.0
```

## 📋 WORKFLOW DE VERSIONING

### 1. Nouvelle Feature
```bash
# Depuis develop
git checkout develop
git pull origin develop

# Créer feature branch
git checkout -b feature/module-inventaire

# Développer...
git add .
git commit -m "feat(inventaire): add base CRUD operations"
git commit -m "feat(inventaire): add multi-tenant support"
git commit -m "test(inventaire): add unit tests"

# Checkpoint avant merge
git tag -a feature-inventaire-ready -m "Feature: Inventaire prêt pour review"

# Push et PR
git push origin feature/module-inventaire
```

### 2. Préparation Release
```bash
# Créer branch release depuis develop
git checkout develop
git checkout -b release/v4.2.0

# Mettre à jour la version
echo "4.2.0" > VERSION
sed -i 's/4.1.10/4.2.0/g' composer.json
sed -i 's/4.1.10/4.2.0/g' config/app.php

# Mettre à jour CHANGELOG
vim CHANGELOG.md

# Commit de version
git add .
git commit -m "chore(release): prepare v4.2.0"

# Tests finaux...

# Merger dans main
git checkout main
git merge --no-ff release/v4.2.0

# Tagger
git tag -a v4.2.0 -m "Release: Version 4.2.0 stable"

# Merger dans develop
git checkout develop
git merge --no-ff release/v4.2.0

# Push tout
git push origin main develop --tags
```

### 3. Hotfix Production
```bash
# Depuis main
git checkout main
git checkout -b hotfix/critical-login-issue

# Fix...
git add .
git commit -m "hotfix(auth): fix Livewire login redirect"

# Merger dans main
git checkout main
git merge --no-ff hotfix/critical-login-issue

# Tagger
git tag -a v4.1.10.3 -m "Hotfix: Login redirect issue"

# Merger dans develop
git checkout develop
git merge --no-ff hotfix/critical-login-issue

# Push
git push origin main develop --tags
```

## 📝 CONVENTION DE COMMIT

### Format
```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types
- **feat**: Nouvelle fonctionnalité
- **fix**: Correction de bug
- **docs**: Documentation uniquement
- **style**: Formatage (pas de changement de code)
- **refactor**: Restructuration du code
- **perf**: Amélioration des performances
- **test**: Ajout de tests
- **chore**: Maintenance (deps, config, etc.)
- **hotfix**: Fix urgent de production

### Exemples
```bash
# Feature
git commit -m "feat(users): add bulk CSV import

- Parse CSV with validation
- Background job for large files
- Email notification on completion
- Progress tracking in UI

Closes #123"

# Fix
git commit -m "fix(auth): correct Livewire login redirect

The login was redirecting to /dashboard instead of /admin/dashboard
for admin users. Fixed RouteServiceProvider HOME constant.

Fixes #456"

# Hotfix
git commit -m "hotfix(payments): critical calculation error

Emergency fix for tax calculation in Quebec region.
Was using 15% instead of 14.975%.

Deployed to production immediately."
```

## 🔖 GESTION DES RELEASES

### Checklist Pre-Release
```markdown
## Release Checklist v[X.X.X]

### Code
- [ ] Tous les tests passent
- [ ] Aucun TODO/FIXME critique
- [ ] Code review complète
- [ ] Performance acceptable

### Documentation
- [ ] CHANGELOG.md à jour
- [ ] README.md à jour
- [ ] API docs à jour
- [ ] Version dans composer.json

### Base de données
- [ ] Migrations testées
- [ ] Rollback possible
- [ ] Seeders à jour
- [ ] Backup de production

### Sécurité
- [ ] Scan de vulnérabilités
- [ ] Permissions vérifiées
- [ ] Logs sensibles retirés
- [ ] .env.example à jour

### Déploiement
- [ ] Tag Git créé
- [ ] Build assets (npm run build)
- [ ] Cache vidé
- [ ] Monitoring configuré
```

### Script de Release
```bash
cat > make-release.sh << 'EOF'
#!/bin/bash

# Usage: ./make-release.sh [major|minor|patch]

TYPE=${1:-patch}
CURRENT_VERSION=$(cat VERSION)

echo "Current version: $CURRENT_VERSION"

# Calculate new version
IFS='.' read -ra VERSION_PARTS <<< "$CURRENT_VERSION"
MAJOR=${VERSION_PARTS[0]}
MINOR=${VERSION_PARTS[1]}
PATCH=${VERSION_PARTS[2]}

case $TYPE in
    major)
        MAJOR=$((MAJOR + 1))
        MINOR=0
        PATCH=0
        ;;
    minor)
        MINOR=$((MINOR + 1))
        PATCH=0
        ;;
    patch)
        PATCH=$((PATCH + 1))
        ;;
esac

NEW_VERSION="$MAJOR.$MINOR.$PATCH"
echo "New version: $NEW_VERSION"

# Update files
echo $NEW_VERSION > VERSION
sed -i "s/$CURRENT_VERSION/$NEW_VERSION/g" composer.json
sed -i "s/$CURRENT_VERSION/$NEW_VERSION/g" config/app.php

# Git operations
git add .
git commit -m "chore(release): bump version to $NEW_VERSION"
git tag -a "v$NEW_VERSION" -m "Release version $NEW_VERSION"

echo "✅ Release v$NEW_VERSION prepared!"
echo "Run: git push origin main --tags"
EOF

chmod +x make-release.sh
```

## 📊 HISTORIQUE DES VERSIONS

### v4.1.10.2 (Current) - 2025-01-09
- fix: Structure du projet nettoyée
- fix: Livewire auth configuré
- chore: Documentation mise à jour

### v4.1.10.1 - 2025-01-08
- fix: Migrations ordre corrigé
- feat: BaseAdminController ajouté

### v4.1.10.0 - 2025-01-07
- feat: Multi-tenant avec ecole_id
- feat: 18 modules admin complets

### v4.1.0.0 - 2024-12-15
- breaking: Migration vers Laravel 12
- feat: Livewire auth system
- feat: Spatie permissions

## 🚀 COMMANDES RAPIDES

```bash
# Voir la version actuelle
cat VERSION
git describe --tags --always

# Lister les tags
git tag -l "v*"

# Voir les détails d'un tag
git show v4.1.10

# Revenir à une version
git checkout v4.1.10

# Comparer versions
git diff v4.1.0..v4.1.10

# Changelog entre versions
git log v4.1.0..v4.1.10 --oneline

# Créer un backup avant modification
git tag -a backup-$(date +%Y%m%d) -m "Backup avant modification majeure"
```

## 🔄 WORKFLOW DE RÉCUPÉRATION

### Si quelque chose va mal
```bash
# 1. Identifier le dernier tag stable
git tag -l "*stable*" | tail -1

# 2. Créer une branche de récupération
git checkout -b recovery/fix-issue <tag-stable>

# 3. Cherry-pick les bons commits
git cherry-pick <commit-hash>

# 4. Tester
php artisan test

# 5. Si OK, merger
git checkout develop
git merge recovery/fix-issue
```

---

💼 **Ce système garantit une gestion professionnelle des versions avec traçabilité complète.**

#!/bin/bash
# setup-studiosdb-github.sh

echo "📦 Installation du workflow StudiosDB..."

# 1. Créer develop si elle n'existe pas
if ! git show-ref --verify --quiet refs/heads/develop; then
    echo "✅ Création de la branche develop..."
    git checkout -b develop
    git push -u origin develop
fi

# 2. Créer le Makefile minimal
if [ ! -f "Makefile" ]; then
    echo "✅ Création du Makefile..."
    cat > Makefile << 'MAKEFILE'
.PHONY: help dev test clear lint install

help:
	@echo "StudiosDB Commands:"
	@echo "  make install - Install dependencies"
	@echo "  make dev     - Start development servers"
	@echo "  make test    - Run tests"
	@echo "  make clear   - Clear all caches"

install:
	composer install
	npm install

dev:
	php artisan serve & npm run dev

test:
	php artisan test

clear:
	php artisan optimize:clear

lint:
	@echo "Lint not configured yet"
MAKEFILE
fi

# 3. Créer structure GitHub
echo "✅ Création de la structure GitHub..."
mkdir -p .github/workflows
mkdir -p .github/ISSUE_TEMPLATE

# 4. Créer un workflow CI minimal
cat > .github/workflows/ci.yml << 'WORKFLOW'
name: CI
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v4
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.3'
    - name: Install Dependencies
      run: composer install
    - name: Run Tests
      run: php artisan test
WORKFLOW

echo "✅ Installation terminée!"
echo ""
echo "Commandes disponibles:"
echo "  make dev    - Lancer le serveur de développement"
echo "  make test   - Lancer les tests"
echo "  make clear  - Vider les caches"
echo ""
echo "Prochaines étapes:"
echo "1. git add ."
echo "2. git commit -m 'feat: add GitHub workflow and Makefile'"
echo "3. git push origin develop"

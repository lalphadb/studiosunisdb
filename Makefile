# ========================================
# STUDIOSDB ENTERPRISE - MAKEFILE PROPRE
# ========================================

.PHONY: help dev test clean install status

# Aide
help:
	@echo "🚀 StudiosDB Enterprise v4.1.10.2 - Commandes disponibles:"
	@echo ""
	@echo "  📦 INSTALLATION:"
	@echo "    install     - Installation complète du projet"
	@echo "    composer    - Installation des dépendances PHP"
	@echo "    npm         - Installation des dépendances NPM"
	@echo ""
	@echo "  🔧 DÉVELOPPEMENT:"
	@echo "    dev         - Démarrer le serveur de développement"
	@echo "    build       - Compiler les assets pour production"
	@echo "    watch       - Watch des assets en temps réel"
	@echo ""
	@echo "  🧪 TESTS:"
	@echo "    test        - Lancer tous les tests"
	@echo "    test-unit   - Tests unitaires uniquement"
	@echo "    test-feature - Tests de fonctionnalités"
	@echo ""
	@echo "  🔍 QUALITÉ:"
	@echo "    status      - Statut complet du projet"
	@echo "    check       - Vérifications de santé"
	@echo "    clean       - Nettoyage des caches"
	@echo ""
	@echo "  👥 UTILISATEURS:"
	@echo "    admin       - Créer un utilisateur admin"
	@echo "    users       - Lister les utilisateurs"
	@echo ""

# Installation
install: composer npm
	@php artisan key:generate --ansi
	@php artisan migrate
	@php artisan db:seed
	@echo "✅ Installation terminée!"

composer:
	@composer install --optimize-autoloader

npm:
	@npm install
	@npm run build

# Développement
dev:
	@echo "🚀 Démarrage du serveur de développement..."
	@php artisan serve --host=0.0.0.0 --port=8001

build:
	@npm run build

watch:
	@npm run dev

# Tests
test:
	@php artisan test

test-unit:
	@php artisan test --testsuite=Unit

test-feature:
	@php artisan test --testsuite=Feature

# Maintenance
clean:
	@php artisan optimize:clear
	@php artisan config:cache
	@php artisan route:cache
	@php artisan view:cache
	@echo "✅ Caches nettoyés et reconstruits"

status:
	@php artisan studiosdb:status

check:
	@echo "🔍 Vérification de la santé du projet..."
	@php artisan --version
	@curl -s http://localhost:8001/api/health | jq . || echo "⚠️  Serveur non démarré"

# Utilisateurs
admin:
	@php artisan studiosdb:dev-users
	@echo "✅ Utilisateurs admin créés"

users:
	@php artisan tinker --execute="App\Models\User::with('roles')->get()->each(fn(\$$u) => print(\$$u->name . ' (' . \$$u->email . ') - ' . \$$u->roles->pluck('name')->join(', ') . PHP_EOL));"

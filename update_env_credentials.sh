#!/bin/bash

echo "⚙️ MISE À JOUR .ENV AVEC VRAIES CREDENTIALS"
echo "=========================================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

# Backup .env actuel
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

# Génération clé Laravel
NEW_KEY=$(php artisan key:generate --show)

# Configuration .env avec vraies informations
cat > .env << 'ENV_CONTENT'
# STUDIOSDB V5 PRO - CONFIGURATION CORRIGÉE
APP_NAME="StudiosDB v5 Pro"
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=America/Montreal
APP_URL=http://localhost:8001
APP_LOCALE=fr
APP_FALLBACK_LOCALE=en

# DATABASE CORRIGÉE
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=studiosdb_central
DB_USERNAME=studiosdb
DB_PASSWORD=StudioSDB_2025!Secure

# MULTI-TENANT STANCL
TENANCY_DATABASE_PREFIX=studiosdb_ecole_
CENTRAL_DOMAINS=localhost,127.0.0.1

# CACHE FILE (pas Redis pour debug)
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# MAIL LOCAL
MAIL_MAILER=log
MAIL_HOST=localhost
MAIL_PORT=2525
MAIL_FROM_ADDRESS=studiosdb@localhost
MAIL_FROM_NAME="StudiosDB v5 Pro"

# VITE DEV
VITE_APP_NAME="${APP_NAME}"
VITE_APP_ENV="${APP_ENV}"
VITE_APP_URL="${APP_URL}"

# DEBUG
DEBUGBAR_ENABLED=true
LOG_CHANNEL=single
LOG_LEVEL=debug
ENV_CONTENT

# Ajout clé générée
echo "APP_KEY=$NEW_KEY" >> .env

echo "✅ .env mis à jour avec vraies informations"
cat .env | grep -E "DB_|APP_KEY"

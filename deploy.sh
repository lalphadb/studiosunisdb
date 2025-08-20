#!/bin/bash

# Auto Deploy Tool pour StudiosDB
# ================================

PROJECT_DIR="/home/studiosdb/studiosunisdb"
BACKUP_DIR="/home/studiosdb/backups"
DATE=$(date +"%Y%m%d_%H%M%S")

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

function log() {
    echo -e "${BLUE}[$(date +'%H:%M:%S')]${NC} $1"
}

function error() {
    echo -e "${RED}[ERROR]${NC} $1"
    exit 1
}

function success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

function warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

function backup() {
    log "Creating backup..."
    
    mkdir -p $BACKUP_DIR
    
    # Backup database
    log "Backing up database..."
    php $PROJECT_DIR/mysql_helper.php backup "$BACKUP_DIR/db_backup_$DATE.sql"
    
    # Backup .env
    cp $PROJECT_DIR/.env "$BACKUP_DIR/env_backup_$DATE"
    
    # Backup storage
    log "Backing up storage..."
    tar -czf "$BACKUP_DIR/storage_backup_$DATE.tar.gz" -C $PROJECT_DIR storage/
    
    success "Backup completed: $BACKUP_DIR/*_$DATE*"
}

function deploy_production() {
    log "Starting PRODUCTION deployment..."
    
    cd $PROJECT_DIR || error "Cannot access project directory"
    
    # 1. Backup first
    backup
    
    # 2. Enable maintenance mode
    log "Enabling maintenance mode..."
    php artisan down --render="errors::503" --retry=60
    
    # 3. Pull latest code
    log "Pulling latest code..."
    git pull origin main || warning "Git pull failed or no changes"
    
    # 4. Install dependencies
    log "Installing composer dependencies..."
    composer install --no-dev --optimize-autoloader
    
    log "Installing npm dependencies..."
    npm ci
    
    # 5. Build assets
    log "Building production assets..."
    npm run build
    
    # 6. Run migrations
    log "Running migrations..."
    php artisan migrate --force
    
    # 7. Clear and cache
    log "Optimizing application..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    
    # 8. Set permissions
    log "Setting permissions..."
    chmod -R 755 storage bootstrap/cache
    chown -R studiosdb:www-data storage bootstrap/cache
    
    # 9. Restart services
    log "Restarting services..."
    systemctl reload php8.3-fpm
    
    # 10. Disable maintenance mode
    log "Disabling maintenance mode..."
    php artisan up
    
    success "Production deployment completed!"
}

function deploy_dev() {
    log "Starting DEVELOPMENT deployment..."
    
    cd $PROJECT_DIR || error "Cannot access project directory"
    
    # 1. Pull latest code
    log "Pulling latest code..."
    git pull || warning "Git pull failed or no changes"
    
    # 2. Install dependencies
    log "Installing composer dependencies..."
    composer install
    
    log "Installing npm dependencies..."
    npm install
    
    # 3. Run migrations
    log "Running migrations..."
    php artisan migrate
    
    # 4. Seed database (optional)
    if [ "$1" == "--seed" ]; then
        log "Seeding database..."
        php artisan db:seed
    fi
    
    # 5. Clear caches
    log "Clearing caches..."
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    
    # 6. Generate IDE helper files
    log "Generating IDE helpers..."
    php artisan ide-helper:generate 2>/dev/null || true
    php artisan ide-helper:models --nowrite 2>/dev/null || true
    
    success "Development deployment completed!"
}

function rollback() {
    log "Starting rollback..."
    
    if [ -z "$1" ]; then
        error "Please specify backup date (format: YYYYMMDD_HHMMSS)"
    fi
    
    BACKUP_DATE=$1
    
    # 1. Enable maintenance mode
    log "Enabling maintenance mode..."
    php artisan down
    
    # 2. Restore database
    if [ -f "$BACKUP_DIR/db_backup_$BACKUP_DATE.sql" ]; then
        log "Restoring database..."
        mysql -u root -pLkmP0km1 studiosdb < "$BACKUP_DIR/db_backup_$BACKUP_DATE.sql"
    else
        error "Database backup not found: $BACKUP_DIR/db_backup_$BACKUP_DATE.sql"
    fi
    
    # 3. Restore .env
    if [ -f "$BACKUP_DIR/env_backup_$BACKUP_DATE" ]; then
        log "Restoring .env file..."
        cp "$BACKUP_DIR/env_backup_$BACKUP_DATE" $PROJECT_DIR/.env
    fi
    
    # 4. Restore storage
    if [ -f "$BACKUP_DIR/storage_backup_$BACKUP_DATE.tar.gz" ]; then
        log "Restoring storage..."
        rm -rf $PROJECT_DIR/storage
        tar -xzf "$BACKUP_DIR/storage_backup_$BACKUP_DATE.tar.gz" -C $PROJECT_DIR/
    fi
    
    # 5. Clear caches
    log "Clearing caches..."
    php artisan cache:clear
    php artisan config:clear
    
    # 6. Disable maintenance mode
    log "Disabling maintenance mode..."
    php artisan up
    
    success "Rollback completed to: $BACKUP_DATE"
}

function health_check() {
    log "Running health check..."
    
    cd $PROJECT_DIR
    
    # Check Laravel
    php artisan --version > /dev/null 2>&1 && success "Laravel OK" || error "Laravel ERROR"
    
    # Check Database
    php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1 && success "Database OK" || error "Database ERROR"
    
    # Check storage permissions
    [ -w "$PROJECT_DIR/storage" ] && success "Storage writable" || warning "Storage not writable"
    
    # Check services
    curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001 | grep -q "200\|302" && success "Laravel server responding" || warning "Laravel server not responding"
    
    success "Health check completed"
}

# Menu principal
case "${1:-help}" in
    production)
        deploy_production
        ;;
    dev)
        deploy_dev $2
        ;;
    backup)
        backup
        ;;
    rollback)
        rollback $2
        ;;
    health)
        health_check
        ;;
    *)
        echo -e "${BLUE}═══════════════════════════════════════${NC}"
        echo -e "${BLUE}   AUTO DEPLOY TOOL${NC}"
        echo -e "${BLUE}═══════════════════════════════════════${NC}"
        echo ""
        echo "Usage: $0 [command] [options]"
        echo ""
        echo "Commands:"
        echo "  production           - Deploy to production"
        echo "  dev [--seed]        - Deploy to development"
        echo "  backup              - Create backup only"
        echo "  rollback [date]     - Rollback to specific backup"
        echo "  health              - Run health check"
        echo ""
        echo "Examples:"
        echo "  $0 production"
        echo "  $0 dev --seed"
        echo "  $0 rollback 20240117_143000"
        echo ""
        echo "Backup location: $BACKUP_DIR"
        ;;
esac

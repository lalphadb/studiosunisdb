# 🚀 Guide d'Installation StudiosDB v5 Pro

## Prérequis Serveur

### Ubuntu 24.04 LTS
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install nginx mysql-server redis-server
```

### PHP 8.3
```bash
sudo apt install php8.3-fpm php8.3-mysql php8.3-redis php8.3-gd php8.3-zip php8.3-xml
```

### Node.js & NPM
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs
```

### Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

## Installation Application

### 1. Clonage Repository
```bash
cd /home/studiosdb/studiosunisdb/
git clone [REPO_URL] studiosdb_v5_pro
cd studiosdb_v5_pro
```

### 2. Dépendances
```bash
composer install --optimize-autoloader --no-dev
npm ci
```

### 3. Configuration
```bash
cp .env.example .env
php artisan key:generate
nano .env  # Éditer configuration
```

### 4. Base de Données
```bash
mysql -u root -p
CREATE DATABASE studiosdb_central;
CREATE USER 'studiosdb'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON studiosdb_central.* TO 'studiosdb'@'localhost';
FLUSH PRIVILEGES;
exit;

php artisan migrate
php artisan db:seed
```

### 5. Compilation Assets
```bash
npm run build
```

### 6. Permissions
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 7. Configuration Nginx
```nginx
# Copier configuration fournie dans le README principal
sudo nano /etc/nginx/sites-available/4lb.ca
sudo ln -s /etc/nginx/sites-available/4lb.ca /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 8. SSL/HTTPS (Production)
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d 4lb.ca -d app.4lb.ca
```

## Vérification Installation

```bash
# Test connexion
curl http://4lb.ca/dashboard

# Logs
tail -f storage/logs/laravel.log
sudo tail -f /var/log/nginx/4lb.ca.error.log
```

## Maintenance

```bash
# Backup quotidien
php artisan backup:run

# Cache refresh
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

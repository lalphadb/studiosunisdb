# 🔧 StudiosDB v5 - Référence Technique

## Architecture Détaillée

### Base de Données - Structure Complète

```sql
-- Tables Principales
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE membres (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    prenom VARCHAR(255),
    nom VARCHAR(255),
    date_naissance DATE,
    statut ENUM('actif', 'inactif', 'suspendu'),
    ceinture_actuelle_id BIGINT,
    -- Plus de champs...
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_statut_date (statut, date_derniere_presence)
);

CREATE TABLE cours (
    id BIGINT PRIMARY KEY,
    nom VARCHAR(255),
    instructeur_id BIGINT,
    niveau ENUM('debutant', 'intermediaire', 'avance'),
    actif BOOLEAN DEFAULT TRUE,
    -- Configuration horaires...
    FOREIGN KEY (instructeur_id) REFERENCES users(id),
    INDEX idx_actif_jour (actif, jour_semaine)
);
```

### Performance - Requête Dashboard Optimisée

```php
// UNE SEULE REQUÊTE pour toutes les métriques
private function calculateStatsOptimized(): array
{
    $metriques = DB::selectOne("
        SELECT 
            (SELECT COUNT(*) FROM membres) as total_membres,
            (SELECT COUNT(*) FROM membres WHERE statut = 'actif') as membres_actifs,
            (SELECT COUNT(*) FROM cours WHERE actif = 1) as cours_actifs,
            (SELECT COUNT(*) FROM presences WHERE DATE(date_cours) = CURDATE()) as presences_aujourdhui,
            (SELECT COALESCE(SUM(montant), 0) FROM paiements 
             WHERE statut = 'paye' AND DATE(date_paiement) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) as revenus_mois
    ");
    
    return [
        'total_membres' => (int)($metriques->total_membres ?? 0),
        'membres_actifs' => (int)($metriques->membres_actifs ?? 0),
        // ... autres métriques
    ];
}
```

### Cache Strategy

```php
// Cache métriques dashboard (5 minutes)
$stats = Cache::remember('dashboard_metrics_user_' . $userId, 300, function() {
    return $this->calculateStatsOptimized();
});

// Cache API temps réel (30 secondes)
$realtime = Cache::remember('realtime_metrics_' . $userId, 30, function() {
    return $this->getRealTimeMetrics();
});
```

## API Endpoints Complets

### Authentication
```
POST /login
POST /logout  
POST /register
POST /password/reset
```

### Dashboard
```
GET  /dashboard
GET  /api/dashboard/metriques
POST /api/dashboard/clear-cache
```

### Membres
```
GET    /membres                 # Liste paginée
POST   /membres                 # Création
GET    /membres/{id}            # Détail
PUT    /membres/{id}            # Modification
DELETE /membres/{id}            # Suppression
POST   /membres/{id}/ceinture   # Changement ceinture
GET    /export/membres          # Export Excel/PDF
```

### Cours & Planning
```
GET    /cours                   # Liste cours
POST   /cours                   # Nouveau cours
GET    /cours/{id}              # Détail cours
PUT    /cours/{id}              # Modification
DELETE /cours/{id}              # Suppression
POST   /cours/{id}/duplicate    # Duplication
GET    /planning-cours          # Vue calendrier
```

### Présences
```
GET  /presences                 # Historique
GET  /presences/tablette        # Interface tactile
POST /presences/marquer         # Marquage présences
GET  /presences/rapports        # Analytics
```

### Paiements
```
GET    /paiements               # Liste paiements
POST   /paiements               # Nouveau paiement  
PATCH  /paiements/{id}/confirmer # Confirmation
POST   /paiements/rappels       # Rappels automatiques
GET    /paiements/export        # Export comptable
```

## Déploiement Production

### Configuration Nginx Optimisée

```nginx
server {
    listen 443 ssl http2;
    server_name studiosdb.ca *.studiosdb.ca;
    
    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/studiosdb.ca/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/studiosdb.ca/privkey.pem;
    
    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    
    # Laravel Configuration
    root /var/www/studiosdb_v5_pro/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Performance
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }
    
    # Static Assets Optimization
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
    
    # Deny access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Scripts de Maintenance

```bash
#!/bin/bash
# maintenance-daily.sh

echo "📅 Maintenance quotidienne StudiosDB v5"

# Backup base de données
php artisan backup:run --only-db

# Nettoyage logs (garder 30 jours)
find storage/logs -name "*.log" -mtime +30 -delete

# Optimisation base de données
php artisan db:optimize

# Nettoyage cache expiré
php artisan cache:prune-stale-tags

# Vérification santé système
php artisan health:check

echo "✅ Maintenance terminée"
```

## Troubleshooting Guide

### Problèmes Courants

1. **Dashboard Lent**
   ```bash
   # Vérifier cache Redis
   redis-cli ping
   
   # Analyser requêtes
   php artisan telescope:clear
   
   # Optimiser cache
   php artisan optimize
   ```

2. **Erreur 500**
   ```bash
   # Logs détaillés
   tail -f storage/logs/laravel.log
   
   # Permissions fichiers
   sudo chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

3. **Assets Manquants**
   ```bash
   # Rebuild assets
   npm run build
   
   # Vérifier Vite
   npm run dev
   ```

### Monitoring Performance

```bash
# Métriques temps réel
curl -s http://studiosdb.local/api/dashboard/metriques | jq

# Temps réponse dashboard
curl -w "Temps: %{time_total}s\n" -s -o /dev/null http://studiosdb.local/dashboard

# Status services
systemctl status nginx php8.3-fpm mysql redis-server
```

## Standards de Développement

### Code Style (PSR-12)

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Contrôleur avec documentation PHPDoc
 * 
 * @package App\Http\Controllers
 * @version 5.1.0
 */
final class ExampleController extends Controller
{
    /**
     * Constantes pour configuration
     */
    private const CACHE_DURATION = 300;
    
    /**
     * Méthode avec types stricts
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = Cache::remember('key', self::CACHE_DURATION, function () {
                return $this->calculateData();
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error message', ['context' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error occurred',
            ], 500);
        }
    }
    
    /**
     * Méthode privée avec logique métier
     */
    private function calculateData(): array
    {
        // Implémentation...
        return [];
    }
}
```

### Tests Structure

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function dashboard_loads_successfully_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->get('/dashboard');
        
        $response->assertStatus(200)
                 ->assertInertia(fn ($page) => 
                     $page->component('Dashboard/Admin')
                          ->has('stats')
                          ->has('user')
                 );
    }
    
    /** @test */  
    public function dashboard_metrics_api_returns_valid_data(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->getJson('/api/dashboard/metriques');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'timestamp',
                         'system_status'
                     ]
                 ]);
    }
}
```

Dernière mise à jour: Août 2025
Version: 5.1.2

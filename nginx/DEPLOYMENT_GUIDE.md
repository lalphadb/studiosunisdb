# Configuration HTTPS Nginx + Cloudflare pour 4lb.ca

## 📋 Checklist de déploiement

### 1. Configuration Cloudflare (à faire en premier)

#### A. DNS
```
Type    Name    Content             Proxy
A       @       IP_DU_SERVEUR      ☁️ Proxied
A       www     IP_DU_SERVEUR      ☁️ Proxied
```

#### B. SSL/TLS
1. Aller dans **SSL/TLS** → **Overview**
   - Sélectionner: **Full (strict)**

2. **SSL/TLS** → **Origin Server**
   - Cliquer "Create Certificate"
   - Hostnames: `4lb.ca, *.4lb.ca`
   - Validity: 15 ans
   - Copier le certificat et la clé

3. **SSL/TLS** → **Edge Certificates**
   - Always Use HTTPS: ✅ ON
   - Automatic HTTPS Rewrites: ✅ ON
   - Minimum TLS Version: TLS 1.2

#### C. Security
1. **Security** → **Settings**
   - Security Level: Medium
   - Challenge Passage: 30 minutes

2. **Security** → **WAF**
   - Activer les règles recommandées

#### D. Speed
1. **Speed** → **Optimization**
   - Auto Minify: ✅ JavaScript, CSS, HTML
   - Brotli: ✅ ON

### 2. Configuration serveur

#### A. Installer les certificats Cloudflare
```bash
# Créer le répertoire SSL
sudo mkdir -p /etc/nginx/ssl/4lb.ca

# Créer les fichiers de certificat
sudo nano /etc/nginx/ssl/4lb.ca/cert.pem
# [Coller le certificat Origin de Cloudflare]

sudo nano /etc/nginx/ssl/4lb.ca/key.pem
# [Coller la clé privée]

# Sécuriser la clé
sudo chmod 600 /etc/nginx/ssl/4lb.ca/key.pem
sudo chown root:root /etc/nginx/ssl/4lb.ca/*
```

#### B. Déployer la configuration Nginx
```bash
# Copier la configuration
sudo cp nginx/4lb.ca.conf /etc/nginx/sites-available/4lb.ca

# Activer le site
sudo ln -sf /etc/nginx/sites-available/4lb.ca /etc/nginx/sites-enabled/

# Désactiver le site par défaut
sudo rm -f /etc/nginx/sites-enabled/default

# Tester la configuration
sudo nginx -t

# Recharger Nginx
sudo systemctl reload nginx
```

### 3. Configuration Laravel (.env)

```env
APP_NAME="StudiosDB"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://4lb.ca
APP_TIMEZONE=America/Montreal

# Force HTTPS
FORCE_HTTPS=true
ASSET_URL=https://4lb.ca

# Session sécurisée
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# reCAPTCHA v2 Checkbox
RECAPTCHA_ENABLED=true
RECAPTCHA_SITE_KEY=6Le...
RECAPTCHA_SECRET_KEY=6Le...

# Trusted Proxies (Cloudflare)
TRUSTED_PROXIES=*
```

### 4. Configuration reCAPTCHA

#### A. Créer les clés sur Google
1. Aller sur https://www.google.com/recaptcha/admin
2. Créer un nouveau site:
   - Label: `4lb.ca`
   - Type: **reCAPTCHA v2** → **"I'm not a robot" Checkbox**
   - Domains: `4lb.ca`, `www.4lb.ca`
3. Copier les clés dans `.env`

#### B. Ajouter la configuration Laravel
```php
// config/services.php
return [
    // ...
    'recaptcha' => [
        'enabled' => env('RECAPTCHA_ENABLED', true),
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],
];
```

#### C. Middleware HandleInertiaRequests
```php
// app/Http/Middleware/HandleInertiaRequests.php
public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'recaptcha' => [
            'enabled' => config('services.recaptcha.enabled'),
            'site_key' => config('services.recaptcha.site_key'),
        ],
        // ...
    ];
}
```

### 5. Utilisation dans les formulaires

#### A. Exemple LoginRequest
```php
use App\Rules\RecaptchaRule;

public function rules(): array
{
    return [
        'email' => 'required|email',
        'password' => 'required|string',
        'g-recaptcha-response' => ['required', new RecaptchaRule()],
    ];
}
```

#### B. Exemple composant Vue
```vue
<template>
  <form @submit.prevent="submit">
    <!-- Champs du formulaire -->
    
    <!-- reCAPTCHA Checkbox -->
    <RecaptchaCheckbox 
      v-model="form['g-recaptcha-response']"
      :theme="'dark'"
      class="mb-4"
      @verified="onRecaptchaVerified"
      @expired="onRecaptchaExpired"
    />
    
    <button type="submit" :disabled="!form['g-recaptcha-response']">
      Soumettre
    </button>
  </form>
</template>

<script setup>
import RecaptchaCheckbox from '@/Components/RecaptchaCheckbox.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
  'g-recaptcha-response': ''
})

const submit = () => {
  form.post('/login')
}

const onRecaptchaVerified = (token) => {
  console.log('reCAPTCHA vérifié')
}

const onRecaptchaExpired = () => {
  form.errors['g-recaptcha-response'] = 'Veuillez vérifier le captcha à nouveau'
}
</script>
```

## 🚀 Script de déploiement automatique

```bash
# Rendre le script exécutable
chmod +x nginx/deploy.sh

# Lancer le déploiement (nécessite sudo)
sudo bash nginx/deploy.sh
```

## 🔒 Sécurité additionnelle

### Fail2ban pour Laravel
```bash
# /etc/fail2ban/jail.local
[laravel-auth]
enabled = true
port = http,https
filter = laravel-auth
logpath = /home/studiosdb/studiosunisdb/storage/logs/laravel.log
maxretry = 5
bantime = 3600

# /etc/fail2ban/filter.d/laravel-auth.conf
[Definition]
failregex = Failed login for .* from IP: <HOST>
            Too many login attempts .* from IP: <HOST>
ignoreregex =
```

### Headers de sécurité supplémentaires
```nginx
# Dans server block
add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
add_header X-Permitted-Cross-Domain-Policies "none" always;
```

## 📊 Monitoring

### Health check endpoint
```php
// routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
    ]);
});
```

### Commandes de vérification
```bash
# Status Nginx
sudo systemctl status nginx

# Logs Nginx
sudo tail -f /var/log/nginx/4lb.ca.error.log
sudo tail -f /var/log/nginx/4lb.ca.access.log

# Test SSL
curl -I https://4lb.ca
openssl s_client -connect 4lb.ca:443 -tls1_2

# Test depuis Cloudflare
curl -H "CF-Connecting-IP: 1.2.3.4" https://4lb.ca/health
```

## 🔧 Dépannage

### Page blanche
```bash
# Vérifier les permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Régénérer les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Erreur 502 Bad Gateway
```bash
# Vérifier PHP-FPM
sudo systemctl status php8.3-fpm
sudo systemctl restart php8.3-fpm

# Vérifier les sockets
sudo ls -la /var/run/php/
```

### SSL non reconnu
- Vérifier que Cloudflare est en mode **Full (strict)**
- Vérifier que les certificats Origin sont bien installés
- Attendre 5-10 minutes pour la propagation

## 📝 Notes importantes

1. **Ne jamais** désactiver le proxy Cloudflare en production
2. **Toujours** garder APP_DEBUG=false en production
3. **Tester** d'abord sur un sous-domaine (ex: staging.4lb.ca)
4. **Sauvegarder** avant tout changement majeur
5. **Monitorer** les logs après déploiement

## Support

Pour toute question:
- Logs Laravel: `/home/studiosdb/studiosunisdb/storage/logs/`
- Logs Nginx: `/var/log/nginx/`
- Status: `sudo bash nginx/check-nginx.sh`

# 🔐 Guide Cloudflare Turnstile pour 4lb.ca

## ✨ Pourquoi Turnstile?

**Cloudflare Turnstile** est l'alternative GRATUITE et supérieure à Google reCAPTCHA:

| Caractéristique | Turnstile | reCAPTCHA |
|-----------------|-----------|-----------|
| Prix | **GRATUIT** | Gratuit limité |
| Compte requis | Cloudflare (déjà utilisé) | Google |
| Performance | **Plus rapide** | Plus lent |
| Privacy | **GDPR compliant** | Tracking Google |
| UX | **Moins intrusif** | Puzzles ennuyants |
| Intégration | **Native Cloudflare** | Externe |

## 📋 Étapes pour activer Turnstile

### 1️⃣ Accéder à Cloudflare Dashboard

1. Allez sur : **https://dash.cloudflare.com**
2. Connectez-vous avec votre compte
3. Vous verrez votre compte : `Boudreaulouis@gmail.com's Account`

### 2️⃣ Créer un widget Turnstile

1. Dans le menu de gauche, cherchez **"Turnstile"**
   - Si pas visible, cliquez sur "View all products" en bas
   - Ou allez directement : https://dash.cloudflare.com/?to=/:account/turnstile

2. Cliquez sur **"Add site"** (bouton bleu)

3. Remplissez le formulaire :
   ```
   Site name: 4lb.ca Production
   
   Domain(s): 
   - 4lb.ca
   - www.4lb.ca
   - localhost (pour dev)
   - 127.0.0.1 (pour dev)
   
   Widget Mode: Managed (Recommandé)
   ```

4. Cliquez **"Create"**

### 3️⃣ Copier les clés

Après création, vous verrez :

```
Site Key: 0x4AAAAAAAA... (clé publique)
Secret Key: 0x4AAAAAAAB... (clé privée)
```

**IMPORTANT** : Copiez ces clés immédiatement!

### 4️⃣ Configurer dans Laravel

Modifiez votre fichier `.env` :

```env
# Remplacez les clés de test par vos vraies clés
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=0x4AAAAAAAA...  # Votre Site Key
TURNSTILE_SECRET_KEY=0x4AAAAAAAB... # Votre Secret Key
TURNSTILE_MODE=managed
```

### 5️⃣ Appliquer la configuration

```bash
# Nettoyer les caches
php artisan config:cache
php artisan route:cache

# Compiler les assets
npm run build

# Redémarrer le serveur
bash start-server.sh
```

## 🧪 Clés de test (pour développement)

Si vous voulez tester sans créer de widget :

```env
# Toujours valide (pour tests)
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA

# Toujours bloque
TURNSTILE_SITE_KEY=2x00000000000000000000AA
TURNSTILE_SECRET_KEY=2x0000000000000000000000000000000AA

# Force un challenge
TURNSTILE_SITE_KEY=3x00000000000000000000AA
TURNSTILE_SECRET_KEY=3x0000000000000000000000000000000AA
```

## 🎯 Configuration des modes

### Mode "Managed" (Recommandé)
Cloudflare décide automatiquement :
- Utilisateur de confiance = pas de challenge
- Comportement suspect = challenge visuel
- Bot détecté = bloqué

### Mode "Non-Interactive"
Challenge invisible en arrière-plan

### Mode "Invisible"
Aucune interface visible

## 📊 Analytics Turnstile

Dans Cloudflare Dashboard → Turnstile → Analytics :

- **Solves** : Nombre de validations réussies
- **Displays** : Nombre d'affichages du widget
- **Solve rate** : Taux de réussite
- **Interactive solve time** : Temps moyen de résolution

## 🔧 Intégration dans vos formulaires

### Exemple LoginController

```php
use App\Rules\TurnstileRule;

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'cf-turnstile-response' => ['required', new TurnstileRule()],
    ]);
    
    // Logique de connexion...
}
```

### Exemple Vue Component

```vue
<template>
  <form @submit.prevent="submit">
    <!-- Vos champs -->
    
    <TurnstileWidget
      v-model="form['cf-turnstile-response']"
      :theme="'dark'"
      @verified="onVerified"
    />
    
    <button type="submit">Soumettre</button>
  </form>
</template>

<script setup>
import TurnstileWidget from '@/Components/TurnstileWidget.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
  'cf-turnstile-response': ''
})

const onVerified = () => {
  console.log('Turnstile validé!')
}
</script>
```

## 🛡️ Configuration de sécurité avancée

### Dans Cloudflare Dashboard

1. **Security** → **WAF** → **Custom rules**
2. Créer une règle :
   ```
   Expression: (http.request.uri.path contains "/login" or 
                http.request.uri.path contains "/register")
   Action: Challenge (Managed Challenge)
   ```

3. **Security** → **Bots**
   - Activer "Bot Fight Mode"

### Rate Limiting avec Turnstile

```nginx
# Dans nginx/4lb.ca.conf
location ~ ^/(login|register) {
    limit_req zone=auth burst=5 nodelay;
    try_files $uri /index.php?$query_string;
}
```

## ✅ Checklist de vérification

- [ ] Widget Turnstile créé dans Cloudflare
- [ ] Clés copiées dans `.env`
- [ ] `setup-turnstile.sh` exécuté
- [ ] Assets compilés (`npm run build`)
- [ ] Page de test fonctionne (`/test-turnstile.html`)
- [ ] Widget visible sur `/login`
- [ ] Validation côté serveur fonctionne

## 🚨 Dépannage

### Widget n'apparaît pas
```bash
# Vérifier les clés
grep TURNSTILE .env

# Vérifier la configuration
php artisan config:cache

# Vérifier la console du navigateur (F12)
```

### Erreur "Invalid site key"
- Vérifiez que le domaine est bien ajouté dans Cloudflare
- Utilisez les clés de test en développement

### Erreur "Validation failed"
- Vérifiez la SECRET_KEY
- Vérifiez que l'IP du serveur n'est pas bloquée

## 📞 Support

- Documentation : https://developers.cloudflare.com/turnstile
- Dashboard : https://dash.cloudflare.com
- Test en ligne : https://4lb.ca/test-turnstile.html

## 🎉 Félicitations!

Votre site est maintenant protégé par Cloudflare Turnstile, une solution :
- ✅ Gratuite
- ✅ Plus rapide que reCAPTCHA
- ✅ Respectueuse de la vie privée
- ✅ Intégrée à votre infrastructure Cloudflare

Plus besoin de Google reCAPTCHA! 🚀

# Configuration Cloudflare Turnstile pour 4lb.ca

## 🚀 Pourquoi Turnstile au lieu de reCAPTCHA?

- **Gratuit** : Inclus avec Cloudflare
- **Plus rapide** : Validation instantanée 
- **Meilleure UX** : Moins intrusif que reCAPTCHA
- **GDPR compliant** : Pas de tracking Google
- **Invisible ou visible** : Au choix
- **Intégration native** : Fonctionne parfaitement avec Cloudflare

## 📋 Configuration sur Cloudflare Dashboard

### Étape 1 : Créer un widget Turnstile

1. Aller sur : https://dash.cloudflare.com
2. Sélectionner votre compte
3. Dans le menu gauche : **Turnstile**
4. Cliquer **"Add site"**
5. Configuration :
   - **Site name** : 4lb.ca
   - **Domain** : 
     - 4lb.ca
     - www.4lb.ca
     - localhost (pour dev)
   - **Widget Mode** : **Managed** (recommandé)
   - **Widget Type** : **Visible** (checkbox comme reCAPTCHA)
6. Copier les clés :
   - **Site Key** : (publique, pour le frontend)
   - **Secret Key** : (privée, pour le backend)

### Étape 2 : Types de widgets disponibles

1. **Managed (Recommandé)** 
   - Cloudflare décide automatiquement si montrer un challenge
   - Meilleure balance UX/sécurité

2. **Non-interactive**
   - Challenge invisible
   - Validation automatique pour les vrais utilisateurs

3. **Invisible** 
   - Aucune interface visible
   - Protection en arrière-plan

## 🔧 Configuration Laravel

### Variables .env
```env
# Cloudflare Turnstile (remplace reCAPTCHA)
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=0x4AAAAAAxxxxxx
TURNSTILE_SECRET_KEY=0x4AAAAAAyyyyyy
# Mode: visible, invisible, managed
TURNSTILE_MODE=managed
```

### Test Keys (pour développement)
```env
# Always passes
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA

# Always blocks
TURNSTILE_SITE_KEY=2x00000ームdddddddddddddAA
TURNSTILE_SECRET_KEY=2x0000000000000000000000000000000AA

# Forces challenge
TURNSTILE_SITE_KEY=3x00000000000000000000AA
TURNSTILE_SECRET_KEY=3x0000000000000000000000000000000AA
```

## 📝 Notes importantes

- Turnstile fonctionne **sans cookies**
- Compatible avec tous les navigateurs modernes
- Pas de dépendance Google
- Statistiques disponibles dans Cloudflare Dashboard
- Rate limiting automatique inclus

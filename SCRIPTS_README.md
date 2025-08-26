# StudiosDB - Scripts de gestion

## 🚀 Démarrage rapide

```bash
# Solution la plus simple - corrige et lance automatiquement
bash quickstart.sh
```

## 📋 Scripts disponibles

### 1. **quickstart.sh** - Démarrage rapide
- Corrige automatiquement les problèmes courants
- Lance le serveur en mode production ou développement
- Solution recommandée pour démarrer rapidement

### 2. **fix-app.sh** - Correction automatique
- Diagnostique les problèmes courants
- Applique les corrections automatiquement
- Nettoie les caches
- Vérifie les permissions
- Compile les assets si nécessaire

### 3. **start-server.sh** - Mode production
- Compile les assets pour la production
- Lance le serveur Laravel
- Pas de hot reload
- Plus rapide et stable

### 4. **start-dev.sh** - Mode développement
- Lance Vite en mode développement
- Active le hot reload
- Idéal pour le développement actif
- Modifications visibles instantanément

### 5. **test-app.sh** - Tests rapides
- Vérifie la configuration Laravel
- Teste la connexion base de données
- Vérifie les assets compilés
- Teste les endpoints principaux

## 🔧 Résolution des problèmes

### Page blanche
```bash
bash fix-app.sh
bash start-server.sh
```

### Erreur 500
```bash
# Vérifier les logs
tail -50 storage/logs/laravel.log

# Corriger les permissions
chmod -R 775 storage bootstrap/cache

# Régénérer la clé
php artisan key:generate
```

### Assets non chargés
```bash
# Recompiler les assets
npm run build

# Ou en mode dev
npm run dev
```

### Base de données non connectée
```bash
# Vérifier la configuration
cat .env | grep DB_

# Tester la connexion
php artisan tinker
> DB::connection()->getPdo()
```

## 📍 URLs importantes

- **Application**: http://127.0.0.1:8001
- **Dashboard**: http://127.0.0.1:8001/dashboard
- **Login**: http://127.0.0.1:8001/login
- **Vite (dev)**: http://127.0.0.1:5173

## 🛠 Commandes utiles

```bash
# Nettoyer tous les caches
php artisan optimize:clear

# Voir toutes les routes
php artisan route:list

# Compiler les assets
npm run build

# Mode développement
npm run dev

# Créer un superadmin
php artisan make:superadmin

# Voir la configuration
php artisan about
```

## ⚠️ Notes importantes

1. **Ne jamais** avoir le fichier `public/hot` en production
2. Toujours compiler les assets avec `npm run build` avant la mise en production
3. Les permissions sur `storage/` et `bootstrap/cache/` doivent être 775
4. La base de données doit être configurée dans `.env`
5. L'APP_KEY doit être définie dans `.env`

## 🔄 Workflow de développement

1. **Démarrer le développement**:
   ```bash
   bash start-dev.sh
   ```

2. **Faire vos modifications** dans les fichiers Vue/JS/CSS

3. **Tester en production**:
   ```bash
   npm run build
   bash test-app.sh
   ```

4. **Déployer**:
   ```bash
   npm run build
   git add .
   git commit -m "feat: description"
   git push
   ```

---

Pour toute question, consultez d'abord les logs:
```bash
tail -f storage/logs/laravel.log
```

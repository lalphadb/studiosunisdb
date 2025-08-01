# 📚 Référence Technique - StudiosDB v5 Pro

## 🏗️ Architecture Globale
- **Backend:** Laravel 12.x (PHP 8.3+)
- **Frontend:** Vue 3 + TypeScript + Inertia.js
- **Styling:** Tailwind CSS v3
- **Base de Données:** MySQL (utf8mb4)
- **Bundler:** Vite pour assets JS/CSS
- **Permissions:** Spatie Laravel Permission
- **PDF/Exports:** Dompdf + Maatwebsite Excel

## 🔑 Configuration Initiale
- **.env Exemple:**
  APP_NAME="StudiosDB v5 Pro"
  APP_ENV=local
  APP_DEBUG=true
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=studiosdb_central
  DB_USERNAME=studiosdb
  DB_PASSWORD=StudiosDB_2025!

- **Installation Dépendances:**
  composer install
  npm install
  npm run build

## 🗄️ Modèles & Relations
- **User:** Has roles (via Spatie), belongsTo Membre
- **Membre:** BelongsTo User, hasMany Presences, belongsTo Ceinture
- **Ceinture:** HasMany Membres (grades: Blanc, Jaune, Orange, Vert, Bleu, Marron, Noir)

## 🎮 Commandes Utiles
- **Serveur:** php artisan serve --host=0.0.0.0 --port=8000
- **Migrations:** php artisan migrate:fresh --seed
- **Tinker:** php artisan tinker
- **Clear Caches:** php artisan optimize:clear
- **Frontend Build:** npm run build
- **Dev Mode:** npm run dev

## 📊 Dashboard Technique
- Composants: AuthenticatedLayout.vue, Dashboard.vue
- Animations: SVG circulaires pour stats, Keyframes CSS
- Routes: web.php (dashboard, profile, auth)

## 🐛 Troubleshooting
- **Vite Manifest Error:** npm run build
- **TypeError Route:** Importer Ziggy plugin dans app.ts
- **TS Build Error:** Déplacer expressions en computed
- **Bash CD Error:** Vérifier ~/.bashrc STUDIOSDB_PATH

## 🔒 Sécurité
- Rôles: admin, instructeur, membre
- Middleware: auth, verified, role:admin
- Password: Hashé avec Bcrypt

## 📈 Performances
- Cache: Redis ready (config/cache.php)
- Queue: Horizon pour jobs asynchrones

Dernière Mise à Jour: 2025-07-20
Contact: louis@4lb.ca

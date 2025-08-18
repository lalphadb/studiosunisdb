cat > /home/studiosdb/studiosunisdb/MODULE_COURS_DOCUMENTATION.md << 'EOH'
# 📚 MODULE COURS - DOCUMENTATION COMPLÈTE
## StudiosDB v5 Pro - Système de Gestion des Cours

---

## 🎯 VUE D'ENSEMBLE

Le module Cours est une solution complète de gestion des cours pour l'école de karaté StudiosUnis. Il offre une interface moderne et responsive pour gérer les horaires, inscriptions, instructeurs et statistiques.

### Caractéristiques Principales
- ✅ Interface full-width optimisée (100% de l'espace)
- ✅ Design responsive avec Tailwind CSS
- ✅ Cartes statistiques non comprimées
- ✅ Architecture Laravel 12.21 + Vue 3
- ✅ Système de permissions granulaire
- ✅ Multi-tenant ready

---

## 🏗️ ARCHITECTURE TECHNIQUE

### Stack Technologique
```
Backend:  Laravel 12.21
Frontend: Vue 3 + TypeScript + Inertia.js
CSS:      Tailwind CSS (dark theme)
Database: MySQL 8.0
Cache:    Redis
```

### Structure des Fichiers
```
studiosdb_v5_pro/
├── app/
│   ├── Http/Controllers/
│   │   └── CoursController.php      # Contrôleur principal
│   └── Models/
│       └── Cours.php                 # Modèle Eloquent
├── resources/js/Pages/Cours/
│   ├── Index.vue                     # Liste des cours (OPTIMISÉ)
│   ├── Create.vue                    # Création de cours
│   ├── Edit.vue                      # Modification
│   ├── Show.vue                      # Détails d'un cours
│   └── Planning.vue                  # Vue calendrier
├── database/
│   ├── migrations/
│   │   └── 2024_01_04_create_cours_table.php
│   └── seeders/
│       └── CoursSeeder.php           # Données de test
└── routes/
    └── cours.php                     # Routes du module
```

---

## 💾 BASE DE DONNÉES

### Table `cours`
```sql
- id (primary key)
- nom (string)
- description (text)
- instructeur_id (foreign key → users)
- niveau (enum: debutant|intermediaire|avance|competition)
- age_min, age_max (integer)
- places_max (integer)
- jour_semaine (enum)
- heure_debut, heure_fin (time)
- date_debut, date_fin (date)
- tarif_mensuel (decimal)
- salle (string)
- type_cours (string)
- prerequis (string)
- materiel_requis (json)
- couleur (string #RRGGBB)
- actif (boolean)
- timestamps, soft_deletes
```

### Table `cours_membres` (Pivot)
```sql
- cours_id (foreign key)
- membre_id (foreign key)
- date_inscription (date)
- date_fin (date nullable)
- statut (enum: actif|inactif|suspendu|termine)
- tarif_special (decimal nullable)
- notes (text)
```

---

## 🎨 INTERFACE UTILISATEUR

### Problèmes Corrigés ✅
1. **Espace mal utilisé** → Container full-width (`max-w-full`)
2. **Cartes comprimées** → Grid responsive avec padding optimisé
3. **Header trop grand** → Hauteur réduite (`py-6` au lieu de `py-12`)
4. **Marges excessives** → Padding uniforme (`px-4 sm:px-6 lg:px-8`)
5. **Design incohérent** → Thème dark unifié

### Composants Principaux
- **Header Gradient** : Dégradé indigo-purple avec effets blur
- **Cartes Statistiques** : 4 cartes avec icônes et animations hover
- **Table Données** : Table responsive avec actions inline
- **Filtres Avancés** : Saison, niveau, recherche temps réel
- **Pagination** : Navigation intuitive en bas de page

---

## 🔐 PERMISSIONS & RÔLES

### Permissions du Module
```php
// Super Admin
- Accès total toutes écoles
- Configuration système

// Admin (Louis)
- cours.* (toutes permissions)
- Export données
- Statistiques avancées

// Gestionnaire
- cours.view, cours.create, cours.edit
- Inscriptions/désinscriptions
- Rapports

// Instructeur
- cours.view (ses cours)
- Présences marquage
- Liste élèves

// Membre
- cours.view (ses inscriptions)
- Planning personnel
```

---

## 📡 API & ROUTES

### Routes Principales
```php
GET    /cours                 # Liste des cours
GET    /cours/create          # Formulaire création
POST   /cours                 # Enregistrer nouveau
GET    /cours/{id}            # Détails cours
GET    /cours/{id}/edit       # Formulaire édition
PUT    /cours/{id}            # Mise à jour
DELETE /cours/{id}            # Suppression
POST   /cours/{id}/duplicate  # Duplication
GET    /planning              # Vue calendrier
GET    /cours/export          # Export CSV
```

### Endpoints API
```php
GET /cours/api/disponibilites  # Vérifier disponibilités
GET /cours/api/conflits        # Détecter conflits horaires
GET /cours/api/search          # Recherche AJAX
GET /cours/api/calendrier      # Données calendrier JSON
```

---

## 🚀 DÉPLOIEMENT

### Installation Rapide
```bash
# 1. Naviguer vers le projet
cd /home/studiosdb/studiosunisdb

# 2. Exécuter le script de déploiement
chmod +x deploy_cours_module.sh
./deploy_cours_module.sh

# 3. Vérifier l'installation
php artisan tinker
>>> App\Models\Cours::count()
>>> 13  # Nombre de cours créés
```

### Configuration Manuelle
```bash
# Migrations
php artisan migrate

# Seeders
php artisan db:seed --class=CoursSeeder

# Compilation assets
npm run build

# Cache
php artisan optimize
```

---

## 🧪 TESTS

### Tests Unitaires
```php
php artisan test --filter=CoursTest
```

### Tests Fonctionnels
```php
// Vérifier modèle
App\Models\Cours::first()

// Vérifier relations
$cours = App\Models\Cours::with('instructeur')->first()
$cours->instructeur->name

// Vérifier inscriptions
$cours->membres()->count()
```

---

## 📊 UTILISATION

### Créer un Cours
1. Naviguer vers `/cours`
2. Cliquer "Nouveau Cours"
3. Remplir le formulaire
4. Vérifier conflits horaires
5. Enregistrer

### Gérer les Inscriptions
```php
// Via interface
/cours/{id}/membres

// Via code
$cours = Cours::find(1);
$cours->inscrireMembre($membre);
```

### Statistiques
```php
$cours->getStatistiques();
// Retourne: membres_inscrits, taux_remplissage, revenue_mensuel, etc.
```

---

## 🐛 TROUBLESHOOTING

### Problème: Page ne prend pas tout l'espace
**Solution appliquée:**
```vue
<!-- Avant -->
<div class="max-w-7xl mx-auto">

<!-- Après -->
<div class="max-w-full mx-auto">
```

### Problème: Erreur TypeScript
**Solution:**
```typescript
// Supprimer les annotations de type dans les fichiers .vue
// Utiliser composition API sans TypeScript explicite
```

### Problème: Routes non trouvées
**Solution:**
```bash
php artisan route:clear
php artisan route:cache
```

---

## 📝 NOTES DE VERSION

### v1.0.0 (2024-08-16)
- ✅ Module initial créé
- ✅ Interface full-width implémentée
- ✅ Design responsive optimisé
- ✅ 13 cours types ajoutés
- ✅ Système de permissions configuré

### Prochaines Améliorations
- 🔄 Vue calendrier drag & drop
- 🔄 Notifications automatiques
- 🔄 Application mobile
- 🔄 Intégration paiements en ligne

---

## 📞 SUPPORT

**Développeur:** StudiosDB Team  
**Email:** support@studiosdb.local  
**Documentation:** Ce fichier  
**Version:** 1.0.0  
**Licence:** MIT  

---

*Module Cours - StudiosDB v5 Pro - École de Karaté StudiosUnis*
EOH
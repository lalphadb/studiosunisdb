# Rôles Canoniques StudiosDB

## Vue d'ensemble

**StudiosDB** utilise un système de 4 rôles canoniques pour une gestion mono-école simplifiée.

## Rôles et Hiérarchie

### 🔴 superadmin
- **Fonction** : Administrateur système total
- **Portée** : Toutes les écoles (multi-écoles si futur)
- **Permissions** : Accès complet sans restriction
- **Usage** : Développement, maintenance, débogage
- **ecole_id** : `null` (pas lié à une école spécifique)

### 🟠 admin  
- **Fonction** : Administrateur de l'école
- **Portée** : École spécifique (`ecole_id` requis)  
- **Permissions** : 
  - Gestion complète cours
  - Gestion complète membres
  - Gestion utilisateurs de l'école
  - Accès panneau d'administration
- **Usage** : Directeur, gérant d'école

### 🟡 instructeur
- **Fonction** : Enseignant/Coach
- **Portée** : École spécifique (`ecole_id` requis)
- **Permissions** :
  - Gestion cours (création, modification)  
  - Gestion membres (inscriptions, présences)
  - PAS de gestion utilisateurs
- **Usage** : Professeur, entraîneur

### 🟢 membre
- **Fonction** : Étudiant/Adhérent  
- **Portée** : École spécifique (`ecole_id` requis)
- **Permissions** :
  - Consultation de ses propres informations
  - Consultation cours auxquels il est inscrit
- **Usage** : Élève, parent, adhérent

## Différences clés

| Aspect | superadmin | admin | instructeur | membre |
|--------|------------|-------|-------------|--------|
| **Scope** | Multi-écoles | Mono-école | Mono-école | Mono-école |
| **Cours** | Tous | École | École | Siens seulement |
| **Membres** | Tous | École | École | Soi-même |
| **Utilisateurs** | Tous | École | ❌ | ❌ |
| **Admin Panel** | ✅ | ✅ | ❌ | ❌ |

## Migration des anciens rôles

### Supprimés
- ❌ `admin_ecole` → migré vers `admin`
- ❌ `super-admin` → migré vers `superadmin`  
- ❌ `gestionnaire` → migré vers `admin`

### Logique de migration
```php
admin_ecole + gestionnaire → admin (principe de privilèges équivalents)
super-admin → superadmin (normalisation nom)
```

## Utilisation dans le code

### Policies
```php
// Exemple CoursPolicy
private array $superRoles = ['superadmin'];
private array $viewRoles = ['superadmin','admin','instructeur','membre'];
private array $manageRoles = ['superadmin','admin'];
```

### Controllers
```php
// Vérification rôle
if (auth()->user()->hasRole('superadmin')) {
    // Accès total
}

if (auth()->user()->hasAnyRole(['superadmin','admin'])) {
    // Gestion complète
}
```

### Middleware/Gates
```php
// Route protégée admin+
Route::middleware('role:superadmin|admin')->group(...);

// Policy gate
Gate::allows('manage-cours', $cours); // Utilise CoursPolicy
```

## Cas d'usage typiques

### 🏢 École mono-site
- **1 superadmin** : Développeur/IT
- **1-2 admin** : Directeur + assistant
- **3-5 instructeur** : Professeurs
- **50+ membre** : Élèves

### 📱 Interface utilisateur
- **superadmin** : Voit tout, peut tout faire
- **admin** : Dashboard complet école, CRUD utilisateurs
- **instructeur** : Cours + membres, pas d'admin
- **membre** : Profil personnel, cours inscrits

### 🔐 Sécurité
- Scoping automatique par `ecole_id` (sauf superadmin)
- Policies filtrent l'accès par rôle
- GlobalScope empêche fuites données inter-écoles

---

**Avantage de cette structure :**
✅ Simple (4 rôles vs 7 avant)
✅ Clair (hiérarchie logique)  
✅ Évolutif (support multi-écoles futur)
✅ Sécurisé (scoping strict)

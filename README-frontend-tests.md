# Tests Frontend StudiosDB v5 Pro

Ce script Playwright permet de tester automatiquement l'interface utilisateur de StudiosDB v5 Pro.

## Prérequis

1. **Node.js** installé
2. **Playwright** installé : `npm install playwright`
3. **Serveur Laravel** en cours d'exécution sur `http://localhost:8001`
4. **Données de test** créées (utiliser les seeders)

## Installation

```bash
# Installer les dépendances Playwright
npm install playwright

# Installer les navigateurs
npx playwright install
```

## Utilisation

### Test complet

```bash
node complete-frontend-test.js
```

Le script va automatiquement :

- Se connecter avec le compte superadmin
- Tester la navigation entre toutes les pages
- Tester les liens de la sidebar
- Tester les fonctionnalités CRUD (Créer/Supprimer) pour les membres
- Tester les fonctionnalités CRUD pour les cours
- Générer un rapport détaillé

## Fonctionnalités testées

### 🔐 Connexion

- Connexion automatique avec `superadmin@test.com`
- Vérification de la redirection vers le dashboard

### 🧭 Navigation

- Test d'accès à toutes les pages principales :
  - Dashboard
  - Membres
  - Cours
  - Présences
  - Paiements
  - Ceintures
  - Statistiques
  - Administration

### 🔗 Liens de navigation

- Test des liens dans la sidebar
- Vérification des redirections
- Détection des liens cassés

### 👥 CRUD Membres

- Test d'accès à la page de création
- Test du formulaire de création
- Test des boutons de suppression

### 📚 CRUD Cours

- Test d'accès à la page de création
- Test du formulaire de création
- Test des boutons de suppression

## Rapport généré

Le script génère un rapport détaillé avec :

- ✅ **Tests réussis**
- ❌ **Erreurs détectées**
- ⚠️ **Avertissements**
- 📊 **Statistiques de succès**

## Données de test utilisées

Le script utilise les données créées par les seeders :

- **5 membres de test** avec différents niveaux de ceintures
- **6 cours** avec différents horaires
- **60 présences** sur 2 semaines

## Comptes de test

- **Superadmin** : `superadmin@test.com` / `password`
- **Membres** : `alice.dupont@test.com`, `bob.martin@test.com`, etc. / `password`

## Dépannage

### Erreur de connexion

- Vérifier que le serveur Laravel tourne sur le port 8001
- Vérifier les identifiants de connexion

### Pages non accessibles

- Vérifier les routes dans `routes/web.php`
- Vérifier les permissions utilisateur

### Boutons non trouvés

- Vérifier les classes CSS des boutons
- Les sélecteurs peuvent différer selon le thème utilisé

## Personnalisation

Vous pouvez modifier les variables suivantes dans le script :

```javascript
const baseUrl = 'http://localhost:8001'; // URL du serveur
const testUser = {
  email: 'superadmin@test.com',
  password: 'password'
};
```

## Améliorations futures

- [ ] Tests de modification (Update)
- [ ] Tests de validation de formulaires
- [ ] Tests de permissions utilisateur
- [ ] Tests responsives (mobile/tablette)
- [ ] Tests de performance
- [ ] Screenshots automatiques en cas d'erreur

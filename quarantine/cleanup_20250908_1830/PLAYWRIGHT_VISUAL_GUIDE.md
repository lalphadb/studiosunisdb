# 🎭 GUIDE TESTS PLAYWRIGHT VISUELS - STUDIOSDB MODULE MEMBRES

## ✅ CONFIGURATION RÉUSSIE

Le MCP est maintenant **ENTIÈREMENT CONFIGURÉ** pour les tests Playwright en mode visuel temps réel ! 

## 🚀 MODES DISPONIBLES

### 1️⃣ Mode HEADED (Fenêtre Visible)
```bash
npm run test:membres
# ou
npx playwright test membres-visual --headed --project=chromium
```
**Résultat :** Une fenêtre Chrome s'ouvre, vous voyez l'IA cliquer en temps réel !

### 2️⃣ Mode UI (Panneau Interactif) 
```bash
npm run test:membres-ui
# ou
npx playwright test membres-visual --ui
```
**Résultat :** Interface web s'ouvre avec liste des tests + contrôles + vue en temps réel

### 3️⃣ Mode INSPECTOR (Pas à Pas)
```bash
npm run test:membres-debug
# ou
PWDEBUG=1 npx playwright test membres-visual
```
**Résultat :** Contrôles pas-à-pas + inspecteur DOM + vue navigateur

### 4️⃣ Mode SLOW MOTION (Super Lent)
```bash
npx playwright test membres-visual --headed --project=chromium --workers=1 --timeout=0
```
**Résultat :** Actions très lentes (200ms entre chaque clic) pour bien observer

### 5️⃣ Mode MOBILE (Responsive)
```bash
npm run test:mobile
# ou
npx playwright test membres-visual --headed --project="Mobile Chrome"
```
**Résultat :** Simulation mobile, vous voyez l'interface responsive

### 6️⃣ Script Interactif (RECOMMANDÉ)
```bash
./test-playwright-visual.sh
```
**Résultat :** Menu interactif pour choisir le mode désiré

## 🎯 TESTS DISPONIBLES

### Tests Module Membres (9 tests)
- 🏠 Navigation vers module Membres
- 📊 Vérification Stats Cards  
- 🔍 Test filtres de recherche
- 👁️ Test actions hover-only
- 👤 Accès au profil d'un membre
- 🥋 Test système progression ceintures
- 📱 Test responsive mobile
- ➕ Test création nouveau membre
- 🎨 Test thème et design StudiosDB

### Tests Module Cours (12 tests existants)
- Navigation, CRUD, duplication, responsive, etc.

## 📋 CE QUE VOUS VERREZ

### ✅ Mode Headed/UI
1. **Chrome s'ouvre automatiquement**
2. **L'IA navigue sur votre site** (http://localhost:8000)
3. **Vous voyez chaque clic, chaque saisie en temps réel**
4. **Actions automatiques :** login, navigation, filtres, hover, etc.
5. **Pauses programmées** avec `await page.pause()` pour observer

### ✅ Mode Inspector  
1. **Contrôles pas-à-pas** (Play, Pause, Step Over)
2. **Inspecteur DOM en temps réel**
3. **Console de debug**
4. **Génération de sélecteurs**

### ✅ Mode UI
1. **Liste tous les tests** avec statuts
2. **Relance instantanée** d'un test spécifique  
3. **Vue navigateur intégrée**
4. **Filtres et recherche**

## 🔧 CONFIGURATION ACTUELLE

### playwright.config.js
- ✅ **headless: false** (fenêtre visible par défaut)
- ✅ **slowMo: 200** (200ms entre actions)
- ✅ **video: 'on-first-retry'** (enregistrement vidéo)
- ✅ **screenshot: 'only-on-failure'** (captures d'écran)
- ✅ **trace: 'on-first-retry'** (traces pour debug)
- ✅ **baseURL: 'http://localhost:8000'** (StudiosDB)

### Scripts NPM Ajoutés
- ✅ `npm run test:membres` - Tests membres headed
- ✅ `npm run test:membres-ui` - Interface interactive  
- ✅ `npm run test:membres-debug` - Mode inspector
- ✅ `npm run test:mobile` - Tests mobile
- ✅ `npm run test:visual` - Script interactif

## 🎬 EXEMPLE D'UTILISATION

```bash
# 1. Démarrer le serveur Laravel (terminal 1)
php artisan serve

# 2. Lancer les tests visuels (terminal 2) 
npm run test:membres-ui

# 3. Dans l'interface qui s'ouvre :
#    - Sélectionner un test
#    - Cliquer "Run"  
#    - Observer le navigateur bouger !
```

## 🚨 PRÉREQUIS

### ✅ Vérifications Automatiques
- [x] Playwright installé (@playwright/test: ^1.55.0)
- [x] Configuration créée (playwright.config.js)
- [x] Tests créés (membres-visual.spec.js)
- [x] Scripts NPM ajoutés (package.json)
- [x] Script interactif (test-playwright-visual.sh)

### 🔍 Tests de Vérification
```bash
# Lister les tests disponibles
npm run test -- --list

# Vérifier la configuration  
npx playwright --version

# Test rapide (sans headed)
npx playwright test membres-visual --project=chromium --timeout=5000
```

## 🎉 RÉSULTAT FINAL

**Quand vous lancerez les tests, vous verrez :**

1. **Une fenêtre Chrome qui s'ouvre**
2. **L'IA se connecter automatiquement** (`louis@4lb.ca`)
3. **Navigation vers /membres**
4. **Clic sur les filtres, saisie de texte**
5. **Hover sur les lignes pour révéler les actions**
6. **Clic sur "Voir" pour accéder au profil**
7. **Test du système de progression ceintures**
8. **Vérification responsive mobile**
9. **Et plus encore...**

**Le tout en TEMPS RÉEL sous vos yeux ! 👀**

## 🛠️ COMMANDES RAPIDES

```bash
# Mode recommandé (interface graphique)
npm run test:membres-ui

# Mode simple (fenêtre Chrome visible)  
npm run test:membres

# Mode debug (pas à pas)
npm run test:membres-debug

# Script interactif (menu de choix)
./test-playwright-visual.sh
```

**🎯 Le MCP est maintenant 100% configuré pour vous faire voir l'IA tester votre interface en temps réel !**

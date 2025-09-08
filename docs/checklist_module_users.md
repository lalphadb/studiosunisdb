# 📋 Checklist Module Users/Membres (J4) - État actuel

## ✅ Complété
- [x] Routes CRUD complètes (index, create, store, show, edit, update, destroy)
- [x] Routes actions spéciales (toggle-status, reset-password, manage-roles, progresser-ceinture)
- [x] Route export (PDF/Excel)
- [x] Route bulk operations
- [x] Vues Inertia (Index, Create, Edit, Show, Form)
- [x] Architecture fusion users+membres
- [x] Migration cours_users (remplace cours_membres)

## 🔧 À vérifier/finaliser

### 1. Controller UserController
- [ ] Vérifier scoping ecole_id dans toutes les méthodes
- [ ] Validation auto-préservation (ne pas supprimer son propre compte)
- [ ] Gestion des rôles avec restrictions (admin_ecole ne peut pas créer superadmin)
- [ ] Export PDF/Excel fonctionnel
- [ ] Bulk operations (assignation masse, promotions groupées)

### 2. Model User
- [ ] Relations complètes (cours, presences, paiements, progression_ceintures)
- [ ] Scopes pour filtrage (actifs, par rôle, par école)
- [ ] Accesseurs/Mutateurs pour champs sensibles
- [ ] Trait BelongsToEcole appliqué

### 3. Policies UserPolicy
- [ ] Vérification permissions par rôle
- [ ] Scoping ecole_id strict
- [ ] Auto-préservation dans delete/update
- [ ] Gestion hiérarchique des rôles

### 4. UI/UX Conformité
- [ ] Actions hover-only (opacity-0 group-hover:opacity-100)
- [ ] Responsive text (text-xl sm:text-2xl xl:text-3xl)
- [ ] Tables responsive (overflow-x-auto)
- [ ] Composants uniformes (UiButton, UiCard, StatsCard)
- [ ] Dark mode support

### 5. Validation & Sécurité
- [ ] FormRequest avec règles complètes
- [ ] Email unique par école
- [ ] Mot de passe ≥ 8 caractères
- [ ] Validation dates (naissance, inscription)
- [ ] Rate limiting sur actions sensibles

### 6. Tests
- [ ] Tests Pest CRUD complet
- [ ] Tests fusion users+membres
- [ ] Tests permissions par rôle
- [ ] Tests scoping ecole_id
- [ ] Tests auto-préservation

### 7. Logs & Audit
- [ ] Activity log sur toutes actions
- [ ] Audit trail modifications sensibles
- [ ] Export logs pour Loi 25

### 8. Fonctionnalités avancées
- [ ] Filtres avancés (statut, rôle, dernière connexion)
- [ ] Recherche full-text
- [ ] Pagination avec préservation état
- [ ] Import CSV/Excel membres
- [ ] Photo profil avec crop

## 📊 Progression estimée: 70%

## 🎯 Prochaines actions prioritaires
1. Vérifier UserController pour scoping ecole_id
2. Créer/vérifier UserPolicy
3. Tester export PDF/Excel
4. Valider UI hover-only sur actions
5. Écrire tests Pest de base

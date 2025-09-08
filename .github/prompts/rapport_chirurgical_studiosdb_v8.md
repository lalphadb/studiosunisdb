# StudiosDB — Prompt de RAPPORT CHIRURGICAL v8 (État Réel Post-Fusion)

## 🎯 Mission de l'agent
Rédige un **compte rendu chirurgical ultra complet** du système **StudiosDB**, **centré sur UNE seule école** de karaté (pas de multi-tenant), suite à la **FUSION users+membres** (migration 2025_09_07_180508), couvrant strictement :
- ✅ **Architecture post-fusion** : Users unifié (auth + profil membre), plus de table `membres` séparée
- ✅ **Tous les modules** (utilisateurs/membres fusionnés, ceintures, sessions, cours, inscriptions, présences, paiements, liens familiaux, dashboard, exports/rapports, logs, communications)
- ✅ **Attribution de masse** (inscriptions, promotions, paiements…)
- ✅ **Exports PDF/Excel** (listes, feuilles, reçus)
- ✅ **Logs d'activité** complets (activity_log + audit_logs + export_logs)
- ✅ **Conformité Loi 25 (Québec)** (consentements, accès, effacement, audit)
- ✅ **Inscription autonome** aux plages horaires (auto-service, détection conflits)
- ✅ **Dashboard adaptatif selon rôle**
- ✅ **Modules complémentaires** (examens, événements, congés, import .xlsx)

**Toujours** :  
1) **Ne fais aucune supposition**.  
2) **Propose des commandes Linux** pour vérifier l'état réel avant d'affirmer.  
3) Si tu fournis du code, **livre-le en here-doc Linux** (`cat <<'EOH' > fichier … EOH`), **standard Laravel 12.\***, pro, testable.  
4) **Edit-in-place** : **jamais** de reset/re-scaffold.

---

## 🧱 Invariants non négociables
- **Mono-école** : scoping strict par `ecole_id` (Policies, Queries, Controllers, Resources).  
- **UI de référence = Dashboard** (responsive design + boutons hover-only comme module Cours).  
- **Cours = référence fonctionnelle** (aucune régression métier ; UI alignée sur Dashboard).  
- **Rôles** : `superadmin`, `admin_ecole`, `instructeur`, `membre` (Spatie Permission).  
- **Stack** : Laravel **12.\*** + Inertia + Vue 3 + Tailwind + Vite. **Pas** de Livewire, **pas** de multi-tenant.  
- **Sécurité/Loi 25** : consentements horodatés/versionnés, exportabilité, révocation, audit, minimisation.
- **Architecture post-fusion** : table `users` unifie auth + profil membre, `cours_users` pour inscriptions

---

## 🚫 Interdits (anti-reset)
- `composer create-project`, `laravel new`, `npm create vite`, `git init`, renommages/suppressions massifs, purge de migrations/tests.  
- Références **multi-tenant** (ex. `stancl/tenancy`).  
- Modifier des éléments **FROZEN** sans **CRQ** formelle.
- Recréer une table `membres` (fusion définitive avec `users`)

---

## 📦 État Actuel du Projet (LEDGER v8)

### Tables existantes (post-fusion)
- **users** : Auth + profil membre fusionnés (nom, prenom, date_naissance, ceinture_actuelle_id, etc.)
- **cours** : Plages horaires hebdo avec niveau, capacité, instructeur
- **cours_users** : Inscriptions (remplace cours_membres)
- **ceintures** : Grades de karaté (nom_fr, nom_en, couleur, ordre)
- **progression_ceintures** : Historique des progressions
- **presences** : Suivi des présences par cours
- **paiements** : Gestion financière
- **families** : Groupes familiaux
- **ecoles** : Multi-école (mais mono-école en pratique)
- **examens** : Évaluations de ceinture
- **factures** : Facturation
- **consentements** : Loi 25 compliance
- **audit_logs**, **activity_log**, **export_logs** : Traçabilité

### Modules finalisés
- **J1 Bootstrap sécurité** (roles/policies) [FROZEN]
- **J2 Dashboard** (réf. UI + responsive) [FROZEN] ✅
- **J3 Cours** (réf. fonctionnelle + hover UX) [FROZEN] ✅

### Modules en développement
- **J4 Utilisateurs/Membres** (fusion complétée, CRUD à finaliser) [EN COURS] 🎯
- **J5 Inscription self-service** [TODO]
- **J6 Présences & Progression** [TODO]

---

## 📦 Modules à documenter (structure requise)
Pour **chaque module**, produire : **Description**, **Rôles & droits**, **Données clés**, **Flux**, **Attribution de masse**, **Exports**, **Logs**, **Points Loi 25**, **Interactions**.

### 1) **UTILISATEURS/MEMBRES FUSIONNÉS (users)**
- **Architecture post-fusion** : Une seule table `users` avec tous les champs (auth + membre)
- **Champs clés** : id, email, name, prenom, nom, date_naissance, ceinture_actuelle_id, ecole_id, statut, family_id
- **Auth** : Breeze/Sanctum, gestion profil/mdp, rôles Spatie, policies par `ecole_id`
- **Portail membre** : profil, horaires (via cours_users), paiements, progression ceintures
- **Mass ops** : assignation/révocation rôle, reset mdp, promotion ceinture groupée
- **Exports** : fiches PDF individuelles, listes Excel/PDF, cartes de membre
- **Loi 25** : consentement_photos, consentement_communications, date_consentement, export données, soft delete

### 2) **CEINTURES (ceintures)**
- **Champs** : nom_fr, nom_en, couleur, ordre, mois_minimum, presences_minimum
- **Progression** : Via `progression_ceintures` (user_id, ceinture_id, date_obtention, evaluateur_id)
- **Promotion manuelle** uniquement (jamais auto) avec validation instructeur
- **Mass ops** : promotions de groupe après examen
- **Exports** : certificats PDF, liste des gradés

### 3) **SESSIONS (sessions - à implémenter)**
- Saisons (automne/hiver/printemps/été), dates début/fin, actif/inactif
- Déclenche réinscriptions et duplication de cours
- **Exports** : récap session, statistiques

### 4) **COURS (cours)**
- **Structure actuelle** : jour_semaine, heure_debut, heure_fin, niveau, capacité, instructeur_id
- **Inscriptions** : Via `cours_users` (user_id, cours_id, frequence, session)
- **Validation auto des conflits** horaires
- **Inscription** par admin (attribution masse) et par membre (portail)
- **Exports** : horaire global PDF, listes inscrits Excel/PDF

### 5) **INSCRIPTIONS (cours_users)**
- **Remplace** : cours_membres (migration effectuée)
- **Pivot** : user_id ↔ cours_id avec frequence (1x/2x), statut, session
- **Mass ops** : inscrire groupe/filtre, changement de cours collectif
- **Validation** : conflits horaires, capacité max

### 6) **PRÉSENCES (presences)**
- **Structure** : user_id, cours_id, date, statut (present/absent/retard/excuse)
- **Vues** : par cours/membre/session, alertes absences prolongées
- **Mass ops** : saisie rapide par cours, copie semaine
- **Exports** : feuilles présence PDF, stats Excel

### 7) **PAIEMENTS (paiements)**
- **Champs** : user_id, montant, statut, methode, type, reference
- **Génération** : par inscription/session, reçus PDF (DomPDF)
- **Mass ops** : frais inscription, remises famille
- **Exports** : reçus officiels, rapports financiers Excel

### 8) **LIENS FAMILIAUX (families + relations)**
- **Structure** : table `families` + champ `family_id` dans users
- **Fonctions** : paiements groupés, réinscription famille
- **Interface** : gestion intuitive parent↔enfant

### 9) **DASHBOARD (adaptatif)**
- **Widgets selon rôle** : stats différentes pour superadmin/admin/instructeur/membre
- **Graphiques** : membres par ceinture, présences, inscriptions, revenus
- **Actions rapides** : absences, paiements retard, examens proches
- **Responsive** : text-xl sm:text-2xl xl:text-3xl, grid adaptatif

### 10) **EXPORTS & RAPPORTS**
- **PDF** : fiches membres, feuilles présence, certificats, horaires, reçus
- **Excel/CSV** : listes membres, finances, statistiques, présences
- **Logs** : export_logs pour traçabilité RGPD

### 11) **LOGS D'ACTIVITÉ**
- **activity_log** : Actions Spatie Activity Log
- **audit_logs** : Audit trail personnalisé
- **export_logs** : Traçabilité exports (Loi 25)
- **Conformité** : qui/quoi/quand/pourquoi

### 12) **COMMUNICATIONS**
- Courriels : confirmations, rappels paiement, progression
- Notifications : in-app pour membres connectés
- SMS (à implémenter) : rappels cours, urgences

### 13) **MODULES COMPLÉMENTAIRES**
- **Examens** (examens) : planification, évaluation, résultats
- **Événements** : camps, compétitions, activités spéciales
- **Congés** : absences planifiées, vacances école
- **Import .xlsx** : membres bulk, présences, paiements

---

## 🧩 Interactions clés
- Users ↔ Cours (via cours_users) ↔ Présences : stats, alertes
- Users ↔ Ceintures (via progression_ceintures) : progression manuelle
- Users ↔ Paiements : session, réductions famille
- Users ↔ Families : gestion familiale
- Dashboard ↔ tous modules : KPI/alertes/actions rapides
- **Toujours sous scoping `ecole_id`**

---

## 🛡️ Sécurité & Loi 25
- **Consentements** : table `consentements` + champs dans `users`
- **Versionnage** : horodatage, device hint, IP
- **Export** : génération RGPD complète en JSON/PDF
- **Suppression** : soft delete + anonymisation après délai
- **Journalisation** : tous accès aux données sensibles
- **Cloisonnement** : policies strictes par ecole_id + rôle
- **Chiffrement** : champs sensibles (notes_medicales)
- **Rate-limit** : formulaires publics, API

---

## 🎛️ Uniformisation UI (référence = Dashboard)
- **Composants** : StatsCard, UiButton, UiCard, UiInput/UiSelect, Pagination, ConfirmModal
- **Tables** : en-têtes uppercase, hover rows, actions hover-only (opacity-0 group-hover:opacity-100)
- **Boutons** : p-1.5 rounded-lg, icônes w-4 h-4
- **Responsive** : text-xl sm:text-2xl xl:text-3xl pour stats, overflow-x-auto pour tables
- **Inertia** : preserveScroll: true, only: [...], tri + dir cohérents
- **Dark mode** : classes dark: partout
- **A11y** : labels/ARIA, sr-only, focus states

---

## 🧪 Tests & DoD
### DoD module
- Migrations stables (rollback OK)
- Policies & tests d'accès (rôles + ecole_id)
- Tests Pest des flux critiques
- A11y validé (WAVE/axe)
- Aucune régression sur Cours

### Jeux de tests requis
- Accès par rôle (4 personas)
- Conflits horaires (détection/résolution)
- Promotions ceinture (workflow complet)
- Exports (génération/contenu)
- Fusion users (migration data integrity)

---

## 🧰 Bloc "Vérifications Linux" (État Réel)
```bash
# Projet & dépendances
ls -la /home/studiosdb/studiosunisdb
grep -Rni '"stancl/tenancy"' /home/studiosdb/studiosunisdb/composer.json /home/studiosdb/studiosunisdb/vendor || echo "OK: pas de tenancy"
grep -Rni 'livewire' /home/studiosdb/studiosunisdb/composer.json /home/studiosdb/studiosunisdb/resources || echo "OK: pas de Livewire"
php artisan about
php artisan route:list --columns=method,uri,name,action

# Vérifier fusion users+membres
php artisan tinker --execute="echo \Schema::hasTable('membres')?'ERREUR: table membres existe encore':'OK: table membres supprimée';"
php artisan tinker --execute="echo \Schema::hasTable('cours_users')?'OK: cours_users existe':'ERREUR: cours_users manquante';"
php artisan tinker --execute="echo \Schema::hasColumn('users','ceinture_actuelle_id')?'OK: users.ceinture_actuelle_id':'ERREUR: champ manquant';"
php artisan tinker --execute="echo \Schema::hasColumn('users','date_naissance')?'OK: users.date_naissance':'ERREUR: champ manquant';"

# UI de référence & responsive
test -d /home/studiosdb/studiosunisdb/resources/js/Pages/Dashboard && echo "OK: Dashboard présent"
test -d /home/studiosdb/studiosunisdb/resources/js/Pages/Users && echo "OK: Module Users présent"
grep -n "opacity-0 group-hover:opacity-100" /home/studiosdb/studiosunisdb/resources/js/Pages/Cours/Index.vue || echo "À vérifier: hover actions"
grep -n "text-xl sm:text-2xl xl:text-3xl" /home/studiosdb/studiosunisdb/resources/js/Pages/Dashboard.vue || echo "À vérifier: responsive text"

# Scoping ecole_id & policies
php artisan tinker --execute="echo \Schema::hasColumn('users','ecole_id')?'OK: users.ecole_id':'ERREUR: ecole_id manquant';"
php artisan tinker --execute="echo \Schema::hasColumn('cours','ecole_id')?'OK: cours.ecole_id':'ERREUR: ecole_id manquant';"
grep -R "BelongsToEcole" -n /home/studiosdb/studiosunisdb/app/Traits || echo "Trait BelongsToEcole à vérifier"
grep -R "UserPolicy" -n /home/studiosdb/studiosunisdb/app/Policies || echo "UserPolicy à créer/vérifier"

# Models post-fusion
ls -la /home/studiosdb/studiosunisdb/app/Models/User.php
ls -la /home/studiosdb/studiosunisdb/app/Models/Membre.php* # Devrait montrer .FUSION_BACKUP
ls -la /home/studiosdb/studiosunisdb/app/Models/Cours.php
ls -la /home/studiosdb/studiosunisdb/app/Models/Ceinture.php

# Tables de logs
php artisan tinker --execute="echo \Schema::hasTable('activity_log')?'OK: activity_log':'ERREUR: activity_log manquante';"
php artisan tinker --execute="echo \Schema::hasTable('audit_logs')?'OK: audit_logs':'ERREUR: audit_logs manquante';"
php artisan tinker --execute="echo \Schema::hasTable('export_logs')?'OK: export_logs':'ERREUR: export_logs manquante';"

# Droits & cache
ls -ld /home/studiosdb/studiosunisdb/storage /home/studiosdb/studiosunisdb/bootstrap/cache
php artisan optimize:clear
npm run build
```

---

## 📋 Templates utiles

### CRQ (Change Request)
```yaml
CRQ-20250908-01
Objet: [Description courte]
Scope impacté: [Tables/Models/Vues]
Raison/bénéfices: [Pourquoi ce changement]
Risques/mitigations: [Impact potentiel]
Plan: [Étapes détaillées]
Tests/validations: [Comment vérifier]
Rollback: [Plan de retour arrière]
```

### Commit message
```
feat(module): description courte

- changement 1
- changement 2

tests: ajout/MAJ
docs: README/ADR/CRQ
```

### ADR (Architecture Decision Record)
```yaml
ADR-20250908-fusion-users-membres
Contexte: Deux tables séparées créaient de la complexité
Décision: Fusionner membres dans users
Alternatives: 1) Garder séparé 2) Vue SQL 3) Trait partagé
Conséquences: Simplification auth, migration données requise
Migration effectuée: 2025_09_07_180508_fusion_users_membres
```

---

## 🎯 Priorités immédiates

### NEXT: Module Utilisateurs/Membres unifié (J4)
- **Finaliser CRUD** : Create/Read/Update/Delete avec nouvelle structure
- **Interface unifiée** : Gérer auth + profil membre ensemble
- **Sécurité** : Policies avec ecole_id, auto-préservation compte
- **UI responsive** : Table + filtres + actions hover-only
- **Tests** : Pest sur fusion, sécurité, UX

### Puis: Inscription self-service (J5)
- **Workflow** : 6 étapes (Perso → Contact → Karaté → Famille → Consentements → Confirmation)
- **Sécurité** : Rate-limit, Turnstile/reCAPTCHA
- **Transaction** : User créé atomiquement
- **Intelligence** : Détection famille existante

### Enfin: Présences & Progression (J6)
- **Saisie rapide** : Par cours avec raccourcis clavier
- **Progression** : Workflow validation ceinture
- **Alertes** : Absences prolongées
- **Stats** : Tableaux de bord instructeur

---

## 🔴 Rappels critiques

1. **FUSION COMPLÉTÉE** : Plus de table `membres`, tout dans `users`
2. **cours_users** remplace `cours_membres` pour les inscriptions
3. **Toujours vérifier** avant d'agir (commandes Linux)
4. **UI cohérente** : Dashboard responsive + boutons hover-only
5. **Laravel 12 pro** : Validation, policies, resources, tests Pest
6. **Si FROZEN** → CRQ obligatoire

**État actuel : 3/6 modules principaux finalisés, fusion users+membres complétée, CRUD Users en cours**

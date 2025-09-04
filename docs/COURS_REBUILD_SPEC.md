# Rebuild Module Cours – Spécification

Date: 2025-08-29
Status: Draft (Phase Design) – Aucune migration exécutée

## Objectifs

1. Simplifier le domaine (cours + inscriptions) en retirant la dette technique (accès/duplication complexes, champs redondants).
2. Uniformiser le modèle de tarification (un seul couple type_tarif + montant + devise).
3. Préparer l’extensibilité (sessions, occurrences calendrier, statistiques) via `options` JSON et services dédiés.
4. Réduire la responsabilité du modèle Eloquent (déplacer logique métier dans services).
5. Fournir une API interne claire (contrat contrôleur → service → modèle) testable.
6. UI alignée sur `Welcome.vue` (palette, spacing, composants réutilisables).

## Périmètre Fonctionnel (Phase 1 Rebuild)

| Feature | Inclus | Détails |
|---------|--------|---------|
| CRUD Cours | Oui | Nom, planning, capacité, tarification, état actif |
| Inscriptions membres | Oui (attach/detach) | Statuts: actif, suspendu, termine |
| Duplication simple | Oui | Dupliquer un cours (copie) ou sur un autre jour |
| Planning basique | Oui | Export JSON pour calendrier (pas d’occurrences générées encore) |
| Statistiques légères | Oui | Taux remplissage, places restantes |
| Session saisonnière | Optionnel (enum) | Stockée dans colonne `session` déjà existante sur legacy, consolidée |
| Export CSV | Non (phase 2) | Sera ré-implémenté après stabilisation |
| Revenus estimés | Non (phase 2) | Via service financier plus tard |
| Présences | Hors scope | Module séparé interagira plus tard |

## Modèle de Données Cible

### Table `cours` (nouvelle)

| Champ | Type | Notes |
|-------|------|-------|
| id | BIGINT | PK |
| ecole_id | BIGINT nullable | Scope multi-école (NULL pour superadmin import) |
| instructeur_id | BIGINT nullable | FK users.id (nullOnDelete) |
| nom | VARCHAR(255) | |
| slug | VARCHAR(255) unique | Généré lors de la création |
| niveau | VARCHAR(32) | Enum logique (tous, debutant, intermediaire, avance, competition) |
| age_min | TINYINT unsigned | défaut 3 |
| age_max | TINYINT unsigned nullable | |
| places_max | SMALLINT unsigned | capacité |
| places_reservees | SMALLINT unsigned | compteur dérivé (optimisation) |
| jour_semaine | VARCHAR(12) | (lundi..dimanche) |
| heure_debut | TIME | |
| heure_fin | TIME | |
| date_debut | DATE | période (facultative) |
| date_fin | DATE nullable | |
| session | ENUM(hiver,printemps,ete,automne) default automne | Alignement futur duplication saison |
| type_tarif | ENUM(mensuel,trimestriel,horaire,a_la_carte,autre) | |
| montant | DECIMAL(8,2) | Montant de référence |
| devise | VARCHAR(8) default CAD | |
| couleur | VARCHAR(10) nullable | Code pour calendrier |
| actif | BOOLEAN default true | Visible / masqué |
| inscriptions_ouvertes | BOOLEAN default true | Contrôle UX |
| options | JSON nullable | Extensibilité (prerequis, materiel, notes) |
| description | TEXT nullable | |
| deleted_at | TIMESTAMP nullable | Soft delete |
| created_at / updated_at | TIMESTAMP | |

### Table `cours_membres`

| Champ | Type | Notes |
|-------|------|-------|
| id | BIGINT | PK |
| cours_id | BIGINT | FK cours.id cascade |
| membre_id | BIGINT | FK membres.id cascade |
| date_inscription | DATE | default now |
| statut | ENUM(actif,suspendu,termine) | |
| meta | JSON nullable | Infos supplémentaires (source, notes) |
| created_at / updated_at | TIMESTAMP | |
| UNIQUE (cours_id, membre_id) | | |

## Mapping Legacy → Nouveau

| Legacy | Nouveau | Transformation |
|--------|---------|----------------|
| tarif_mensuel | (supprimé) | Remplacé par montant unique |
| type_tarif + montant | type_tarif + montant | Conservé |
| parent_cours_id, group_uid, session (extended) | session | Parent/group inutiles phase 1 (duplications restent simples) |
| prerequis, materiel_requis | options JSON | options.prerequis / options.materiel |
| places_max / calcul membres | places_max + places_reservees | Recalcul initial après migration |

## Migration Stratégie

1. Renommer tables existantes → `cours_legacy` & `cours_membres_legacy` (préservation brute).
2. Créer nouvelles tables propres.
3. Migrer les lignes: insertion SELECT en mappant colonnes + JSON build.
4. Recalcul `places_reservees` via COUNT sur inscriptions migrées.
5. Vérification intégrité (counts comparés, hash simple).
6. Basculer code sur nouveaux modèles (le modèle actuel déjà modifié est compatible avec le schéma cible; vérifier `session`).
7. (Optionnel) Indexation additionnelle selon besoin (ex: composite jour_semaine+heure_debut).

## Services (Phase 1)

| Service | Responsabilités |
|---------|-----------------|
| `CourseService` | CRUD bas niveau + recalcul places + duplication simple |
| `EnrollmentService` | Inscrire / désinscrire membres (MAJ compteur) |
| `CourseDuplicationService` (future) | Stratégies avancées (jour/semaine/saison) |

## Contrat Contrôleur (esquisse)

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| /cours | GET | Liste paginée + filtres (niveau, instructeur, actif) |
| /cours | POST | Créer cours (FormRequest) |
| /cours/{id} | GET | Détails (cours + inscriptions count) |
| /cours/{id} | PUT | Mise à jour |
| /cours/{id} | DELETE | Soft delete |
| /cours/{id}/duplicate | POST | Duplique le cours (inactif) |
| /cours/{id}/inscriptions | POST | Inscrire un membre (membre_id) |
| /cours/{id}/inscriptions/{membre_id} | DELETE | Désinscrire |

## Tests Cibles

| Test | Type |
|------|------|
| Création cours minimal | Feature |
| Duplication cours | Feature |
| Inscription > capacité refuse | Feature |
| Migration mapping (script verifie counts) | Artisan test / integration |
| Service recalcul places | Unit |

## Sécurité & Autorisations

| Action | Rôles | Politique |
|--------|-------|-----------|
| viewAny/view | superadmin, admin, instructeur, membre (lecture filtrée) | Policy Cours |
| create/update/delete | superadmin, admin | Policy Cours |
| inscrire/désinscrire | superadmin, admin, instructeur (limité) | Nouvelle méthode policy |

## UX (Phase 1 UI)

Composants réutilisables (Button, Card, Badge, Table) + Style Welcome.
Index: tableau condensé, actions sur hover, badges (Actif / Fermé / Complet).
Form Create/Edit: 2 colonnes (Infos / Capacité & Tarification) + section Options (prérequis, matériel).

## Étapes Prochaines

1. (PRÉCIS) Créer migration rename + new schema (safe).  
2. Ajouter script artisan de migration de données.  
3. Implémenter services + adapter contrôleur.  
4. Générer tests.  
5. Refonte UI.  

---
Fin de la spécification Phase 1.

# Rapport d'analyse de la base de données studiosdb
Date: Mon Sep  1 12:09:38 PM EDT 2025

## 1. Schéma Global

### Bases de données disponibles:
```sql
Database
information_schema
mysql
performance_schema
studiosdb
sys
```

### Tables de la base studiosdb:
```sql
Tables_in_studiosdb
activity_log
cache
cache_locks
ceintures
cours
cours_legacy
cours_membres
cours_membres_legacy
ecoles
examens
factures
failed_jobs
families
job_batches
jobs
liens_familiaux
membres
migrations
model_has_permissions
model_has_roles
paiements
password_reset_tokens
permissions
presences
progression_ceintures
role_has_permissions
roles
sessions
telescope_entries
users
```

## 2. Structure des Tables

### Table: activity_log

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
log_name	varchar(255)	YES	MUL	NULL	
description	text	NO		NULL	
subject_type	varchar(255)	YES	MUL	NULL	
event	varchar(255)	YES		NULL	
subject_id	bigint unsigned	YES		NULL	
causer_type	varchar(255)	YES	MUL	NULL	
causer_id	bigint unsigned	YES		NULL	
properties	json	YES		NULL	
batch_uuid	char(36)	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: activity_log
Create Table: CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: cache

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
key	varchar(255)	NO	PRI	NULL	
value	mediumtext	NO		NULL	
expiration	int	NO		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: cache
Create Table: CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: cache_locks

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
key	varchar(255)	NO	PRI	NULL	
owner	varchar(255)	NO		NULL	
expiration	int	NO		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: cache_locks
Create Table: CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: ceintures

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
name	varchar(255)	NO		NULL	
name_en	varchar(255)	YES		NULL	
color_hex	varchar(255)	YES		NULL	
order	int	NO	UNI	NULL	
description	text	YES		NULL	
technical_requirements	json	YES		NULL	
minimum_duration_months	int	NO		3	
minimum_attendances	int	NO		24	
active	tinyint(1)	NO	MUL	1	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: ceintures
Create Table: CREATE TABLE `ceintures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_hex` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `technical_requirements` json DEFAULT NULL,
  `minimum_duration_months` int NOT NULL DEFAULT '3',
  `minimum_attendances` int NOT NULL DEFAULT '24',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `belts_order_unique` (`order`),
  KEY `belts_active_index` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: cours

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
ecole_id	bigint unsigned	YES		NULL	
instructeur_id	bigint unsigned	YES		NULL	
nom	varchar(255)	NO		NULL	
slug	varchar(255)	YES		NULL	
niveau	varchar(32)	NO		tous	
age_min	tinyint unsigned	NO		3	
age_max	tinyint unsigned	YES		NULL	
places_max	smallint unsigned	NO		20	
places_reservees	smallint unsigned	NO		0	
jour_semaine	varchar(12)	NO		NULL	
heure_debut	time	NO		NULL	
heure_fin	time	NO		NULL	
date_debut	date	NO		NULL	
date_fin	date	YES		NULL	
session	enum('automne','hiver','printemps','ete')	NO		automne	
type_tarif	enum('mensuel','trimestriel','horaire','a_la_carte','autre')	NO		mensuel	
montant	decimal(8,2)	NO		0.00	
devise	varchar(8)	NO		CAD	
couleur	varchar(10)	YES		NULL	
actif	tinyint(1)	NO		1	
inscriptions_ouvertes	tinyint(1)	NO		1	
options	json	YES		NULL	
description	text	YES		NULL	
deleted_at	timestamp	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: cours
Create Table: CREATE TABLE `cours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ecole_id` bigint unsigned DEFAULT NULL,
  `instructeur_id` bigint unsigned DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tous',
  `age_min` tinyint unsigned NOT NULL DEFAULT '3',
  `age_max` tinyint unsigned DEFAULT NULL,
  `places_max` smallint unsigned NOT NULL DEFAULT '20',
  `places_reservees` smallint unsigned NOT NULL DEFAULT '0',
  `jour_semaine` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `session` enum('automne','hiver','printemps','ete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'automne',
  `type_tarif` enum('mensuel','trimestriel','horaire','a_la_carte','autre') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mensuel',
  `montant` decimal(8,2) NOT NULL DEFAULT '0.00',
  `devise` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CAD',
  `couleur` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `inscriptions_ouvertes` tinyint(1) NOT NULL DEFAULT '1',
  `options` json DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: cours_legacy

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
parent_cours_id	bigint unsigned	YES	MUL	NULL	
group_uid	char(36)	YES	MUL	NULL	
nom	varchar(255)	NO		NULL	
description	text	YES		NULL	
instructeur_id	bigint unsigned	YES	MUL	NULL	
niveau	varchar(40)	YES		NULL	
age_min	int	NO		5	
age_max	int	YES		NULL	
places_max	int	NO		20	
jour_semaine	enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche')	NO	MUL	NULL	
session	enum('automne','hiver','printemps','ete')	NO	MUL	automne	
heure_debut	time	NO		NULL	
heure_fin	time	NO		NULL	
date_debut	date	NO		NULL	
date_fin	date	YES		NULL	
tarif_mensuel	decimal(8,2)	YES		NULL	
type_tarif	varchar(255)	NO		mensuel	
montant	decimal(8,2)	NO		0.00	
details_tarif	text	YES		NULL	
salle	varchar(255)	YES		NULL	
couleur_calendrier	varchar(255)	NO		#3B82F6	
actif	tinyint(1)	NO	MUL	1	
inscription_ouverte	tinyint(1)	NO		1	
prerequis	json	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
deleted_at	timestamp	YES		NULL	
ecole_id	bigint unsigned	NO	MUL	1	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: cours_legacy
Create Table: CREATE TABLE `cours_legacy` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_cours_id` bigint unsigned DEFAULT NULL,
  `group_uid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `instructeur_id` bigint unsigned DEFAULT NULL,
  `niveau` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age_min` int NOT NULL DEFAULT '5',
  `age_max` int DEFAULT NULL,
  `places_max` int NOT NULL DEFAULT '20',
  `jour_semaine` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') COLLATE utf8mb4_unicode_ci NOT NULL,
  `session` enum('automne','hiver','printemps','ete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'automne',
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `tarif_mensuel` decimal(8,2) DEFAULT NULL,
  `type_tarif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mensuel',
  `montant` decimal(8,2) NOT NULL DEFAULT '0.00',
  `details_tarif` text COLLATE utf8mb4_unicode_ci,
  `salle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `couleur_calendrier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3B82F6',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `inscription_ouverte` tinyint(1) NOT NULL DEFAULT '1',
  `prerequis` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `ecole_id` bigint unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cours_jour_semaine_heure_debut_index` (`jour_semaine`,`heure_debut`),
  KEY `cours_instructeur_id_actif_index` (`instructeur_id`,`actif`),
  KEY `cours_actif_index` (`actif`),
  KEY `cours_ecole_id_actif_index` (`ecole_id`,`actif`),
  KEY `cours_session_index` (`session`),
  KEY `cours_session_jour_semaine_index` (`session`,`jour_semaine`),
  KEY `cours_parent_cours_id_foreign` (`parent_cours_id`),
  KEY `cours_group_uid_index` (`group_uid`),
  CONSTRAINT `cours_ecole_id_foreign` FOREIGN KEY (`ecole_id`) REFERENCES `ecoles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cours_instructeur_id_foreign` FOREIGN KEY (`instructeur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `cours_parent_cours_id_foreign` FOREIGN KEY (`parent_cours_id`) REFERENCES `cours_legacy` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: cours_membres

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
cours_id	bigint unsigned	NO		NULL	
membre_id	bigint unsigned	NO		NULL	
date_inscription	date	NO		2025-08-29	
statut	enum('actif','suspendu','termine')	NO		actif	
meta	json	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: cours_membres
Create Table: CREATE TABLE `cours_membres` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cours_id` bigint unsigned NOT NULL,
  `membre_id` bigint unsigned NOT NULL,
  `date_inscription` date NOT NULL DEFAULT '2025-08-29',
  `statut` enum('actif','suspendu','termine') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: cours_membres_legacy

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
cours_id	bigint unsigned	NO	MUL	NULL	
membre_id	bigint unsigned	NO	MUL	NULL	
date_inscription	date	NO		NULL	
date_fin	date	YES		NULL	
statut	enum('actif','inactif','suspendu')	NO	MUL	actif	
horaire_selectionne	varchar(25)	YES		NULL	
statut_validation	enum('pending','approuve','refuse')	NO		pending	
proposition_alternative	json	YES		NULL	
notes	text	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: cours_membres_legacy
Create Table: CREATE TABLE `cours_membres_legacy` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cours_id` bigint unsigned NOT NULL,
  `membre_id` bigint unsigned NOT NULL,
  `date_inscription` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `statut` enum('actif','inactif','suspendu') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `horaire_selectionne` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut_validation` enum('pending','approuve','refuse') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `proposition_alternative` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cours_membres_cours_id_membre_id_unique` (`cours_id`,`membre_id`),
  KEY `cours_membres_membre_id_foreign` (`membre_id`),
  KEY `cours_membres_statut_date_inscription_index` (`statut`,`date_inscription`),
  CONSTRAINT `cours_membres_cours_id_foreign` FOREIGN KEY (`cours_id`) REFERENCES `cours_legacy` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cours_membres_membre_id_foreign` FOREIGN KEY (`membre_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: ecoles

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
nom	varchar(255)	NO		NULL	
slug	varchar(255)	NO	UNI	NULL	
adresse	varchar(255)	YES		NULL	
ville	varchar(255)	YES		NULL	
code_postal	varchar(255)	YES		NULL	
province	varchar(255)	NO		QC	
telephone	varchar(255)	YES		NULL	
email	varchar(255)	YES		NULL	
site_web	varchar(255)	YES		NULL	
logo	varchar(255)	YES		NULL	
configuration	json	YES		NULL	
est_active	tinyint(1)	NO	MUL	1	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: ecoles
Create Table: CREATE TABLE `ecoles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'QC',
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `configuration` json DEFAULT NULL,
  `est_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ecoles_slug_unique` (`slug`),
  KEY `ecoles_slug_index` (`slug`),
  KEY `ecoles_est_active_index` (`est_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: examens

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
membre_id	bigint unsigned	NO	MUL	NULL	
ceinture_id	bigint unsigned	NO	MUL	NULL	
instructeur_id	bigint unsigned	NO	MUL	NULL	
date_examen	date	NO		NULL	
heure_examen	time	YES		NULL	
statut	enum('planifie','en_cours','reussi','echec','reporte')	NO		planifie	
note_technique	int	YES		NULL	
note_physique	int	YES		NULL	
note_kata	int	YES		NULL	
note_finale	int	YES		NULL	
commentaires	text	YES		NULL	
points_forts	text	YES		NULL	
points_amelioration	text	YES		NULL	
certificat_emis	tinyint(1)	NO		0	
date_certificat	date	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: examens
Create Table: CREATE TABLE `examens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `membre_id` bigint unsigned NOT NULL,
  `ceinture_id` bigint unsigned NOT NULL,
  `instructeur_id` bigint unsigned NOT NULL,
  `date_examen` date NOT NULL,
  `heure_examen` time DEFAULT NULL,
  `statut` enum('planifie','en_cours','reussi','echec','reporte') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planifie',
  `note_technique` int DEFAULT NULL,
  `note_physique` int DEFAULT NULL,
  `note_kata` int DEFAULT NULL,
  `note_finale` int DEFAULT NULL,
  `commentaires` text COLLATE utf8mb4_unicode_ci,
  `points_forts` text COLLATE utf8mb4_unicode_ci,
  `points_amelioration` text COLLATE utf8mb4_unicode_ci,
  `certificat_emis` tinyint(1) NOT NULL DEFAULT '0',
  `date_certificat` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `examens_membre_id_foreign` (`membre_id`),
  KEY `examens_ceinture_id_foreign` (`ceinture_id`),
  KEY `examens_instructeur_id_foreign` (`instructeur_id`),
  CONSTRAINT `examens_ceinture_id_foreign` FOREIGN KEY (`ceinture_id`) REFERENCES `ceintures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `examens_instructeur_id_foreign` FOREIGN KEY (`instructeur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `examens_membre_id_foreign` FOREIGN KEY (`membre_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: factures

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
numero_facture	varchar(255)	NO	UNI	NULL	
membre_id	bigint unsigned	NO	MUL	NULL	
family_id	bigint unsigned	YES	MUL	NULL	
date_emission	date	NO	MUL	NULL	
date_echeance	date	NO		NULL	
montant_ht	decimal(8,2)	NO		NULL	
montant_tps	decimal(8,2)	NO		0.00	
montant_tvq	decimal(8,2)	NO		0.00	
montant_total	decimal(8,2)	NO		NULL	
statut	enum('brouillon','envoyee','payee','en_retard','annulee')	NO	MUL	NULL	
details_lignes	json	NO		NULL	
notes	text	YES		NULL	
envoi_email	tinyint(1)	NO		0	
date_envoi	timestamp	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: factures
Create Table: CREATE TABLE `factures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_facture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `membre_id` bigint unsigned NOT NULL,
  `family_id` bigint unsigned DEFAULT NULL,
  `date_emission` date NOT NULL,
  `date_echeance` date NOT NULL,
  `montant_ht` decimal(8,2) NOT NULL,
  `montant_tps` decimal(8,2) NOT NULL DEFAULT '0.00',
  `montant_tvq` decimal(8,2) NOT NULL DEFAULT '0.00',
  `montant_total` decimal(8,2) NOT NULL,
  `statut` enum('brouillon','envoyee','payee','en_retard','annulee') COLLATE utf8mb4_unicode_ci NOT NULL,
  `details_lignes` json NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `envoi_email` tinyint(1) NOT NULL DEFAULT '0',
  `date_envoi` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `factures_numero_facture_unique` (`numero_facture`),
  KEY `factures_family_id_foreign` (`family_id`),
  KEY `factures_statut_date_echeance_index` (`statut`,`date_echeance`),
  KEY `factures_membre_id_index` (`membre_id`),
  KEY `factures_date_emission_index` (`date_emission`),
  CONSTRAINT `factures_family_id_foreign` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`) ON DELETE SET NULL,
  CONSTRAINT `factures_membre_id_foreign` FOREIGN KEY (`membre_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: failed_jobs

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
uuid	varchar(255)	NO	UNI	NULL	
connection	text	NO		NULL	
queue	text	NO		NULL	
payload	longtext	NO		NULL	
exception	longtext	NO		NULL	
failed_at	timestamp	NO		CURRENT_TIMESTAMP	DEFAULT_GENERATED
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: failed_jobs
Create Table: CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: families

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
name	varchar(255)	NO		NULL	
primary_contact_name	varchar(255)	NO		NULL	
primary_contact_email	varchar(255)	NO	UNI	NULL	
primary_contact_phone	varchar(255)	NO		NULL	
address	text	YES		NULL	
city	varchar(255)	YES		NULL	
postal_code	varchar(255)	YES		NULL	
province	varchar(255)	NO		QC	
discount_percentage	decimal(5,2)	NO		0.00	
notes	text	YES		NULL	
active	tinyint(1)	NO	MUL	1	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: families
Create Table: CREATE TABLE `families` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_contact_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'QC',
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `families_primary_contact_email_unique` (`primary_contact_email`),
  KEY `families_active_index` (`active`),
  KEY `families_primary_contact_email_index` (`primary_contact_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: job_batches

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	varchar(255)	NO	PRI	NULL	
name	varchar(255)	NO		NULL	
total_jobs	int	NO		NULL	
pending_jobs	int	NO		NULL	
failed_jobs	int	NO		NULL	
failed_job_ids	longtext	NO		NULL	
options	mediumtext	YES		NULL	
cancelled_at	int	YES		NULL	
created_at	int	NO		NULL	
finished_at	int	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: job_batches
Create Table: CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: jobs

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
queue	varchar(255)	NO	MUL	NULL	
payload	longtext	NO		NULL	
attempts	tinyint unsigned	NO		NULL	
reserved_at	int unsigned	YES		NULL	
available_at	int unsigned	NO		NULL	
created_at	int unsigned	NO		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: jobs
Create Table: CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: liens_familiaux

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
membre_id	bigint unsigned	NO	MUL	NULL	
membre_lie_id	bigint unsigned	NO	MUL	NULL	
ecole_id	bigint unsigned	NO		NULL	
type_relation	varchar(50)	NO		NULL	
est_tuteur_legal	tinyint(1)	NO		0	
contact_urgence	tinyint(1)	NO		0	
ordre_priorite	int	NO		1	
notes	text	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: liens_familiaux
Create Table: CREATE TABLE `liens_familiaux` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `membre_id` bigint unsigned NOT NULL,
  `membre_lie_id` bigint unsigned NOT NULL,
  `ecole_id` bigint unsigned NOT NULL,
  `type_relation` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `est_tuteur_legal` tinyint(1) NOT NULL DEFAULT '0',
  `contact_urgence` tinyint(1) NOT NULL DEFAULT '0',
  `ordre_priorite` int NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `liens_familiaux_membre_id_foreign` (`membre_id`),
  KEY `liens_familiaux_membre_lie_id_foreign` (`membre_lie_id`),
  CONSTRAINT `liens_familiaux_membre_id_foreign` FOREIGN KEY (`membre_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `liens_familiaux_membre_lie_id_foreign` FOREIGN KEY (`membre_lie_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: membres

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
ecole_id	bigint unsigned	YES	MUL	NULL	
user_id	bigint unsigned	YES	MUL	NULL	
prenom	varchar(255)	NO	MUL	NULL	
nom	varchar(255)	NO		NULL	
email	varchar(255)	NO	UNI	NULL	
telephone	varchar(255)	YES		NULL	
date_naissance	date	NO		NULL	
sexe	enum('M','F','Autre')	NO		Autre	
adresse	text	YES		NULL	
ville	varchar(255)	YES		NULL	
code_postal	varchar(255)	YES		NULL	
province	varchar(255)	NO		QC	
contact_urgence_nom	varchar(255)	YES		NULL	
contact_urgence_telephone	varchar(255)	YES		NULL	
contact_urgence_relation	varchar(255)	YES		NULL	
statut	enum('actif','inactif','suspendu')	NO	MUL	actif	
ceinture_actuelle_id	bigint unsigned	YES	MUL	NULL	
date_inscription	date	NO	MUL	NULL	
date_derniere_presence	date	YES		NULL	
notes_medicales	text	YES		NULL	
allergies	json	YES		NULL	
medicaments	json	YES		NULL	
consentement_photos	tinyint(1)	NO		0	
consentement_communications	tinyint(1)	NO		1	
date_consentement	timestamp	YES		NULL	
family_id	bigint unsigned	YES	MUL	NULL	
champs_personnalises	json	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
deleted_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: membres
Create Table: CREATE TABLE `membres` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ecole_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date NOT NULL,
  `sexe` enum('M','F','Autre') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Autre',
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'QC',
  `contact_urgence_nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_urgence_telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_urgence_relation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('actif','inactif','suspendu') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `ceinture_actuelle_id` bigint unsigned DEFAULT NULL,
  `date_inscription` date NOT NULL,
  `date_derniere_presence` date DEFAULT NULL,
  `notes_medicales` text COLLATE utf8mb4_unicode_ci,
  `allergies` json DEFAULT NULL,
  `medicaments` json DEFAULT NULL,
  `consentement_photos` tinyint(1) NOT NULL DEFAULT '0',
  `consentement_communications` tinyint(1) NOT NULL DEFAULT '1',
  `date_consentement` timestamp NULL DEFAULT NULL,
  `family_id` bigint unsigned DEFAULT NULL,
  `champs_personnalises` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membres_email_unique` (`email`),
  KEY `membres_user_id_foreign` (`user_id`),
  KEY `membres_ceinture_actuelle_id_foreign` (`ceinture_actuelle_id`),
  KEY `membres_family_id_foreign` (`family_id`),
  KEY `membres_statut_date_derniere_presence_index` (`statut`,`date_derniere_presence`),
  KEY `membres_prenom_nom_index` (`prenom`,`nom`),
  KEY `membres_date_inscription_index` (`date_inscription`),
  KEY `membres_ecole_id_index` (`ecole_id`),
  FULLTEXT KEY `membres_prenom_nom_email_fulltext` (`prenom`,`nom`,`email`),
  CONSTRAINT `membres_ceinture_actuelle_id_foreign` FOREIGN KEY (`ceinture_actuelle_id`) REFERENCES `ceintures` (`id`),
  CONSTRAINT `membres_ecole_id_foreign` FOREIGN KEY (`ecole_id`) REFERENCES `ecoles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `membres_family_id_foreign` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`) ON DELETE SET NULL,
  CONSTRAINT `membres_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: migrations

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	int unsigned	NO	PRI	NULL	auto_increment
migration	varchar(255)	NO		NULL	
batch	int	NO		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: migrations
Create Table: CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: model_has_permissions

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
permission_id	bigint unsigned	NO	PRI	NULL	
model_type	varchar(255)	NO	PRI	NULL	
model_id	bigint unsigned	NO	PRI	NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: model_has_permissions
Create Table: CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: model_has_roles

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
role_id	bigint unsigned	NO	PRI	NULL	
model_type	varchar(255)	NO	PRI	NULL	
model_id	bigint unsigned	NO	PRI	NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: model_has_roles
Create Table: CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: paiements

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
membre_id	bigint unsigned	NO	MUL	NULL	
type	enum('inscription','mensuel','examen','stage','equipement','autre')	NO		NULL	
montant	decimal(8,2)	NO		NULL	
description	varchar(255)	NO		NULL	
date_echeance	date	NO	MUL	NULL	
date_paiement	date	YES	MUL	NULL	
statut	enum('en_attente','paye','en_retard','annule')	NO		en_attente	
methode_paiement	enum('especes','cheque','virement','carte','en_ligne')	YES		NULL	
reference_transaction	varchar(255)	YES		NULL	
numero_facture	varchar(255)	YES	MUL	NULL	
notes	text	YES		NULL	
saisi_par_id	bigint unsigned	YES	MUL	NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: paiements
Create Table: CREATE TABLE `paiements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `membre_id` bigint unsigned NOT NULL,
  `type` enum('inscription','mensuel','examen','stage','equipement','autre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` decimal(8,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_echeance` date NOT NULL,
  `date_paiement` date DEFAULT NULL,
  `statut` enum('en_attente','paye','en_retard','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `methode_paiement` enum('especes','cheque','virement','carte','en_ligne') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_transaction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_facture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `saisi_par_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paiements_saisi_par_id_foreign` (`saisi_par_id`),
  KEY `paiements_date_echeance_statut_index` (`date_echeance`,`statut`),
  KEY `paiements_membre_id_statut_index` (`membre_id`,`statut`),
  KEY `paiements_date_paiement_index` (`date_paiement`),
  KEY `paiements_numero_facture_index` (`numero_facture`),
  CONSTRAINT `paiements_membre_id_foreign` FOREIGN KEY (`membre_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `paiements_saisi_par_id_foreign` FOREIGN KEY (`saisi_par_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: password_reset_tokens

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
email	varchar(255)	NO	PRI	NULL	
token	varchar(255)	NO		NULL	
created_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: password_reset_tokens
Create Table: CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: permissions

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
name	varchar(255)	NO	MUL	NULL	
guard_name	varchar(255)	NO		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: permissions
Create Table: CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: presences

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
cours_id	bigint unsigned	NO	MUL	NULL	
membre_id	bigint unsigned	NO	MUL	NULL	
instructeur_id	bigint unsigned	NO	MUL	NULL	
date_cours	date	NO	MUL	NULL	
statut	enum('present','absent','retard','excuse')	NO		present	
heure_arrivee	time	YES		NULL	
notes	text	YES		NULL	
validation_parent	tinyint(1)	NO		0	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: presences
Create Table: CREATE TABLE `presences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cours_id` bigint unsigned NOT NULL,
  `membre_id` bigint unsigned NOT NULL,
  `instructeur_id` bigint unsigned NOT NULL,
  `date_cours` date NOT NULL,
  `statut` enum('present','absent','retard','excuse') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `heure_arrivee` time DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `validation_parent` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presences_cours_id_membre_id_date_cours_unique` (`cours_id`,`membre_id`,`date_cours`),
  KEY `presences_membre_id_foreign` (`membre_id`),
  KEY `presences_date_cours_statut_index` (`date_cours`,`statut`),
  KEY `presences_instructeur_id_index` (`instructeur_id`),
  CONSTRAINT `presences_cours_id_foreign` FOREIGN KEY (`cours_id`) REFERENCES `cours_legacy` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presences_instructeur_id_foreign` FOREIGN KEY (`instructeur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `presences_membre_id_foreign` FOREIGN KEY (`membre_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: progression_ceintures

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
membre_id	bigint unsigned	NO	MUL	NULL	
ceinture_actuelle_id	bigint unsigned	NO	MUL	NULL	
ceinture_cible_id	bigint unsigned	NO	MUL	NULL	
instructeur_id	bigint unsigned	NO	MUL	NULL	
statut	enum('eligible','candidat','examen_planifie','examen_reussi','certifie','echec')	NO		eligible	
date_eligibilite	date	NO		NULL	
date_examen	date	YES	MUL	NULL	
notes_instructeur	text	YES		NULL	
evaluation_techniques	json	YES		NULL	
note_finale	int	YES		NULL	
recommandations	text	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: progression_ceintures
Create Table: CREATE TABLE `progression_ceintures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `membre_id` bigint unsigned NOT NULL,
  `ceinture_actuelle_id` bigint unsigned NOT NULL,
  `ceinture_cible_id` bigint unsigned NOT NULL,
  `instructeur_id` bigint unsigned NOT NULL,
  `statut` enum('eligible','candidat','examen_planifie','examen_reussi','certifie','echec') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'eligible',
  `date_eligibilite` date NOT NULL,
  `date_examen` date DEFAULT NULL,
  `notes_instructeur` text COLLATE utf8mb4_unicode_ci,
  `evaluation_techniques` json DEFAULT NULL,
  `note_finale` int DEFAULT NULL,
  `recommandations` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `progression_ceintures_ceinture_actuelle_id_foreign` (`ceinture_actuelle_id`),
  KEY `progression_ceintures_ceinture_cible_id_foreign` (`ceinture_cible_id`),
  KEY `progression_ceintures_instructeur_id_foreign` (`instructeur_id`),
  KEY `progression_ceintures_membre_id_statut_index` (`membre_id`,`statut`),
  KEY `progression_ceintures_date_examen_index` (`date_examen`),
  CONSTRAINT `progression_ceintures_ceinture_actuelle_id_foreign` FOREIGN KEY (`ceinture_actuelle_id`) REFERENCES `ceintures` (`id`),
  CONSTRAINT `progression_ceintures_ceinture_cible_id_foreign` FOREIGN KEY (`ceinture_cible_id`) REFERENCES `ceintures` (`id`),
  CONSTRAINT `progression_ceintures_instructeur_id_foreign` FOREIGN KEY (`instructeur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `progression_ceintures_membre_id_foreign` FOREIGN KEY (`membre_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: role_has_permissions

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
permission_id	bigint unsigned	NO	PRI	NULL	
role_id	bigint unsigned	NO	PRI	NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: role_has_permissions
Create Table: CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: roles

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
name	varchar(255)	NO	MUL	NULL	
guard_name	varchar(255)	NO		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: roles
Create Table: CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: sessions

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	varchar(255)	NO	PRI	NULL	
user_id	bigint unsigned	YES	MUL	NULL	
ip_address	varchar(45)	YES		NULL	
user_agent	text	YES		NULL	
payload	longtext	NO		NULL	
last_activity	int	NO	MUL	NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: sessions
Create Table: CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: telescope_entries

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
uuid	varchar(255)	NO	MUL	NULL	
batch_id	varchar(255)	NO	MUL	NULL	
family_hash	varchar(255)	YES	MUL	NULL	
should_display_on_index	varchar(255)	NO		1	
type	varchar(255)	NO	MUL	NULL	
content	text	NO		NULL	
user_id	text	YES		NULL	
user_type	varchar(255)	YES		NULL	
tags	varchar(255)	YES		NULL	
sequence	int	NO		0	
created_at	timestamp	YES	MUL	NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: telescope_entries
Create Table: CREATE TABLE `telescope_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` text COLLATE utf8mb4_unicode_ci,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sequence` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `telescope_entries_uuid_index` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_type_index` (`type`),
  KEY `telescope_entries_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=35228 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### Table: users

#### Structure:
```sql
Field	Type	Null	Key	Default	Extra
id	bigint unsigned	NO	PRI	NULL	auto_increment
name	varchar(255)	NO		NULL	
email	varchar(255)	NO	UNI	NULL	
email_verified_at	timestamp	YES		NULL	
password	varchar(255)	NO		NULL	
active	tinyint(1)	NO		1	
remember_token	varchar(100)	YES		NULL	
last_login_at	timestamp	YES		NULL	
created_at	timestamp	YES		NULL	
updated_at	timestamp	YES		NULL	
ecole_id	bigint unsigned	NO	MUL	NULL	
```

#### Commandes de création:
```sql
*************************** 1. row ***************************
       Table: users
Create Table: CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ecole_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_ecole_id_email_verified_at_index` (`ecole_id`,`email_verified_at`),
  CONSTRAINT `users_ecole_id_foreign` FOREIGN KEY (`ecole_id`) REFERENCES `ecoles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

## 3. Relations et Clés Étrangères

```sql
TABLE_NAME	COLUMN_NAME	CONSTRAINT_NAME	REFERENCED_TABLE_NAME	REFERENCED_COLUMN_NAME
cours_legacy	ecole_id	cours_ecole_id_foreign	ecoles	id
cours_legacy	instructeur_id	cours_instructeur_id_foreign	users	id
cours_legacy	parent_cours_id	cours_parent_cours_id_foreign	cours_legacy	id
cours_membres_legacy	cours_id	cours_membres_cours_id_foreign	cours_legacy	id
cours_membres_legacy	membre_id	cours_membres_membre_id_foreign	membres	id
examens	ceinture_id	examens_ceinture_id_foreign	ceintures	id
examens	instructeur_id	examens_instructeur_id_foreign	users	id
examens	membre_id	examens_membre_id_foreign	membres	id
factures	family_id	factures_family_id_foreign	families	id
factures	membre_id	factures_membre_id_foreign	membres	id
liens_familiaux	membre_id	liens_familiaux_membre_id_foreign	membres	id
liens_familiaux	membre_lie_id	liens_familiaux_membre_lie_id_foreign	membres	id
membres	ceinture_actuelle_id	membres_ceinture_actuelle_id_foreign	ceintures	id
membres	ecole_id	membres_ecole_id_foreign	ecoles	id
membres	family_id	membres_family_id_foreign	families	id
membres	user_id	membres_user_id_foreign	users	id
model_has_permissions	permission_id	model_has_permissions_permission_id_foreign	permissions	id
model_has_roles	role_id	model_has_roles_role_id_foreign	roles	id
paiements	membre_id	paiements_membre_id_foreign	membres	id
paiements	saisi_par_id	paiements_saisi_par_id_foreign	users	id
presences	cours_id	presences_cours_id_foreign	cours_legacy	id
presences	instructeur_id	presences_instructeur_id_foreign	users	id
presences	membre_id	presences_membre_id_foreign	membres	id
progression_ceintures	ceinture_actuelle_id	progression_ceintures_ceinture_actuelle_id_foreign	ceintures	id
progression_ceintures	ceinture_cible_id	progression_ceintures_ceinture_cible_id_foreign	ceintures	id
progression_ceintures	instructeur_id	progression_ceintures_instructeur_id_foreign	users	id
progression_ceintures	membre_id	progression_ceintures_membre_id_foreign	membres	id
role_has_permissions	permission_id	role_has_permissions_permission_id_foreign	permissions	id
role_has_permissions	role_id	role_has_permissions_role_id_foreign	roles	id
users	ecole_id	users_ecole_id_foreign	ecoles	id
```

## 4. Métriques Techniques

### Taille des tables:
```sql
Table	Size (MB)	Rows
telescope_entries	21.59	15026
membres	0.16	2
cours_legacy	0.14	4
progression_ceintures	0.08	0
presences	0.06	0
cours_membres_legacy	0.06	0
examens	0.06	0
activity_log	0.06	4
liens_familiaux	0.05	0
users	0.05	8
sessions	0.05	4
paiements	0.05	0
ceintures	0.05	21
factures	0.05	0
roles	0.03	4
model_has_permissions	0.03	0
model_has_roles	0.03	12
role_has_permissions	0.03	80
permissions	0.03	50
password_reset_tokens	0.02	0
cache	0.02	1
cache_locks	0.02	0
cours	0.02	4
cours_membres	0.02	0
families	0.02	0
ecoles	0.02	0
migrations	0.02	42
failed_jobs	0.02	0
jobs	0.02	0
job_batches	0.02	0
```

## 5. Index et Performances

### Index de la table: activity_log
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
activity_log	0	PRIMARY	1	id	A	4	NULL	NULL		BTREE			YES	NULL
activity_log	1	subject	1	subject_type	A	1	NULL	NULL	YES	BTREE			YES	NULL
activity_log	1	subject	2	subject_id	A	3	NULL	NULL	YES	BTREE			YES	NULL
activity_log	1	causer	1	causer_type	A	1	NULL	NULL	YES	BTREE			YES	NULL
activity_log	1	causer	2	causer_id	A	1	NULL	NULL	YES	BTREE			YES	NULL
activity_log	1	activity_log_log_name_index	1	log_name	A	1	NULL	NULL	YES	BTREE			YES	NULL
```

### Index de la table: cache
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
cache	0	PRIMARY	1	key	A	1	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: cache_locks
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
cache_locks	0	PRIMARY	1	key	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: ceintures
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
ceintures	0	PRIMARY	1	id	A	21	NULL	NULL		BTREE			YES	NULL
ceintures	0	belts_order_unique	1	order	A	21	NULL	NULL		BTREE			YES	NULL
ceintures	1	belts_active_index	1	active	A	1	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: cours
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
cours	0	PRIMARY	1	id	A	4	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: cours_legacy
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
cours_legacy	0	PRIMARY	1	id	A	4	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_jour_semaine_heure_debut_index	1	jour_semaine	A	1	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_jour_semaine_heure_debut_index	2	heure_debut	A	1	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_instructeur_id_actif_index	1	instructeur_id	A	1	NULL	NULL	YES	BTREE			YES	NULL
cours_legacy	1	cours_instructeur_id_actif_index	2	actif	A	2	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_actif_index	1	actif	A	2	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_ecole_id_actif_index	1	ecole_id	A	1	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_ecole_id_actif_index	2	actif	A	2	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_session_index	1	session	A	1	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_session_jour_semaine_index	1	session	A	1	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_session_jour_semaine_index	2	jour_semaine	A	1	NULL	NULL		BTREE			YES	NULL
cours_legacy	1	cours_parent_cours_id_foreign	1	parent_cours_id	A	4	NULL	NULL	YES	BTREE			YES	NULL
cours_legacy	1	cours_group_uid_index	1	group_uid	A	2	NULL	NULL	YES	BTREE			YES	NULL
```

### Index de la table: cours_membres
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
cours_membres	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: cours_membres_legacy
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
cours_membres_legacy	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
cours_membres_legacy	0	cours_membres_cours_id_membre_id_unique	1	cours_id	A	0	NULL	NULL		BTREE			YES	NULL
cours_membres_legacy	0	cours_membres_cours_id_membre_id_unique	2	membre_id	A	0	NULL	NULL		BTREE			YES	NULL
cours_membres_legacy	1	cours_membres_membre_id_foreign	1	membre_id	A	0	NULL	NULL		BTREE			YES	NULL
cours_membres_legacy	1	cours_membres_statut_date_inscription_index	1	statut	A	0	NULL	NULL		BTREE			YES	NULL
cours_membres_legacy	1	cours_membres_statut_date_inscription_index	2	date_inscription	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: ecoles
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
ecoles	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
ecoles	0	ecoles_slug_unique	1	slug	A	0	NULL	NULL		BTREE			YES	NULL
ecoles	1	ecoles_slug_index	1	slug	A	0	NULL	NULL		BTREE			YES	NULL
ecoles	1	ecoles_est_active_index	1	est_active	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: examens
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
examens	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
examens	1	examens_membre_id_foreign	1	membre_id	A	0	NULL	NULL		BTREE			YES	NULL
examens	1	examens_ceinture_id_foreign	1	ceinture_id	A	0	NULL	NULL		BTREE			YES	NULL
examens	1	examens_instructeur_id_foreign	1	instructeur_id	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: factures
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
factures	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
factures	0	factures_numero_facture_unique	1	numero_facture	A	0	NULL	NULL		BTREE			YES	NULL
factures	1	factures_family_id_foreign	1	family_id	A	0	NULL	NULL	YES	BTREE			YES	NULL
factures	1	factures_statut_date_echeance_index	1	statut	A	0	NULL	NULL		BTREE			YES	NULL
factures	1	factures_statut_date_echeance_index	2	date_echeance	A	0	NULL	NULL		BTREE			YES	NULL
factures	1	factures_membre_id_index	1	membre_id	A	0	NULL	NULL		BTREE			YES	NULL
factures	1	factures_date_emission_index	1	date_emission	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: failed_jobs
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
failed_jobs	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
failed_jobs	0	failed_jobs_uuid_unique	1	uuid	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: families
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
families	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
families	0	families_primary_contact_email_unique	1	primary_contact_email	A	0	NULL	NULL		BTREE			YES	NULL
families	1	families_active_index	1	active	A	0	NULL	NULL		BTREE			YES	NULL
families	1	families_primary_contact_email_index	1	primary_contact_email	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: job_batches
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
job_batches	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: jobs
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
jobs	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
jobs	1	jobs_queue_index	1	queue	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: liens_familiaux
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
liens_familiaux	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
liens_familiaux	1	liens_familiaux_membre_id_foreign	1	membre_id	A	0	NULL	NULL		BTREE			YES	NULL
liens_familiaux	1	liens_familiaux_membre_lie_id_foreign	1	membre_lie_id	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: membres
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
membres	0	PRIMARY	1	id	A	2	NULL	NULL		BTREE			YES	NULL
membres	0	membres_email_unique	1	email	A	2	NULL	NULL		BTREE			YES	NULL
membres	1	membres_user_id_foreign	1	user_id	A	2	NULL	NULL	YES	BTREE			YES	NULL
membres	1	membres_ceinture_actuelle_id_foreign	1	ceinture_actuelle_id	A	2	NULL	NULL	YES	BTREE			YES	NULL
membres	1	membres_family_id_foreign	1	family_id	A	1	NULL	NULL	YES	BTREE			YES	NULL
membres	1	membres_statut_date_derniere_presence_index	1	statut	A	1	NULL	NULL		BTREE			YES	NULL
membres	1	membres_statut_date_derniere_presence_index	2	date_derniere_presence	A	1	NULL	NULL	YES	BTREE			YES	NULL
membres	1	membres_prenom_nom_index	1	prenom	A	2	NULL	NULL		BTREE			YES	NULL
membres	1	membres_prenom_nom_index	2	nom	A	2	NULL	NULL		BTREE			YES	NULL
membres	1	membres_date_inscription_index	1	date_inscription	A	1	NULL	NULL		BTREE			YES	NULL
membres	1	membres_ecole_id_index	1	ecole_id	A	1	NULL	NULL	YES	BTREE			YES	NULL
membres	1	membres_prenom_nom_email_fulltext	1	prenom	NULL	2	NULL	NULL		FULLTEXT			YES	NULL
membres	1	membres_prenom_nom_email_fulltext	2	nom	NULL	2	NULL	NULL		FULLTEXT			YES	NULL
membres	1	membres_prenom_nom_email_fulltext	3	email	NULL	2	NULL	NULL		FULLTEXT			YES	NULL
```

### Index de la table: migrations
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
migrations	0	PRIMARY	1	id	A	38	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: model_has_permissions
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
model_has_permissions	0	PRIMARY	1	permission_id	A	0	NULL	NULL		BTREE			YES	NULL
model_has_permissions	0	PRIMARY	2	model_id	A	0	NULL	NULL		BTREE			YES	NULL
model_has_permissions	0	PRIMARY	3	model_type	A	0	NULL	NULL		BTREE			YES	NULL
model_has_permissions	1	model_has_permissions_model_id_model_type_index	1	model_id	A	0	NULL	NULL		BTREE			YES	NULL
model_has_permissions	1	model_has_permissions_model_id_model_type_index	2	model_type	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: model_has_roles
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
model_has_roles	0	PRIMARY	1	role_id	A	5	NULL	NULL		BTREE			YES	NULL
model_has_roles	0	PRIMARY	2	model_id	A	15	NULL	NULL		BTREE			YES	NULL
model_has_roles	0	PRIMARY	3	model_type	A	15	NULL	NULL		BTREE			YES	NULL
model_has_roles	1	model_has_roles_model_id_model_type_index	1	model_id	A	15	NULL	NULL		BTREE			YES	NULL
model_has_roles	1	model_has_roles_model_id_model_type_index	2	model_type	A	15	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: paiements
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
paiements	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
paiements	1	paiements_saisi_par_id_foreign	1	saisi_par_id	A	0	NULL	NULL	YES	BTREE			YES	NULL
paiements	1	paiements_date_echeance_statut_index	1	date_echeance	A	0	NULL	NULL		BTREE			YES	NULL
paiements	1	paiements_date_echeance_statut_index	2	statut	A	0	NULL	NULL		BTREE			YES	NULL
paiements	1	paiements_membre_id_statut_index	1	membre_id	A	0	NULL	NULL		BTREE			YES	NULL
paiements	1	paiements_membre_id_statut_index	2	statut	A	0	NULL	NULL		BTREE			YES	NULL
paiements	1	paiements_date_paiement_index	1	date_paiement	A	0	NULL	NULL	YES	BTREE			YES	NULL
paiements	1	paiements_numero_facture_index	1	numero_facture	A	0	NULL	NULL	YES	BTREE			YES	NULL
```

### Index de la table: password_reset_tokens
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
password_reset_tokens	0	PRIMARY	1	email	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: permissions
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
permissions	0	PRIMARY	1	id	A	50	NULL	NULL		BTREE			YES	NULL
permissions	0	permissions_name_guard_name_unique	1	name	A	50	NULL	NULL		BTREE			YES	NULL
permissions	0	permissions_name_guard_name_unique	2	guard_name	A	50	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: presences
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
presences	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
presences	0	presences_cours_id_membre_id_date_cours_unique	1	cours_id	A	0	NULL	NULL		BTREE			YES	NULL
presences	0	presences_cours_id_membre_id_date_cours_unique	2	membre_id	A	0	NULL	NULL		BTREE			YES	NULL
presences	0	presences_cours_id_membre_id_date_cours_unique	3	date_cours	A	0	NULL	NULL		BTREE			YES	NULL
presences	1	presences_membre_id_foreign	1	membre_id	A	0	NULL	NULL		BTREE			YES	NULL
presences	1	presences_date_cours_statut_index	1	date_cours	A	0	NULL	NULL		BTREE			YES	NULL
presences	1	presences_date_cours_statut_index	2	statut	A	0	NULL	NULL		BTREE			YES	NULL
presences	1	presences_instructeur_id_index	1	instructeur_id	A	0	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: progression_ceintures
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
progression_ceintures	0	PRIMARY	1	id	A	0	NULL	NULL		BTREE			YES	NULL
progression_ceintures	1	progression_ceintures_ceinture_actuelle_id_foreign	1	ceinture_actuelle_id	A	0	NULL	NULL		BTREE			YES	NULL
progression_ceintures	1	progression_ceintures_ceinture_cible_id_foreign	1	ceinture_cible_id	A	0	NULL	NULL		BTREE			YES	NULL
progression_ceintures	1	progression_ceintures_instructeur_id_foreign	1	instructeur_id	A	0	NULL	NULL		BTREE			YES	NULL
progression_ceintures	1	progression_ceintures_membre_id_statut_index	1	membre_id	A	0	NULL	NULL		BTREE			YES	NULL
progression_ceintures	1	progression_ceintures_membre_id_statut_index	2	statut	A	0	NULL	NULL		BTREE			YES	NULL
progression_ceintures	1	progression_ceintures_date_examen_index	1	date_examen	A	0	NULL	NULL	YES	BTREE			YES	NULL
```

### Index de la table: role_has_permissions
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
role_has_permissions	0	PRIMARY	1	permission_id	A	55	NULL	NULL		BTREE			YES	NULL
role_has_permissions	0	PRIMARY	2	role_id	A	80	NULL	NULL		BTREE			YES	NULL
role_has_permissions	1	role_has_permissions_role_id_foreign	1	role_id	A	5	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: roles
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
roles	0	PRIMARY	1	id	A	4	NULL	NULL		BTREE			YES	NULL
roles	0	roles_name_guard_name_unique	1	name	A	4	NULL	NULL		BTREE			YES	NULL
roles	0	roles_name_guard_name_unique	2	guard_name	A	4	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: sessions
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
sessions	0	PRIMARY	1	id	A	1	NULL	NULL		BTREE			YES	NULL
sessions	1	sessions_user_id_index	1	user_id	A	1	NULL	NULL	YES	BTREE			YES	NULL
sessions	1	sessions_last_activity_index	1	last_activity	A	1	NULL	NULL		BTREE			YES	NULL
```

### Index de la table: telescope_entries
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
telescope_entries	0	PRIMARY	1	id	A	14012	NULL	NULL		BTREE			YES	NULL
telescope_entries	1	telescope_entries_uuid_index	1	uuid	A	15043	NULL	NULL		BTREE			YES	NULL
telescope_entries	1	telescope_entries_batch_id_index	1	batch_id	A	951	NULL	NULL		BTREE			YES	NULL
telescope_entries	1	telescope_entries_family_hash_index	1	family_hash	A	10	NULL	NULL	YES	BTREE			YES	NULL
telescope_entries	1	telescope_entries_type_index	1	type	A	10	NULL	NULL		BTREE			YES	NULL
telescope_entries	1	telescope_entries_created_at_index	1	created_at	A	730	NULL	NULL	YES	BTREE			YES	NULL
```

### Index de la table: users
```sql
Table	Non_unique	Key_name	Seq_in_index	Column_name	Collation	Cardinality	Sub_part	Packed	Null	Index_type	Comment	Index_comment	Visible	Expression
users	0	PRIMARY	1	id	A	9	NULL	NULL		BTREE			YES	NULL
users	0	users_email_unique	1	email	A	9	NULL	NULL		BTREE			YES	NULL
users	1	users_ecole_id_email_verified_at_index	1	ecole_id	A	1	NULL	NULL		BTREE			YES	NULL
users	1	users_ecole_id_email_verified_at_index	2	email_verified_at	A	2	NULL	NULL	YES	BTREE			YES	NULL
```


-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: studiosdb
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ceintures`
--

DROP TABLE IF EXISTS `ceintures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ceintures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ceintures`
--

LOCK TABLES `ceintures` WRITE;
/*!40000 ALTER TABLE `ceintures` DISABLE KEYS */;
/*!40000 ALTER TABLE `ceintures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cours`
--

DROP TABLE IF EXISTS `cours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ecole_id` bigint unsigned NOT NULL,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `niveau` enum('debutant','intermediaire','avance','tous_niveaux') COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacite_max` int NOT NULL DEFAULT '20',
  `prix` decimal(8,2) DEFAULT NULL,
  `duree_minutes` int NOT NULL DEFAULT '60',
  `instructeur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cours_ecole_id_foreign` (`ecole_id`),
  CONSTRAINT `cours_ecole_id_foreign` FOREIGN KEY (`ecole_id`) REFERENCES `ecoles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cours`
--

LOCK TABLES `cours` WRITE;
/*!40000 ALTER TABLE `cours` DISABLE KEYS */;
/*!40000 ALTER TABLE `cours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cours_horaires`
--

DROP TABLE IF EXISTS `cours_horaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cours_horaires` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cours_id` bigint unsigned NOT NULL,
  `jour_semaine` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') COLLATE utf8mb4_unicode_ci NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cours_horaires_cours_id_foreign` (`cours_id`),
  CONSTRAINT `cours_horaires_cours_id_foreign` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cours_horaires`
--

LOCK TABLES `cours_horaires` WRITE;
/*!40000 ALTER TABLE `cours_horaires` DISABLE KEYS */;
/*!40000 ALTER TABLE `cours_horaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ecoles`
--

DROP TABLE IF EXISTS `ecoles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ecoles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'QC',
  `code_postal` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_web` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ecoles_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ecoles`
--

LOCK TABLES `ecoles` WRITE;
/*!40000 ALTER TABLE `ecoles` DISABLE KEYS */;
/*!40000 ALTER TABLE `ecoles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscriptions_cours`
--

DROP TABLE IF EXISTS `inscriptions_cours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscriptions_cours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `cours_id` bigint unsigned NOT NULL,
  `date_inscription` date NOT NULL DEFAULT '2025-06-21',
  `statut` enum('inscrit','confirme','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inscrit',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inscriptions_cours_user_id_cours_id_unique` (`user_id`,`cours_id`),
  KEY `inscriptions_cours_cours_id_foreign` (`cours_id`),
  CONSTRAINT `inscriptions_cours_cours_id_foreign` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inscriptions_cours_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscriptions_cours`
--

LOCK TABLES `inscriptions_cours` WRITE;
/*!40000 ALTER TABLE `inscriptions_cours` DISABLE KEYS */;
/*!40000 ALTER TABLE `inscriptions_cours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscriptions_seminaires`
--

DROP TABLE IF EXISTS `inscriptions_seminaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscriptions_seminaires` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `seminaire_id` bigint unsigned NOT NULL,
  `date_inscription` date NOT NULL DEFAULT '2025-06-21',
  `statut` enum('inscrit','confirme','present','absent','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inscrit',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inscriptions_seminaires_user_id_seminaire_id_unique` (`user_id`,`seminaire_id`),
  KEY `inscriptions_seminaires_seminaire_id_foreign` (`seminaire_id`),
  CONSTRAINT `inscriptions_seminaires_seminaire_id_foreign` FOREIGN KEY (`seminaire_id`) REFERENCES `seminaires` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inscriptions_seminaires_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscriptions_seminaires`
--

LOCK TABLES `inscriptions_seminaires` WRITE;
/*!40000 ALTER TABLE `inscriptions_seminaires` DISABLE KEYS */;
/*!40000 ALTER TABLE `inscriptions_seminaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membre_ceintures`
--

DROP TABLE IF EXISTS `membre_ceintures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membre_ceintures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ceinture_id` bigint unsigned NOT NULL,
  `date_obtention` date NOT NULL,
  `examinateur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commentaires` text COLLATE utf8mb4_unicode_ci,
  `valide` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `membre_ceintures_ceinture_id_foreign` (`ceinture_id`),
  KEY `membre_ceintures_user_id_date_obtention_index` (`user_id`,`date_obtention`),
  CONSTRAINT `membre_ceintures_ceinture_id_foreign` FOREIGN KEY (`ceinture_id`) REFERENCES `ceintures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `membre_ceintures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membre_ceintures`
--

LOCK TABLES `membre_ceintures` WRITE;
/*!40000 ALTER TABLE `membre_ceintures` DISABLE KEYS */;
/*!40000 ALTER TABLE `membre_ceintures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_06_18_200100_create_ecoles_table',1),(5,'2025_06_18_200200_create_ceintures_table',1),(6,'2025_06_18_200300_add_fields_to_users_table',1),(7,'2025_06_18_200500_create_cours_table',1),(8,'2025_06_18_200600_create_seminaires_table',1),(9,'2025_06_18_200700_create_cours_horaires_table',1),(10,'2025_06_18_200800_create_membre_ceintures_table',1),(11,'2025_06_18_200900_create_inscriptions_cours_table',1),(12,'2025_06_18_201000_create_inscriptions_seminaires_table',1),(13,'2025_06_18_201100_create_presences_table',1),(14,'2025_06_18_201200_create_paiements_table',1),(15,'2025_06_18_230000_create_permission_tables',1),(16,'2025_06_18_230100_create_activity_log_table',1),(17,'2025_06_19_124116_create_telescope_entries_table',1),(18,'2025_06_20_111718_fix_activity_log_batch_uuid_column',1),(19,'2025_06_20_180000_create_unified_users_system',1),(20,'2025_06_20_185000_fix_activity_log_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paiements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ecole_id` bigint unsigned NOT NULL,
  `processed_by_user_id` bigint unsigned DEFAULT NULL,
  `reference_interne` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_paiement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'interac',
  `motif` enum('session_automne','session_hiver','session_printemps','session_ete','seminaire','examen_ceinture','equipement','autre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant` decimal(10,2) NOT NULL,
  `frais` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montant_net` decimal(10,2) NOT NULL,
  `email_expediteur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_expediteur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_interac` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_interac` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('en_attente','recu','valide','rejete','rembourse') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `date_facture` timestamp NULL DEFAULT NULL,
  `date_echeance` timestamp NULL DEFAULT NULL,
  `date_reception` timestamp NULL DEFAULT NULL,
  `date_validation` timestamp NULL DEFAULT NULL,
  `periode_facturation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annee_fiscale` year NOT NULL DEFAULT '2025',
  `recu_fiscal_emis` tinyint(1) NOT NULL DEFAULT '0',
  `metadonnees` json DEFAULT NULL,
  `notes_admin` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `paiements_reference_interne_unique` (`reference_interne`),
  KEY `paiements_processed_by_user_id_foreign` (`processed_by_user_id`),
  KEY `paiements_ecole_id_statut_index` (`ecole_id`,`statut`),
  KEY `paiements_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `paiements_reference_interne_index` (`reference_interne`),
  KEY `paiements_type_paiement_statut_index` (`type_paiement`,`statut`),
  CONSTRAINT `paiements_ecole_id_foreign` FOREIGN KEY (`ecole_id`) REFERENCES `ecoles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `paiements_processed_by_user_id_foreign` FOREIGN KEY (`processed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `paiements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paiements`
--

LOCK TABLES `paiements` WRITE;
/*!40000 ALTER TABLE `paiements` DISABLE KEYS */;
/*!40000 ALTER TABLE `paiements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presences`
--

DROP TABLE IF EXISTS `presences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `cours_id` bigint unsigned NOT NULL,
  `date_cours` date NOT NULL,
  `present` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presences_user_id_cours_id_date_cours_unique` (`user_id`,`cours_id`,`date_cours`),
  KEY `presences_cours_id_date_cours_index` (`cours_id`,`date_cours`),
  CONSTRAINT `presences_cours_id_foreign` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presences`
--

LOCK TABLES `presences` WRITE;
/*!40000 ALTER TABLE `presences` DISABLE KEYS */;
/*!40000 ALTER TABLE `presences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seminaires`
--

DROP TABLE IF EXISTS `seminaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seminaires` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ecole_id` bigint unsigned NOT NULL,
  `titre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('technique','kata','competition','arbitrage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `instructeur` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacite_max` int NOT NULL DEFAULT '30',
  `prix` decimal(8,2) DEFAULT NULL,
  `niveau_requis` enum('debutant','intermediaire','avance','tous_niveaux') COLLATE utf8mb4_unicode_ci NOT NULL,
  `inscription_ouverte` tinyint(1) NOT NULL DEFAULT '1',
  `materiel_requis` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seminaires_ecole_id_foreign` (`ecole_id`),
  CONSTRAINT `seminaires_ecole_id_foreign` FOREIGN KEY (`ecole_id`) REFERENCES `ecoles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seminaires`
--

LOCK TABLES `seminaires` WRITE;
/*!40000 ALTER TABLE `seminaires` DISABLE KEYS */;
/*!40000 ALTER TABLE `seminaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_entries`
--

DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries`
--

LOCK TABLES `telescope_entries` WRITE;
/*!40000 ALTER TABLE `telescope_entries` DISABLE KEYS */;
INSERT INTO `telescope_entries` VALUES (167,'9f35cc2b-031a-4e31-8dfd-0288c8c1d548','9f35cc2b-033a-4fd6-bc46-075ececb9a60',NULL,1,'request','{\"ip_address\":\"127.0.0.1\",\"uri\":\"\\/\",\"method\":\"GET\",\"controller_action\":\"Closure\",\"middleware\":[\"web\"],\"headers\":{\"host\":\"localhost\",\"user-agent\":\"Symfony\",\"accept\":\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,*\\/*;q=0.8\",\"accept-language\":\"en-us,en;q=0.5\",\"accept-charset\":\"ISO-8859-1,utf-8;q=0.7,*;q=0.7\"},\"payload\":[],\"session\":{\"_token\":\"pLGdDCtY26dStYOzQrw0MFzBZ6OyubjWy7vUMRQD\",\"_previous\":{\"url\":\"http:\\/\\/localhost\"},\"_flash\":{\"old\":[],\"new\":[]}},\"response_headers\":{\"content-type\":\"text\\/html; charset=UTF-8\",\"cache-control\":\"no-cache, private\",\"date\":\"Sat, 21 Jun 2025 19:27:54 GMT\",\"set-cookie\":\"XSRF-TOKEN=eyJpdiI6IkduM2tOZXlSZ3JuRDZQK3FwRDFHcmc9PSIsInZhbHVlIjoiclAvb2lGR3IvMTVIYThST3FiRzBIaTFEcFdnYUZDL2xQeHg0bHJxWWUwWnVmbkhoVGUzelRHdmRrWGdOQnhKOWdzNXYvaWlKMUV2bDlxVFBBRytHNmJ6WEpTNTZWQ0xnRytmUnJEV1hodm5qMjJmYURQMkQyMmIwL2kycUtrK3IiLCJtYWMiOiIxM2Q3YzdmNjVhNDdiZDkzNTExOTZlNzE3NDUzM2E5ZGViYTE0ZjU1ZjUzNjYyYzFlODQxZWIzNTFhY2E2N2E2IiwidGFnIjoiIn0%3D; expires=Sat, 21 Jun 2025 21:27:54 GMT; Max-Age=7200; path=\\/; samesite=lax, studiosunisdb_session=eyJpdiI6ImdFZVlWeHFyeFpXRE9uMjJwSzkwcEE9PSIsInZhbHVlIjoicll0Z1ZvN3p4ZlluUUVhNi9zeVFTTER5cjN0RlVMelcxdVRxZEhYZTZkM2RSaGhQSDBPSzErRlZtbWYrcmJkQ2JvTGh2bzdNY2tWSXpSYi9NYlpPWkpJclI1WEp6MzBDQVdlRjMvakV1U05RQXIyekkxV2J6c3RZS2IvSi9Ec2EiLCJtYWMiOiIzNjdmMmI5NjZlY2IzMzY5NDkxNDAxYmFmZTczNThhYjBiZDAzZWIxZTdkZjYwNGM0YmVjMDFmODRhZDc4ZDE3IiwidGFnIjoiIn0%3D; expires=Sat, 21 Jun 2025 21:27:54 GMT; Max-Age=7200; path=\\/; httponly; samesite=lax\"},\"response_status\":200,\"response\":{\"view\":\"\\/home\\/studiosdb\\/studiosunisdb\\/resources\\/views\\/welcome.blade.php\",\"data\":[]},\"duration\":2,\"memory\":54.5,\"hostname\":\"lalpha\"}','2025-06-21 15:27:54'),(195,'9f35cc2b-a8cd-4a79-aefd-c38171cea221','9f35cc2b-aa25-439d-8461-bd39e3137f51',NULL,1,'command','{\"command\":\"test\",\"exit_code\":2,\"arguments\":{\"command\":\"test\"},\"options\":{\"without-tty\":true,\"compact\":false,\"coverage\":false,\"min\":null,\"parallel\":false,\"profile\":false,\"recreate-databases\":false,\"drop-databases\":false,\"without-databases\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:27:54'),(196,'9f35ccff-ac1d-4d3e-b993-92dcb6295c0d','9f35ccff-ad1a-4e4f-9406-510b4369f943',NULL,1,'command','{\"command\":\"route:list\",\"exit_code\":0,\"arguments\":{\"command\":\"route:list\"},\"options\":{\"json\":false,\"method\":null,\"action\":null,\"name\":null,\"domain\":null,\"path\":null,\"except-path\":null,\"reverse\":false,\"sort\":\"uri\",\"except-vendor\":false,\"only-vendor\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:13'),(197,'9f35ccff-ea8c-41c2-9821-3591f67923a1','9f35ccff-eb8e-496c-803e-29d974676f71',NULL,1,'command','{\"command\":\"route:list\",\"exit_code\":0,\"arguments\":{\"command\":\"route:list\"},\"options\":{\"json\":false,\"method\":null,\"action\":null,\"name\":\"admin\",\"domain\":null,\"path\":null,\"except-path\":null,\"reverse\":false,\"sort\":\"uri\",\"except-vendor\":false,\"only-vendor\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:14'),(198,'9f35cd00-28df-4ca7-afa1-dba9a32ee0bd','9f35cd00-29e6-4a05-a649-a5bbbc9a7f5e',NULL,1,'command','{\"command\":\"route:list\",\"exit_code\":0,\"arguments\":{\"command\":\"route:list\"},\"options\":{\"json\":false,\"method\":null,\"action\":null,\"name\":\"api\",\"domain\":null,\"path\":null,\"except-path\":null,\"reverse\":false,\"sort\":\"uri\",\"except-vendor\":false,\"only-vendor\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:14'),(199,'9f35cd00-6afd-46c4-aad5-1421ea8e6a68','9f35cd00-6bfb-4a99-b3bf-9f4e89e54805',NULL,1,'command','{\"command\":\"route:list\",\"exit_code\":0,\"arguments\":{\"command\":\"route:list\"},\"options\":{\"json\":false,\"method\":null,\"action\":null,\"name\":null,\"domain\":null,\"path\":null,\"except-path\":null,\"reverse\":false,\"sort\":\"uri\",\"except-vendor\":false,\"only-vendor\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:14'),(200,'9f35cd00-a83a-4501-a749-ab9f870cff78','9f35cd00-a938-439e-9ba4-d825e87deed5',NULL,1,'command','{\"command\":\"route:list\",\"exit_code\":0,\"arguments\":{\"command\":\"route:list\"},\"options\":{\"json\":false,\"method\":null,\"action\":null,\"name\":\"admin\",\"domain\":null,\"path\":null,\"except-path\":null,\"reverse\":false,\"sort\":\"uri\",\"except-vendor\":false,\"only-vendor\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:14'),(201,'9f35cd01-0180-4848-b18f-2cbf53ba4664','9f35cd01-02b7-4d37-a3df-0415b29db07a',NULL,1,'command','{\"command\":\"list\",\"exit_code\":0,\"arguments\":{\"command\":\"list\",\"namespace\":null},\"options\":{\"raw\":false,\"format\":\"txt\",\"short\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:14'),(202,'9f35cd01-4ae5-474f-b44b-4e577668d139','9f35cd01-4c00-4a2b-8bf1-6884397b6d9b',NULL,1,'command','{\"command\":\"tinker\",\"exit_code\":0,\"arguments\":{\"command\":\"tinker\",\"include\":[]},\"options\":{\"execute\":\"echo \'DB Test: \' . \\\\DB::connection()->getPdo()->getAttribute(\\\\PDO::ATTR_CONNECTION_STATUS);\",\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:14'),(203,'9f35cd01-9418-4105-8061-5a9ab47ca35b','9f35cd01-955b-488a-8fd6-2f8e7b6786d2',NULL,1,'query','{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select count(*) as aggregate from `users`\",\"time\":\"0.27\",\"slow\":false,\"file\":\"\\/home\\/studiosdb\\/studiosunisdb\\/artisan\",\"line\":16,\"hash\":\"6c5274cfac96d79f6367317dfb756038\",\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(204,'9f35cd01-9511-44ed-b6b6-2b597b75fb34','9f35cd01-955b-488a-8fd6-2f8e7b6786d2',NULL,1,'command','{\"command\":\"tinker\",\"exit_code\":0,\"arguments\":{\"command\":\"tinker\",\"include\":[]},\"options\":{\"execute\":\"echo \'Users count: \' . \\\\App\\\\Models\\\\User::count();\",\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(205,'9f35cd01-d454-4353-a9d5-a505983272bc','9f35cd01-d5bd-45f6-a4da-de441845ab6e',NULL,1,'command','{\"command\":\"route:list\",\"exit_code\":0,\"arguments\":{\"command\":\"route:list\"},\"options\":{\"json\":false,\"method\":null,\"action\":null,\"name\":null,\"domain\":null,\"path\":null,\"except-path\":null,\"reverse\":false,\"sort\":\"uri\",\"except-vendor\":false,\"only-vendor\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(206,'9f35cd02-1871-4ddd-8ff9-3f38461f2bf4','9f35cd02-1c00-443d-9c89-24b9a8833dc1',NULL,1,'query','{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select * from `cache` where `key` in (\'studiosunisdb_cache_telescope:pause-recording\')\",\"time\":\"0.74\",\"slow\":false,\"file\":\"\\/home\\/studiosdb\\/studiosunisdb\\/artisan\",\"line\":16,\"hash\":\"423396ec9e2f98d907ef4fff47152199\",\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(207,'9f35cd02-1bb1-42a3-a1ff-6894d77defc9','9f35cd02-1c00-443d-9c89-24b9a8833dc1',NULL,1,'command','{\"command\":\"config:cache\",\"exit_code\":0,\"arguments\":{\"command\":\"config:cache\"},\"options\":{\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(208,'9f35cd02-56d0-4c48-a3b1-117d1a9a6a2d','9f35cd02-5f69-4719-b10d-04dbbe0a27cc',NULL,1,'query','{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select * from `cache` where `key` in (\'studiosunisdb_cache_telescope:pause-recording\')\",\"time\":\"0.80\",\"slow\":false,\"file\":\"\\/home\\/studiosdb\\/studiosunisdb\\/artisan\",\"line\":16,\"hash\":\"423396ec9e2f98d907ef4fff47152199\",\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(209,'9f35cd02-5f15-4b3d-b5d2-7df92f8fdcb3','9f35cd02-5f69-4719-b10d-04dbbe0a27cc',NULL,1,'command','{\"command\":\"route:cache\",\"exit_code\":0,\"arguments\":{\"command\":\"route:cache\"},\"options\":{\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(210,'9f35cd02-99b0-4dbb-bedd-70ec8996273e','9f35cd02-9b47-4128-80e2-d543a58c0083',NULL,1,'query','{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select `guard_name` from `permissions`\",\"time\":\"0.31\",\"slow\":false,\"file\":\"\\/home\\/studiosdb\\/studiosunisdb\\/artisan\",\"line\":16,\"hash\":\"ba4824e17d0cc9a412f0593ebee5f479\",\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(211,'9f35cd02-9aca-4a7b-9941-1e4f802815d0','9f35cd02-9b47-4128-80e2-d543a58c0083',NULL,1,'query','{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select `guard_name` from `roles`\",\"time\":\"0.29\",\"slow\":false,\"file\":\"\\/home\\/studiosdb\\/studiosunisdb\\/artisan\",\"line\":16,\"hash\":\"4dc19cec5448c46bafaea4ffe4ab20b3\",\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(212,'9f35cd02-9af0-466f-96a1-3763c3b30cf4','9f35cd02-9b47-4128-80e2-d543a58c0083',NULL,1,'command','{\"command\":\"permission:show\",\"exit_code\":0,\"arguments\":{\"command\":\"permission:show\",\"guard\":null,\"style\":null},\"options\":{\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:15'),(213,'9f35cd02-e4b6-4ce6-87d5-a2b92039d0c9','9f35cd02-e5f1-4c65-a95b-ce4b5db6f7c3',NULL,1,'query','{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select count(*) as aggregate from `users`\",\"time\":\"0.31\",\"slow\":false,\"file\":\"\\/home\\/studiosdb\\/studiosunisdb\\/artisan\",\"line\":16,\"hash\":\"6c5274cfac96d79f6367317dfb756038\",\"hostname\":\"lalpha\"}','2025-06-21 15:30:16'),(214,'9f35cd02-e5a8-4bbb-96e4-7874fdb0994b','9f35cd02-e5f1-4c65-a95b-ce4b5db6f7c3',NULL,1,'command','{\"command\":\"tinker\",\"exit_code\":0,\"arguments\":{\"command\":\"tinker\",\"include\":[]},\"options\":{\"execute\":\"\\\\App\\\\Models\\\\User::count();\",\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:16'),(215,'9f35cd03-2ec0-4d16-9bca-5de0979feff3','9f35cd03-303e-455d-a24e-900fced7a28f',NULL,1,'query','{\"connection\":\"mysql\",\"bindings\":[],\"sql\":\"select * from `users` limit 1\",\"time\":\"0.34\",\"slow\":false,\"file\":\"\\/home\\/studiosdb\\/studiosunisdb\\/artisan\",\"line\":16,\"hash\":\"26d128571acc3ade5f7d4401c1737345\",\"hostname\":\"lalpha\"}','2025-06-21 15:30:16'),(216,'9f35cd03-2fef-48e8-a946-74f54f895af2','9f35cd03-303e-455d-a24e-900fced7a28f',NULL,1,'command','{\"command\":\"tinker\",\"exit_code\":0,\"arguments\":{\"command\":\"tinker\",\"include\":[]},\"options\":{\"execute\":\"\\\\App\\\\Models\\\\User::with(\'ecole\')->first();\",\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:16'),(217,'9f35cd03-bcc2-4852-bd55-05c4ddb9379d','9f35cd03-bdc0-417a-bb1a-3fe1174909d8',NULL,1,'command','{\"command\":\"route:list\",\"exit_code\":0,\"arguments\":{\"command\":\"route:list\"},\"options\":{\"json\":false,\"method\":null,\"action\":null,\"name\":null,\"domain\":null,\"path\":null,\"except-path\":null,\"reverse\":false,\"sort\":\"uri\",\"except-vendor\":false,\"only-vendor\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:16'),(218,'9f35cd03-fb8d-4f16-97e5-c0a1af8f675a','9f35cd03-fc93-43c4-bfe1-85b87e700035',NULL,1,'command','{\"command\":\"route:list\",\"exit_code\":0,\"arguments\":{\"command\":\"route:list\"},\"options\":{\"json\":false,\"method\":null,\"action\":null,\"name\":\"admin\",\"domain\":null,\"path\":null,\"except-path\":null,\"reverse\":false,\"sort\":\"uri\",\"except-vendor\":false,\"only-vendor\":false,\"help\":false,\"silent\":false,\"quiet\":false,\"verbose\":false,\"version\":false,\"ansi\":null,\"no-interaction\":false,\"env\":null},\"hostname\":\"lalpha\"}','2025-06-21 15:30:16'),(219,'9f35d299-e207-48b4-8c68-e972232ae54b','9f35d299-e257-4cc9-b739-e485536ab9ff',NULL,1,'request','{\"ip_address\":\"128.203.200.235\",\"uri\":\"\\/\",\"method\":\"GET\",\"controller_action\":\"Closure\",\"middleware\":[\"web\"],\"headers\":{\"accept-encoding\":\"gzip\",\"accept\":\"*\\/*\",\"user-agent\":\"Mozilla\\/5.0 zgrab\\/0.x\",\"host\":\"207.253.150.57\"},\"payload\":[],\"session\":{\"_token\":\"umKjn3xWKSfdwBiP6mGGPp3zTgiBQBRWDh1EsAhO\",\"_previous\":{\"url\":\"https:\\/\\/207.253.150.57\"},\"_flash\":{\"old\":[],\"new\":[]}},\"response_headers\":{\"content-type\":\"text\\/html; charset=UTF-8\",\"cache-control\":\"no-cache, private\",\"date\":\"Sat, 21 Jun 2025 19:45:53 GMT\",\"set-cookie\":\"XSRF-TOKEN=eyJpdiI6IjJNTGd2dVZWdWJhSW5hUjBQRDBnclE9PSIsInZhbHVlIjoiMDRuU0VWWXBaZWpyalF2a0NOMjlxNUVKN052bTlMNGtGUmZHMFFnb212bGNSOE4xZmpUZVFpWDhFcUxNNVo2UHV5cW51aGlCcHNSbnRQd0JTdEkvUm9Wcjd2ZENpemlYSjZkZ2hka2dHZ2Z2eXhVM0ZYcG8wR25JUm5vdmpOOS8iLCJtYWMiOiI0OTBmZTc0YzVlMWE3MTg1N2Y4NGNhNWFhMzFkYTdjMDg4M2Y2Mjk2ODRkYjQzY2ViZWU2YTI4MzNhMTg0NjVmIiwidGFnIjoiIn0%3D; expires=Sat, 21 Jun 2025 21:45:53 GMT; Max-Age=7200; path=\\/; secure; samesite=lax, studiosunisdb_session=eyJpdiI6IjA0REpGNk5WTHA4cFVTcnFtTVhlZmc9PSIsInZhbHVlIjoiOENYbXU1V0FjNUVOSzV6M0xEVTBTcDI2L3JpN1FHUERnN1JNUnlqNlVTRWlQenFaRzVMdzBOVXNRVGlYT3NRWWZGR2lUd282U2VHUUV1YUdvNnRJaXFoN2hwa2g4Y0RLWFRNdk9kOE9YL3VrbHpNR01pbjJMTFVsTFo1bHl1RWciLCJtYWMiOiIwNzg5Y2EzNWQxNTkyMWFlOGQ5Y2U3NjVlODI4YTY5OGYyZWU0N2YwZTFkMGNhMWUzY2I3YTZkY2M5ZTdhNGQ5IiwidGFnIjoiIn0%3D; expires=Sat, 21 Jun 2025 21:45:53 GMT; Max-Age=7200; path=\\/; secure; httponly; samesite=lax\"},\"response_status\":200,\"response\":{\"view\":\"\\/home\\/studiosdb\\/studiosunisdb\\/resources\\/views\\/welcome.blade.php\",\"data\":[]},\"duration\":13,\"memory\":4,\"hostname\":\"lalpha\"}','2025-06-21 15:45:53'),(220,'9f35d332-8e74-4cd3-82b7-a44455d14559','9f35d332-8eae-491e-ae51-3923fcbd397a',NULL,1,'request','{\"ip_address\":\"93.123.109.7\",\"uri\":\"\\/.git\\/config\",\"method\":\"GET\",\"controller_action\":null,\"middleware\":[],\"headers\":{\"connection\":\"close\",\"accept-encoding\":\"gzip\",\"accept-charset\":\"utf-8\",\"user-agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/75.0.3770.142 Safari\\/537.36\",\"host\":\"4lb.ca\"},\"payload\":[],\"session\":[],\"response_headers\":{\"cache-control\":\"no-cache, private\",\"date\":\"Sat, 21 Jun 2025 19:47:33 GMT\",\"content-type\":\"text\\/html; charset=UTF-8\"},\"response_status\":404,\"response\":\"HTML Response\",\"duration\":13,\"memory\":4,\"hostname\":\"lalpha\"}','2025-06-21 15:47:33');
/*!40000 ALTER TABLE `telescope_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_entries_tags`
--

DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries_tags`
--

LOCK TABLES `telescope_entries_tags` WRITE;
/*!40000 ALTER TABLE `telescope_entries_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_monitoring`
--

DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_monitoring`
--

LOCK TABLES `telescope_monitoring` WRITE;
/*!40000 ALTER TABLE `telescope_monitoring` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_monitoring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ecole_id` bigint unsigned DEFAULT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `sexe` enum('M','F','Autre') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `ville` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_urgence_nom` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_urgence_telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `date_inscription` date NOT NULL DEFAULT '2025-06-21',
  `notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_ecole_id_active_index` (`ecole_id`,`active`),
  KEY `users_date_inscription_index` (`date_inscription`),
  CONSTRAINT `users_ecole_id_foreign` FOREIGN KEY (`ecole_id`) REFERENCES `ecoles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-21 16:25:44

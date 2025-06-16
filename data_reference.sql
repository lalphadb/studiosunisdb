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
-- Table structure for table `ecoles`
--

DROP TABLE IF EXISTS `ecoles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ecoles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Quebec',
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `directeur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacite_max` int DEFAULT '100',
  `statut` enum('actif','inactif') COLLATE utf8mb4_unicode_ci DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ecoles`
--

LOCK TABLES `ecoles` WRITE;
/*!40000 ALTER TABLE `ecoles` DISABLE KEYS */;
INSERT INTO `ecoles` VALUES (1,'Studios Unis Québec','123 Grande Allée','Québec','Quebec',NULL,'418-555-0001','quebec@studiosunisdb.com',NULL,'Jean Tremblay',150,'actif','2025-06-11 18:24:37','2025-06-11 18:24:37'),(2,'Studios Unis Montréal','456 Rue Sainte-Catherine','Montréal','Quebec',NULL,'514-555-0002','montreal@studiosunisdb.com',NULL,'Marie Dubois',200,'actif','2025-06-11 18:24:37','2025-06-11 18:24:37'),(3,'Studios Unis Laval','789 Boul. des Laurentides','Laval','Quebec',NULL,'450-555-0003','laval@studiosunisdb.com',NULL,'Pierre Gagnon',120,'actif','2025-06-11 18:24:37','2025-06-11 18:24:37'),(4,'Studios Unis St-Émile','123 Rue Principale St-Émile','St-Émile','Quebec',NULL,'418-555-0010','stemile@studiosunisdb.com',NULL,NULL,100,'actif','2025-06-12 00:05:28','2025-06-12 00:05:28'),(5,'Studios Unis Montréal Centre','123 Rue Principale','Montréal','QC','G1A 1A1','418-555-0100','studios.unis.montréal.centre@studiosunisqc.com',NULL,'Pierre Dubois',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(6,'Studios Unis Sherbrooke','123 Rue Principale','Sherbrooke','QC','G1A 1A1','418-555-0100','studios.unis.sherbrooke@studiosunisqc.com',NULL,'Claude Bélanger',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(7,'Studios Unis Gatineau','123 Rue Principale','Gatineau','QC','G1A 1A1','418-555-0100','studios.unis.gatineau@studiosunisqc.com',NULL,'André Morin',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(8,'Studios Unis Trois-Rivières','123 Rue Principale','Trois-Rivières','QC','G1A 1A1','418-555-0100','studios.unis.trois-rivières@studiosunisqc.com',NULL,'Robert Lafleur',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(9,'Studios Unis Chicoutimi','123 Rue Principale','Saguenay','QC','G1A 1A1','418-555-0100','studios.unis.chicoutimi@studiosunisqc.com',NULL,'Véronique Simard',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(10,'Studios Unis Rimouski','123 Rue Principale','Rimouski','QC','G1A 1A1','418-555-0100','studios.unis.rimouski@studiosunisqc.com',NULL,'Martine Bérubé',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(11,'Studios Unis Hull','123 Rue Principale','Gatineau','QC','G1A 1A1','418-555-0100','studios.unis.hull@studiosunisqc.com',NULL,'Nathalie Côté',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(12,'Studios Unis Lévis','123 Rue Principale','Lévis','QC','G1A 1A1','418-555-0100','studios.unis.lévis@studiosunisqc.com',NULL,'Isabelle Gagnon',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(13,'Studios Unis Longueuil','123 Rue Principale','Longueuil','QC','G1A 1A1','418-555-0100','studios.unis.longueuil@studiosunisqc.com',NULL,'Sophie Bouchard',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(14,'Studios Unis Sainte-Foy','123 Rue Principale','Québec','QC','G1A 1A1','418-555-0100','studios.unis.sainte-foy@studiosunisqc.com',NULL,'Martin Roy',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(15,'Studios Unis Brossard','123 Rue Principale','Brossard','QC','G1A 1A1','418-555-0100','studios.unis.brossard@studiosunisqc.com',NULL,'Michel Leblanc',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(16,'Studios Unis Magog','123 Rue Principale','Magog','QC','G1A 1A1','418-555-0100','studios.unis.magog@studiosunisqc.com',NULL,'Sylvie Paradis',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(17,'Studios Unis Granby','123 Rue Principale','Granby','QC','G1A 1A1','418-555-0100','studios.unis.granby@studiosunisqc.com',NULL,'Daniel Poulin',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(18,'Studios Unis Shawinigan','123 Rue Principale','Shawinigan','QC','G1A 1A1','418-555-0100','studios.unis.shawinigan@studiosunisqc.com',NULL,'Lucie Pellerin',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(19,'Studios Unis Jonquière','123 Rue Principale','Saguenay','QC','G1A 1A1','418-555-0100','studios.unis.jonquière@studiosunisqc.com',NULL,'Éric Bouchard',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(20,'Studios Unis Rouyn-Noranda','123 Rue Principale','Rouyn-Noranda','QC','G1A 1A1','418-555-0100','studios.unis.rouyn-noranda@studiosunisqc.com',NULL,'Sylvain Trottier',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(21,'Studios Unis Val-d\'Or','123 Rue Principale','Val-d\'Or','QC','G1A 1A1','418-555-0100','studios.unis.val-dor@studiosunisqc.com',NULL,'Stéphane Lacroix',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49'),(22,'Studios Unis Sept-Îles','123 Rue Principale','Sept-Îles','QC','G1A 1A1','418-555-0100','studios.unis.sept-Îles@studiosunisqc.com',NULL,'Julie Tremblay',75,'actif','2025-06-14 00:25:49','2025-06-14 00:25:49');
/*!40000 ALTER TABLE `ecoles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ceintures`
--

DROP TABLE IF EXISTS `ceintures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ceintures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `niveau` int NOT NULL,
  `ordre_affichage` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `pre_requis` text COLLATE utf8mb4_unicode_ci,
  `duree_minimum_mois` int DEFAULT '3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ceintures_niveau_unique` (`niveau`),
  UNIQUE KEY `ceintures_ordre_affichage_unique` (`ordre_affichage`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ceintures`
--

LOCK TABLES `ceintures` WRITE;
/*!40000 ALTER TABLE `ceintures` DISABLE KEYS */;
INSERT INTO `ceintures` VALUES (8,'Ceinture Blanche','blanc',1,1,'Ceinture de débutant',NULL,0,NULL,NULL),(9,'Ceinture Jaune','jaune',2,2,'Première ceinture colorée',NULL,3,NULL,NULL),(10,'Ceinture Orange','orange',3,3,'Progression vers les ceintures intermédiaires',NULL,3,NULL,NULL),(11,'Ceinture Violet','violet',4,4,'Niveau intermédiaire',NULL,4,NULL,NULL),(12,'Ceinture Bleue','bleu',5,5,'Ceinture bleue standard',NULL,4,NULL,NULL),(13,'Ceinture Bleue I','bleu',6,6,'Ceinture bleue avec barrette',NULL,4,NULL,NULL),(14,'Ceinture Verte','vert',7,7,'Ceinture verte standard',NULL,6,NULL,NULL),(15,'Ceinture Verte I','vert',8,8,'Ceinture verte avec barrette',NULL,6,NULL,NULL),(16,'Ceinture Brune I','marron',9,9,'Première ceinture brune',NULL,8,NULL,NULL),(17,'Ceinture Brune II','marron',10,10,'Deuxième ceinture brune',NULL,8,NULL,NULL),(18,'Ceinture Brune III','marron',11,11,'Troisième ceinture brune',NULL,8,NULL,NULL),(19,'Ceinture Noire (1er Dan) — Shodan','noir',12,12,'Premier Dan - Shodan',NULL,12,NULL,NULL),(20,'Ceinture Noire (2e Dan) — Nidan','noir',13,13,'Deuxième Dan - Nidan',NULL,24,NULL,NULL),(21,'Ceinture Noire (3e Dan) — Sandan','noir',14,14,'Troisième Dan - Sandan',NULL,36,NULL,NULL),(22,'Ceinture Noire (4e Dan) — Yondan','noir',15,15,'Quatrième Dan - Yondan',NULL,48,NULL,NULL),(23,'Ceinture Noire (5e Dan) — Godan','noir',16,16,'Cinquième Dan - Godan',NULL,60,NULL,NULL),(24,'Ceinture Noire (6e Dan) — Rokudan','noir',17,17,'Sixième Dan - Rokudan',NULL,72,NULL,NULL),(25,'Ceinture Noire (7e Dan) — Nanadan','noir',18,18,'Septième Dan - Nanadan',NULL,84,NULL,NULL),(26,'Ceinture Noire (8e Dan) — Hachidan','noir',19,19,'Huitième Dan - Hachidan',NULL,96,NULL,NULL),(27,'Ceinture Noire (9e Dan) — Kudan','noir',20,20,'Neuvième Dan - Kudan',NULL,108,NULL,NULL),(28,'Ceinture Noire (10e Dan) — Jūdan','noir',21,21,'Dixième Dan - Jūdan - Grade ultime',NULL,120,NULL,NULL);
/*!40000 ALTER TABLE `ceintures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'superadmin','web','2025-06-11 18:27:27','2025-06-11 18:27:27'),(2,'admin','web','2025-06-11 18:27:27','2025-06-11 18:27:27'),(3,'instructeur','web','2025-06-11 18:27:27','2025-06-11 18:27:27'),(4,'membre','web','2025-06-11 18:27:27','2025-06-11 18:27:27');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'view_dashboard','web','2025-06-11 18:27:33','2025-06-11 18:27:33'),(2,'manage_ecoles','web','2025-06-11 18:27:33','2025-06-11 18:27:33'),(3,'manage_membres','web','2025-06-11 18:27:33','2025-06-11 18:27:33'),(4,'manage_cours','web','2025-06-11 18:27:33','2025-06-11 18:27:33'),(5,'manage_presences','web','2025-06-11 18:27:33','2025-06-11 18:27:33'),(6,'manage_ceintures','web','2025-06-11 18:27:33','2025-06-11 18:27:33'),(7,'view_reports','web','2025-06-11 18:27:33','2025-06-11 18:27:33'),(8,'manage_users','web','2025-06-11 18:27:33','2025-06-11 18:27:33'),(9,'manage-all','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(10,'view-dashboard','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(11,'access-admin','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(12,'manage-ecoles','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(13,'create-ecole','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(14,'edit-ecole','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(15,'delete-ecole','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(16,'view-ecoles','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(17,'manage-membres','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(18,'create-membre','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(19,'edit-membre','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(20,'delete-membre','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(21,'view-membres','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(22,'approve-membre','web','2025-06-14 18:00:51','2025-06-14 18:00:51'),(23,'suspend-membre','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(24,'export-membres','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(25,'manage-cours','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(26,'create-cours','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(27,'edit-cours','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(28,'delete-cours','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(29,'view-cours','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(30,'assign-instructeur','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(31,'manage-horaires','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(32,'manage-presences','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(33,'take-presences','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(34,'edit-presences','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(35,'view-presences','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(36,'export-presences','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(37,'view-statistics','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(38,'manage-ceintures','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(39,'evaluate-ceintures','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(40,'assign-ceintures','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(41,'view-progressions','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(42,'manage-finances','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(43,'view-paiements','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(44,'create-paiement','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(45,'generate-factures','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(46,'view-reports','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(47,'generate-reports','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(48,'view-analytics','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(49,'export-data','web','2025-06-14 18:00:52','2025-06-14 18:00:52'),(50,'presence.view','web','2025-06-14 18:01:50','2025-06-14 18:01:50'),(51,'presence.create','web','2025-06-14 18:01:50','2025-06-14 18:01:50'),(52,'presence.edit','web','2025-06-14 18:01:50','2025-06-14 18:01:50'),(53,'presence.delete','web','2025-06-14 18:01:50','2025-06-14 18:01:50'),(54,'presence.export','web','2025-06-14 18:01:50','2025-06-14 18:01:50');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-16 14:56:53

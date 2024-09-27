-- MySQL dump 10.13  Distrib 8.0.28, for Linux (x86_64)
--
-- Host: localhost    Database: atc
-- ------------------------------------------------------
-- Server version       8.0.28-0ubuntu0.20.04.3

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
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `control_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_middlename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_suffix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_age` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_category` enum('elderly','pwd','pregnant_woman','woman','children') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_pob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_dob` date DEFAULT NULL,
  `arrested_sex` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_status` enum('single','married','widowed','separated') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_spouse_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_spouse_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_height` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_eyes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_hair` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_complexion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_tribe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_educ_attainment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_school_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_school_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_marks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_location_marks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrested_defect` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `who` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `when` datetime DEFAULT NULL,
  `where` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `what` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `how` text COLLATE utf8mb4_unicode_ci,
  `why` text COLLATE utf8mb4_unicode_ci,
  `other_details` text COLLATE utf8mb4_unicode_ci,
  `is_informed_of_right` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mental_condition` text COLLATE utf8mb4_unicode_ci,
  `physical_condition` text COLLATE utf8mb4_unicode_ci,
  `extension_reason` text COLLATE utf8mb4_unicode_ci,
  `reason_narration` text COLLATE utf8mb4_unicode_ci,
  `approved_remarks` text COLLATE utf8mb4_unicode_ci,
  `approved_date` datetime DEFAULT NULL,
  `disapproved_remarks` text COLLATE utf8mb4_unicode_ci,
  `disapproved_date` datetime DEFAULT NULL,
  `endorsed_remarks` text COLLATE utf8mb4_unicode_ci,
  `endorsed_date` datetime DEFAULT NULL,
  `final_resolution` datetime DEFAULT NULL,
  `is_extension` tinyint(1) NOT NULL DEFAULT '0',
  `expiration_notified` tinyint(1) NOT NULL DEFAULT '0',
  `detention_expiration` datetime DEFAULT NULL,
  `date_submitted` datetime DEFAULT NULL,
  `posted_date` datetime DEFAULT NULL,
  `status` enum('draft','available','disapproved','endorsing','voting','approved','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `audits`
--

DROP TABLE IF EXISTS `audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint unsigned NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci,
  `new_values` text COLLATE utf8mb4_unicode_ci,
  `url` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1023) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audits`
--

LOCK TABLES `audits` WRITE;
/*!40000 ALTER TABLE `audits` DISABLE KEYS */;
/*!40000 ALTER TABLE `audits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brief_narrative_histories`
--

DROP TABLE IF EXISTS `brief_narrative_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brief_narrative_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `narrative` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brief_narrative_histories`
--

LOCK TABLES `brief_narrative_histories` WRITE;
/*!40000 ALTER TABLE `brief_narrative_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `brief_narrative_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `application_id` int unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (56,'2014_10_12_000000_create_users_table',1),(57,'2014_10_12_100000_create_password_resets_table',1),(58,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(59,'2019_08_19_000000_create_failed_jobs_table',1),(60,'2019_12_14_000001_create_personal_access_tokens_table',1),(61,'2021_08_25_171142_create_applications_table',1),(62,'2021_08_25_171608_create_permission_tables',1),(63,'2021_08_29_034225_create_comments_table',1),(64,'2021_09_12_152731_create_votes_table',1),(65,'2021_10_19_203956_create_brief_narrative_histories_table',1),(66,'2022_03_03_094903_create_sessions_table',1),(67,'2022_03_03_212106_create_audits_table',1),(68,'2022_04_09_032323_create_media_table',1),(69,'2022_04_20_221701_create_jobs_table',1);
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
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `model_has_permissions` VALUES (2,'App\\Models\\User',16),(3,'App\\Models\\User',16),(4,'App\\Models\\User',16),(7,'App\\Models\\User',16),(2,'App\\Models\\User',20),(3,'App\\Models\\User',20),(4,'App\\Models\\User',20),(7,'App\\Models\\User',20),(8,'App\\Models\\User',23),(9,'App\\Models\\User',23),(10,'App\\Models\\User',23),(8,'App\\Models\\User',24),(9,'App\\Models\\User',24),(10,'App\\Models\\User',24),(8,'App\\Models\\User',25),(9,'App\\Models\\User',25),(10,'App\\Models\\User',25),(8,'App\\Models\\User',26),(9,'App\\Models\\User',26),(10,'App\\Models\\User',26),(2,'App\\Models\\User',27),(3,'App\\Models\\User',27),(4,'App\\Models\\User',27),(7,'App\\Models\\User',27),(2,'App\\Models\\User',28),(3,'App\\Models\\User',28),(4,'App\\Models\\User',28),(7,'App\\Models\\User',28),(2,'App\\Models\\User',29),(3,'App\\Models\\User',29),(4,'App\\Models\\User',29),(7,'App\\Models\\User',29),(2,'App\\Models\\User',30),(3,'App\\Models\\User',30),(4,'App\\Models\\User',30),(7,'App\\Models\\User',30),(2,'App\\Models\\User',31),(3,'App\\Models\\User',31),(4,'App\\Models\\User',31),(7,'App\\Models\\User',31),(2,'App\\Models\\User',32),(3,'App\\Models\\User',32),(4,'App\\Models\\User',32),(7,'App\\Models\\User',32),(2,'App\\Models\\User',33),(3,'App\\Models\\User',33),(4,'App\\Models\\User',33),(7,'App\\Models\\User',33),(2,'App\\Models\\User',34),(3,'App\\Models\\User',34),(4,'App\\Models\\User',34),(7,'App\\Models\\User',34),(2,'App\\Models\\User',35),(3,'App\\Models\\User',35),(4,'App\\Models\\User',35),(7,'App\\Models\\User',35),(2,'App\\Models\\User',36),(3,'App\\Models\\User',36),(4,'App\\Models\\User',36),(7,'App\\Models\\User',36),(2,'App\\Models\\User',37),(3,'App\\Models\\User',37),(4,'App\\Models\\User',37),(7,'App\\Models\\User',37),(2,'App\\Models\\User',38),(3,'App\\Models\\User',38),(4,'App\\Models\\User',38),(7,'App\\Models\\User',38),(2,'App\\Models\\User',39),(3,'App\\Models\\User',39),(4,'App\\Models\\User',39),(7,'App\\Models\\User',39),(2,'App\\Models\\User',40),(3,'App\\Models\\User',40),(4,'App\\Models\\User',40),(7,'App\\Models\\User',40),(2,'App\\Models\\User',41),(3,'App\\Models\\User',41),(4,'App\\Models\\User',41),(7,'App\\Models\\User',41),(2,'App\\Models\\User',42),(3,'App\\Models\\User',42),(4,'App\\Models\\User',42),(7,'App\\Models\\User',42),(2,'App\\Models\\User',43),(3,'App\\Models\\User',43),(4,'App\\Models\\User',43),(7,'App\\Models\\User',43),(2,'App\\Models\\User',44),(3,'App\\Models\\User',44),(4,'App\\Models\\User',44),(7,'App\\Models\\User',44),(2,'App\\Models\\User',45),(3,'App\\Models\\User',45),(4,'App\\Models\\User',45),(7,'App\\Models\\User',45),(2,'App\\Models\\User',46),(3,'App\\Models\\User',46),(4,'App\\Models\\User',46),(7,'App\\Models\\User',46),(2,'App\\Models\\User',47),(3,'App\\Models\\User',47),(4,'App\\Models\\User',47),(7,'App\\Models\\User',47),(2,'App\\Models\\User',48),(3,'App\\Models\\User',48),(4,'App\\Models\\User',48),(7,'App\\Models\\User',48),(2,'App\\Models\\User',49),(3,'App\\Models\\User',49),(4,'App\\Models\\User',49),(7,'App\\Models\\User',49),(2,'App\\Models\\User',50),(3,'App\\Models\\User',50),(4,'App\\Models\\User',50),(7,'App\\Models\\User',50),(2,'App\\Models\\User',51),(3,'App\\Models\\User',51),(4,'App\\Models\\User',51),(7,'App\\Models\\User',51),(2,'App\\Models\\User',52),(3,'App\\Models\\User',52),(4,'App\\Models\\User',52),(7,'App\\Models\\User',52),(2,'App\\Models\\User',53),(3,'App\\Models\\User',53),(4,'App\\Models\\User',53),(7,'App\\Models\\User',53),(2,'App\\Models\\User',54),(3,'App\\Models\\User',54),(4,'App\\Models\\User',54),(7,'App\\Models\\User',54),(2,'App\\Models\\User',55),(3,'App\\Models\\User',55),(4,'App\\Models\\User',55),(7,'App\\Models\\User',55),(2,'App\\Models\\User',56),(3,'App\\Models\\User',56),(4,'App\\Models\\User',56),(7,'App\\Models\\User',56),(2,'App\\Models\\User',57),(3,'App\\Models\\User',57),(4,'App\\Models\\User',57),(7,'App\\Models\\User',57),(2,'App\\Models\\User',58),(3,'App\\Models\\User',58),(4,'App\\Models\\User',58),(7,'App\\Models\\User',58),(2,'App\\Models\\User',59),(3,'App\\Models\\User',59),(4,'App\\Models\\User',59),(7,'App\\Models\\User',59),(2,'App\\Models\\User',60),(3,'App\\Models\\User',60),(4,'App\\Models\\User',60),(7,'App\\Models\\User',60),(2,'App\\Models\\User',61),(3,'App\\Models\\User',61),(4,'App\\Models\\User',61),(7,'App\\Models\\User',61),(2,'App\\Models\\User',62),(3,'App\\Models\\User',62),(4,'App\\Models\\User',62),(7,'App\\Models\\User',62),(2,'App\\Models\\User',63),(3,'App\\Models\\User',63),(4,'App\\Models\\User',63),(7,'App\\Models\\User',63),(2,'App\\Models\\User',64),(3,'App\\Models\\User',64),(4,'App\\Models\\User',64),(7,'App\\Models\\User',64),(2,'App\\Models\\User',65),(3,'App\\Models\\User',65),(4,'App\\Models\\User',65),(7,'App\\Models\\User',65),(2,'App\\Models\\User',66),(3,'App\\Models\\User',66),(4,'App\\Models\\User',66),(7,'App\\Models\\User',66),(2,'App\\Models\\User',67),(3,'App\\Models\\User',67),(4,'App\\Models\\User',67),(7,'App\\Models\\User',67),(2,'App\\Models\\User',68),(3,'App\\Models\\User',68),(4,'App\\Models\\User',68),(7,'App\\Models\\User',68),(2,'App\\Models\\User',69),(3,'App\\Models\\User',69),(4,'App\\Models\\User',69),(7,'App\\Models\\User',69),(5,'App\\Models\\User',70),(6,'App\\Models\\User',70),(9,'App\\Models\\User',70),(12,'App\\Models\\User',70),(13,'App\\Models\\User',70),(8,'App\\Models\\User',71),(9,'App\\Models\\User',71),(11,'App\\Models\\User',71),(12,'App\\Models\\User',71),(8,'App\\Models\\User',72),(9,'App\\Models\\User',72),(11,'App\\Models\\User',72),(12,'App\\Models\\User',72),(8,'App\\Models\\User',73),(9,'App\\Models\\User',73),(11,'App\\Models\\User',73),(12,'App\\Models\\User',73),(8,'App\\Models\\User',74),(9,'App\\Models\\User',74),(11,'App\\Models\\User',74),(12,'App\\Models\\User',74),(8,'App\\Models\\User',75),(9,'App\\Models\\User',75),(11,'App\\Models\\User',75),(12,'App\\Models\\User',75),(8,'App\\Models\\User',76),(9,'App\\Models\\User',76),(11,'App\\Models\\User',76),(12,'App\\Models\\User',76),(8,'App\\Models\\User',77),(9,'App\\Models\\User',77),(11,'App\\Models\\User',77),(12,'App\\Models\\User',77),(8,'App\\Models\\User',78),(9,'App\\Models\\User',78),(11,'App\\Models\\User',78),(12,'App\\Models\\User',78),(8,'App\\Models\\User',79),(9,'App\\Models\\User',79),(11,'App\\Models\\User',79),(12,'App\\Models\\User',79),(2,'App\\Models\\User',80),(3,'App\\Models\\User',80),(4,'App\\Models\\User',80),(7,'App\\Models\\User',80),(2,'App\\Models\\User',81),(3,'App\\Models\\User',81),(4,'App\\Models\\User',81),(7,'App\\Models\\User',81),(8,'App\\Models\\User',82),(9,'App\\Models\\User',82),(10,'App\\Models\\User',82);
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
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'manage user','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(2,'send application','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(3,'restrict view other application','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(4,'update application','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(5,'approve application','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(6,'disapprove application','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(7,'edit narrative','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(8,'comment application','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(9,'view discussion','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(10,'endorse application','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(11,'vote application','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(12,'view vote','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(13,'provide resolution','web','2022-04-28 13:20:04','2022-04-28 13:20:04');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
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
INSERT INTO `role_has_permissions` VALUES (1,1),(2,2),(3,2),(4,2),(4,3),(5,3),(6,3),(7,3),(9,3),(12,3),(13,3),(8,4),(9,4),(11,5),(12,5);
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrator','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(2,'Applicant','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(3,'ATC Secretariat','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(4,'Review Committee','web','2022-04-28 13:20:04','2022-04-28 13:20:04'),(5,'Voting Committee','web','2022-04-28 13:20:04','2022-04-28 13:20:04');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `font_size` enum('small','medium','large','huge') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','admin@admin.com','2022-04-28 13:20:04','$2y$10$OhSJ2NsStXvk.1w2gMQSjOy0symqWXImibwGvidOKOfv2vBTBJt0O',NULL,NULL,'+639101111111',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(2,'Mr. Joe Louie Addu','joelouieaddu@yahoo.com','2022-04-28 13:20:05','$2y$10$j5iXUtJ74c8lp.UegZDf0u8fl350.EnsggljIaurlKHPHTJKNbfxu',NULL,NULL,'+639196993481',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(3,'PCPL Roxel Taguba','roxeltaguba15@gmail.com','2022-04-28 13:20:05','$2y$10$C9Sl0lS8..yUHe6XUP3blu9Y5/gqpl2QkHS8jO9gGzJC3S3x834wq',NULL,NULL,'+639273904951',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(4,'Mr. David Macabio','davemacabio_6@yahoo.com','2022-04-28 13:20:05','$2y$10$b390XxymtxyeGJ86Ko0w4.OJeUjeBFDoSRgeYwddsI/NECHfxHspi',NULL,NULL,'+639557396545',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(5,'OIC-ARD Jose Ferdinando F Toledo','marquindavid@yahoo.com','2022-04-28 13:20:05','$2y$10$te7SJLqUi59prV/43CuoQ.m.Zcgrmqyd0Ys5GPqZm4d7Opr92qtXu',NULL,NULL,'+639218947114',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(6,'PLTC Victor Basil Morales','victorbasilb.morales@rocketmail.com','2022-04-28 13:20:05','$2y$10$DoHpm9zy7TyS5BLaLRNcjuLl46ZgERdzP36hLWJTkF6dGT6Cs.zPu',NULL,NULL,'+639171555904',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(7,'ARD Roxellen Arzaga','region4nica@gmail.com','2022-04-28 13:20:05','$2y$10$oLdyJhEPiXn2IBlRT9hR6.SqBToNGVhrVJB.8IfbkOHbpnmSHAz3W',NULL,NULL,'+639175792586',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(8,'PMAJ Ma. Kristhyl A Hernandez','intelcidg4a@gmail.com','2022-04-28 13:20:05','$2y$10$2Qk94VHIC5hMt4SKIEtoJ.s819ATM5GRayes6cAm2dr3GI8BVXBUi',NULL,NULL,'+639262508312',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(9,'RD Ariel Perlado','galahadm357@yahoo.com','2022-04-28 13:20:05','$2y$10$e7Ah9PEV1mQPV2MwrC6NNusAnaIZSauwraeNkF8k462xSxppA.qOS',NULL,NULL,'+639954870188',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(10,'Sean Ramos ARD CI','ard.rcid6@gmail.com','2022-04-28 13:20:05','$2y$10$sv0omyjqnxPghZZNxJrYC.jMXtcdKZzBilr07Fvsw5s5Dgndz0Si2',NULL,NULL,'+639668625203',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(11,'PMAJ JESS P BAYLON - Chief Intel CIDG RFU 6','jessbaylon23881@gmail.com','2022-04-28 13:20:05','$2y$10$3FoZ3SNjXcZKvdf.Uu5ET.aZ0nl3gI/xycHaqQtJB4Dq4IsB2A.ti',NULL,NULL,'+639173773650',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(12,'Alvin T. Devaras','atd_0568@yahoo.com','2022-04-28 13:20:05','$2y$10$d1ofvscXpKXBXD0bwOpPsOFc4JHYtOsu5ttNTgimvil5vSi0L22vW',NULL,NULL,'+639173216300',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:05','2022-04-28 13:20:05'),(13,'PMAJ Duane Francis J. Ducducan','duanefrancis08@gmail.com','2022-04-28 13:20:05','$2y$10$BJ./UXenD7epHa/yfith9eJ0G11frYDhPWVoaIWEyUCmn9HbI.mP6',NULL,NULL,'+639173081904',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 13:20:06'),(14,'D’Fernando Echeverria','dfernandoecheverria@yahoo.com','2022-04-28 13:20:06','$2y$10$DojZPHfjKJce/5EwLgpVVeKzAASnHKHnmidxjYCDI/3/mLyApLdTW',NULL,NULL,'+639176348646',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 13:20:06'),(15,'CO MichaelBatomalaque','miconefour@gmail.com','2022-04-28 13:20:06','$2y$10$nzDgRUrMt4elwxZnGvwmYOnCxU1yvCLrqhJvA9qcPFu8rsENic5G2',NULL,NULL,'+639157093539',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 13:20:06'),(16,'PCpl ALEX GREGGOR O AVENIDO','347avenido@gmail.com','2022-04-28 13:20:06','$2y$10$5mcID/mFAyq2hX9EGK8lFuE3vOybSfwrv/EbkW7xi9kf7YcAmaRuy',NULL,NULL,'+639380210073',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 16:15:17'),(17,'Ms. Charmilyn D Cacdac','lucasnorth180194@yahoo.com.ph','2022-04-28 13:20:06','$2y$10$usvX88GblTnhRj4ZplDuLunrffEW4U6RF0JCOm1LA8UuZQy5ZVV0W',NULL,NULL,'+639777085993',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 13:20:06'),(18,'Irene Lauzon','It.lauzon66@gmail.com','2022-04-28 13:20:06','$2y$10$vb3WMnb8ihXUx1xYaudfkudFsIMMWxCX7VSnD.rYzkLdud0nXO9O.',NULL,NULL,'+639162237427',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 13:20:06'),(19,'Joe Eric Tuclaud','jetuclaud@gmail.com','2022-04-28 13:20:06','$2y$10$ivoR2gBBpI53tupjk/zBYu2bmdiX/cakzNRHEb3jgyAtL0Qd..NyO',NULL,NULL,'+639776181262',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 13:20:06'),(20,'PLTCOL RONALD ALLAN A TOLOSA','caragacidu2006@yahoo.com','2022-04-28 13:20:06','$2y$10$4WIBA6O9dizz1b3MXDTMcuU8JLaxFt08pi1quUTGHlu.GJUjsoD9O',NULL,NULL,'+639364764599',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 16:16:17'),(21,'Mr Kerry Manalo','kerrymanalo@gmail.com','2022-04-28 13:20:06','$2y$10$OyGZxMwedxt3rIn46pLhK.MBMRa9aRmD6cP48Rr7XtOCp3kjQXjHK',NULL,NULL,'+639760227120',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 13:20:06'),(22,'Sir Boyet','elvis_giordano2012@yahoo.com.ph','2022-04-28 13:20:06','$2y$10$wgOh/Il9XC1rpecHMFujiO7lrgZOSZmB3p9/1P9MaA5XwxgasdP8G',NULL,NULL,'+639175509042',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 13:20:06'),(23,'Review Committee 3','makifalgui@gmail.com','2022-04-28 13:20:06','$2y$10$ZJr4dqLOBLxI8/jewgQO5.Lgs4nhKbBDFgqKJouh.Ln60bK.jorAW',NULL,NULL,'+639177260911',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 14:13:53'),(24,'Review Committee 4','maearcillas.afp@gmail.com','2022-04-28 13:20:06','$2y$10$frAmc7.4sSchbOQslHsiJOIsPIbxbUey0LUDIUAggU4NCS7GOJSfC',NULL,NULL,'+639178779164',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 14:14:18'),(25,'Review Committee 1','alo@nica.gov.ph','2022-04-28 13:20:06','$2y$10$bR7tkmZ00SNXiU4l2BOjIuGH/KK9kYsV6/Z4LRLIIb1ss4IdYADZy',NULL,NULL,'+639154790544',NULL,NULL,NULL,'medium',1,'2022-04-28 13:20:06','2022-04-28 14:11:12'),(26,'Review Committee 2','j.am_reyes@yahoo.com',NULL,'$2y$10$..qIEaT2VSmsmgzSW5yYAuphIKfux9JiDGDQmVr4LOrzHh./CCBf.',NULL,NULL,'12345678910',NULL,NULL,NULL,'medium',1,'2022-04-28 14:13:09','2022-04-28 14:13:09'),(27,'PLTCOL ARMELINA S MANALO','armiemanalo.pnp@gmail.com',NULL,'$2y$10$Ycc8Lb5ladYGTam31AIWQuSqaW6YiQrO5cuH5ySUDjacIaDpj2vQS',NULL,NULL,'+639272891617',NULL,NULL,NULL,'medium',1,'2022-04-28 14:22:19','2022-04-28 14:22:19'),(28,'PCpl JENICK C DIZON','jenick.dizon@pnp.gov.ph',NULL,'$2y$10$ESVG6k/NlazKL0HBW0G/AOOvQjlFKLNve.dT56hjWThlLL4qy6YWK',NULL,NULL,'+639364654050',NULL,NULL,NULL,'medium',1,'2022-04-28 14:24:15','2022-04-28 14:24:15'),(29,'PCOL RONALD G HIPOLITO','ronald.hipolito@pnp.gov.ph',NULL,'$2y$10$o5EIrn.Y8JRFvgKe0v2j7e808TjWb6L7Y3tmA29xVTZys9uKH1neS',NULL,NULL,'+639177729596',NULL,NULL,NULL,'medium',1,'2022-04-28 14:25:55','2022-04-28 14:25:55'),(30,'PLT MARITES B NISPEROS','mabinispesor@gmail.com',NULL,'$2y$10$xrtUfhw5fCAx/xUbwxD11.WIB14eqrqESRhB4PPgkBO8BjmglKNNO',NULL,NULL,'+639150926552',NULL,NULL,NULL,'medium',1,'2022-04-28 14:28:02','2022-04-28 14:28:02'),(31,'PCMS LAURO L GUILLERMO JR','Lauguillermo2000@yahoo.com',NULL,'$2y$10$/g2.6C9dr/LtjnzY1wGEF.Vw.fLjNDtbrz91l9Zf/HN5BaNaMdGHm',NULL,NULL,'+639772447558',NULL,NULL,NULL,'medium',1,'2022-04-28 14:29:50','2022-04-28 14:29:50'),(32,'PCpl JOE CARLO M GULAN','Gulanjoecarlo1@gmail.com',NULL,'$2y$10$ta.CkSwmczlPECoPbPYqUeizcTx2ped35etZ4K5r8A4S1CLIOGJ1S',NULL,NULL,'+639978942702',NULL,NULL,NULL,'medium',1,'2022-04-28 14:30:52','2022-04-28 14:30:52'),(33,'PLT JESUS R MEIMBAN','jesusmeimban557@gmail.com',NULL,'$2y$10$JQT3N15/7iMdrkjsnqChluqmzfT3HCN2C95pGBgY4CDhSWr2yz/B2',NULL,NULL,'+639129552426',NULL,NULL,NULL,'medium',1,'2022-04-28 14:32:31','2022-04-28 14:32:31'),(34,'PCpl BENEDICT F TUGAS','benedictferrertugas@gmail.com',NULL,'$2y$10$S3pQTcVyzn4wStWHZBB3Eek/ydpVG0GffCxBWjvJFInArZEQ8hOEC',NULL,NULL,'+639456109818',NULL,NULL,NULL,'medium',1,'2022-04-28 14:33:42','2022-04-28 14:33:42'),(35,'PMSg MICHAELT PANGANIBAN','mikeltpanganiban@gmail.com',NULL,'$2y$10$USDaNjBkh.u/TQhGI5nduOsqRy1nkw7CajRfDmWJzi8ZtWmm6g5Ki',NULL,NULL,'+639458179201',NULL,NULL,NULL,'medium',1,'2022-04-28 14:35:09','2022-04-28 14:35:09'),(36,'Pat JOHN R EVANGELISTA','Evangelistaj720@yahoo.com',NULL,'$2y$10$zS73ZtdLZ2rUlnBPD4C9cO0sFAGbMCRFLte4SuOIyahsP8hrsFTIq',NULL,NULL,'+639662691443',NULL,NULL,NULL,'medium',1,'2022-04-28 14:36:07','2022-04-28 14:36:07'),(37,'PCMS ELMER P LEYNES','Elmerleynes95@gmail.com',NULL,'$2y$10$2wnGCiM/LnUv0lMA/tIXQeevy.t7.JRQJv47qKkS/FEQpieeYHpny',NULL,NULL,'+639566697588',NULL,NULL,NULL,'medium',1,'2022-04-28 14:38:04','2022-04-28 14:38:04'),(38,'PCpl BRYAN JHON P MEREZ','Shinigamerez19@gmail.com',NULL,'$2y$10$FDCTTxcZwrcWtF0qm64AE.Mi6FeoiIGo2O1HFFyrmgLOL0yyKhJKy',NULL,NULL,'+639457427309',NULL,NULL,NULL,'medium',1,'2022-04-28 14:39:52','2022-04-28 14:39:52'),(39,'PSMS CARLO C DAET','caloycoydaet@yahoo.com',NULL,'$2y$10$c.Zx9bTbp4tIvoY5kaseGesDv9Xm8zkwQaNhtd/LjJCJCNHSaquQC',NULL,NULL,'+639494353388',NULL,NULL,NULL,'medium',1,'2022-04-28 14:41:16','2022-04-28 14:41:16'),(40,'PCpl REGINE A BERMUDO','Regine.bermudo@yahoo.com',NULL,'$2y$10$s7EM6Vp2Sv7RXi9TnmECcONSNTmTPFV1QEnb5j83mi8..Bt8ZKqy.',NULL,NULL,'+639754921423',NULL,NULL,NULL,'medium',1,'2022-04-28 14:43:24','2022-04-28 14:43:24'),(41,'PCPT MA GINA P FEROLINO','marygrace6897@yahoo.com',NULL,'$2y$10$KoalPuh.p2QvRzRb/S4YtenNa9YDJXUJD0QVzXiaVr8d0w0ym7MrG',NULL,NULL,'+639384178747',NULL,NULL,NULL,'medium',1,'2022-04-28 14:45:22','2022-04-28 14:45:22'),(42,'PEMS ISIDRO JAY P TEOFILO','Isidrojay.teofilo@pnp.gov.ph',NULL,'$2y$10$iVKP.N55liNVE6cxVxulR.YLGtnUBsgMsybFotHc.1B84JcTAKzGO',NULL,NULL,'+639297075477',NULL,NULL,NULL,'medium',1,'2022-04-28 14:46:33','2022-04-28 14:46:33'),(43,'PLT JOEL H DEIPARINE','joel.deiparine@pnp.gov.ph',NULL,'$2y$10$qoqMHLoPgoR4XH02X/wOhumkjFvQ9S4Z2wVSPZX.jY1jgMREot03W',NULL,NULL,'+639055032729',NULL,NULL,NULL,'medium',1,'2022-04-28 14:48:12','2022-04-28 14:48:12'),(44,'PLT JOSEPHELMER C CORNEA','Josephhelmer.cornea@pnp.gov.ph',NULL,'$2y$10$Mmsl89dR672mtPFB0jQ2Q.QWeTWpBZFnF9XrZIv9XndJh.0YKQwna',NULL,NULL,'+639566426451',NULL,NULL,NULL,'medium',1,'2022-04-28 14:49:24','2022-04-28 14:49:24'),(45,'PEMS NOEL B CORAL','cidgtacloban@gmail.com',NULL,'$2y$10$vYju9vwe1YpQZCBt1UDBiOXDE/pMQlw1QqlYCGp5R3dBfJ53KZPrK',NULL,NULL,'+639955406233',NULL,NULL,NULL,'medium',1,'2022-04-28 14:51:02','2022-04-28 14:51:02'),(46,'PMAJ ALLAN C ALOG','allancarpioalog@gmail.com',NULL,'$2y$10$bu9Cdss78N3OqQ2aE0DfGO96WtLSha7Z2e.t8iAA5HJkWUW.CMtRu',NULL,NULL,'+639167924224',NULL,NULL,NULL,'medium',1,'2022-04-28 14:52:46','2022-04-28 14:52:46'),(47,'Pat ROJEFF-JOSE A MURCIA','Rojeff.murcia@gmail.com',NULL,'$2y$10$.PxY4jH0GvCQc9Ha51dfnOE6QUYFfcYXAm491ypPtk7mmJUWIu1ku',NULL,NULL,'+639366334423',NULL,NULL,NULL,'medium',1,'2022-04-28 14:53:56','2022-04-28 14:53:56'),(48,'PLT ARLENE E LANITON','lanitonarlene@gmail.com',NULL,'$2y$10$Lv/rPZ8EEzKS1YbpzrTeYuQNXwq.9GS9FvL3TaawmmmxSsJIrr7T6',NULL,NULL,'+639361020280',NULL,NULL,NULL,'medium',1,'2022-04-28 15:58:15','2022-04-28 15:58:15'),(49,'PMAJ RANDY RAJAH V RAMOS','randyrajahvalleramos@gmail.com',NULL,'$2y$10$yNXuuNS6UHvg10OcMxmzEuXY6RyHva/DIMcIDHkcaCIcH8DQM1she',NULL,NULL,'+639482825779',NULL,NULL,NULL,'medium',1,'2022-04-28 16:05:21','2022-04-28 16:05:21'),(50,'PSMS ALEXIS C ARATON','alexisarantoncidg@gmail.com',NULL,'$2y$10$ywE/.emj6Hq0ACa/aQsRFe.BVZSGpzWaquHWMuWa5Cre1Nyrl0haG',NULL,NULL,'+639192608247',NULL,NULL,NULL,'medium',1,'2022-04-28 16:06:47','2022-04-28 16:06:47'),(51,'PMAJ EDUARD B JUNGCO','cidgsar12@gmail.com',NULL,'$2y$10$lyG5QMMy1S31rcCODMrkHutKpGnU0fOoQAnXPLhALv5LPVYswvh.y',NULL,NULL,'+639182973182',NULL,NULL,NULL,'medium',1,'2022-04-28 16:08:24','2022-04-28 16:08:24'),(52,'PMAJ JETHRO P MIPAÑA','cidg12northcotabato@gmail.com',NULL,'$2y$10$RqWkkUnbVbtfF.KNPIwmmu6elA4AIwi4BHoiMtMcmmgtc/knKSWTG',NULL,NULL,'+639129834017',NULL,NULL,NULL,'medium',1,'2022-04-28 16:10:04','2022-04-28 16:10:04'),(53,'PMAJ MELENIUM B BANTAS','airmelbantas@gmail.com',NULL,'$2y$10$BPgiw9dfeCfKybBMXxmCw.bxYrmy.CIK5TOTWDgu0CCq7PLIUB.sS',NULL,NULL,'+639959550370',NULL,NULL,NULL,'medium',1,'2022-04-28 16:17:29','2022-04-28 16:17:29'),(54,'Pat QUINCY JOSEPH T BICO','quincybico@gmail.com',NULL,'$2y$10$lrRRYAzwz5OzdvYQ7gQ8POLCIJiHMY0auNTytAFvyihNz7fZlweZW',NULL,NULL,'+639150926552',NULL,NULL,NULL,'medium',1,'2022-04-28 16:18:21','2022-04-28 16:18:21'),(55,'PLTCOL NORMAN M PATNAAN','jnormspatz1331@gmail.com',NULL,'$2y$10$hlXHZnPZuSzpvvRDpwbpLuA.L9Kh7HFsT93veuVP3FI3STzgwIVDW',NULL,NULL,'+639771728292',NULL,NULL,NULL,'medium',1,'2022-04-28 16:19:18','2022-04-28 16:19:18'),(56,'PCpl BARJORIE V BAGUMBARAN','rastamanbands@gmail.com',NULL,'$2y$10$c5sntEPg58DNy.6tL6nE4uNDlqPvMWhZIMYlNAC9wHIH3UrrqTWIW',NULL,NULL,'+63905787643
2',NULL,NULL,NULL,'medium',1,'2022-04-28 16:20:42','2022-04-28 16:20:42'),(57,'Pat JOHN EDWARD M URIAN','johnedwardurian23@gmail.com',NULL,'$2y$10$FATUcl/fmmO7zwoCT881TOTOd.0S/TSizDHcgzqs3VITx1s8TdQ3i',NULL,NULL,'+639382706633',NULL,NULL,NULL,'medium',1,'2022-04-28 16:21:57','2022-04-28 16:21:57'),(58,'Pat JUSTINE ERWIN C CALMA JR','justineerwin3@gmail.com',NULL,'$2y$10$Chn6kmnnQ98pt.PsuLX8JudWO7/ayWx2N20q4S7jE6.VFepo7gUsK',NULL,NULL,'+639264267322',NULL,NULL,NULL,'medium',1,'2022-04-28 16:23:04','2022-04-28 16:23:04'),(59,'PSSg HERMIE B LOMIBAO','cidgaocu@gmail.com',NULL,'$2y$10$UCVuRQlJqwNHUNvl0NJX7eNg0kkTY2CbecTja2YFml2RtFvg117Vq',NULL,NULL,'+639567946486',NULL,NULL,NULL,'medium',1,'2022-04-28 16:24:36','2022-04-28 16:24:36'),(60,'PSSg GLADYS O AGPAD','aocucidg@gmail.com',NULL,'$2y$10$.8Fa/eITi6Vqt9RCMZlpm.c.ypgFoQtdjUbEKj1Un7QWjs73jFLb6',NULL,NULL,'+639669311186',NULL,NULL,NULL,'medium',1,'2022-04-28 16:25:52','2022-04-28 16:25:52'),(61,'PSMS SUNNYBOY B MENDEZ','cidgmciu@gmail.com',NULL,'$2y$10$0L0ezZWAA6QTmgPl0S7Yuu5RwFJezAV5I9FRk5gJF8Ko4rtGSU7Ne',NULL,NULL,'+639052305824',NULL,NULL,NULL,'medium',1,'2022-04-28 16:26:46','2022-04-28 16:26:46'),(62,'PCpl ROSE ANN C DECENA','acr.decena7@gmail.com',NULL,'$2y$10$Ncc3JgFOEWYtP0635KhhCujsxJgiUoQwG5lcV33geuR.WsQUk0GDi',NULL,NULL,'+639276082125',NULL,NULL,NULL,'medium',1,'2022-04-28 16:28:20','2022-04-28 16:28:20'),(63,'Pat JODAR L CAPATI','jodarcapati02@gmail.com',NULL,'$2y$10$5NI8D/K6/2Obccnhp1x.DOGyaFFwpepQgSZy6RjTGlCTMIDUxAZ3S',NULL,NULL,'+639169148113',NULL,NULL,NULL,'medium',1,'2022-04-28 16:29:59','2022-04-28 16:29:59'),(64,'PSSg ROMANO ROSS L SINGSON','atcu.cidg@gmail.com',NULL,'$2y$10$H334xLi2QIa3YCsSGsh2TeVoG2rz3frnMcD5IVVLikLhiQs/aqBpW',NULL,NULL,'+639297154122',NULL,NULL,NULL,'medium',1,'2022-04-28 16:31:03','2022-04-28 16:31:03'),(65,'Pat JENRIX M BALDERAMA','Khiraaa28@gmail.com',NULL,'$2y$10$BziUgz4Yt5dsQGJkYHrpv.TMZrD1sq2kZB61M5qwwJkaV6f5tdOu.',NULL,NULL,'+639687184433',NULL,NULL,NULL,'medium',1,'2022-04-28 16:32:03','2022-04-28 16:32:03'),(66,'PLTCOL CRISLE T CAINONG','warayfeb252019@gmail.com',NULL,'$2y$10$q2GR1Mk0haUCgjH7AgKTUeLKjWSztmyYtyDbMCtAr5c9RvE2tvcly',NULL,NULL,'+639064688167',NULL,NULL,NULL,'medium',1,'2022-04-28 16:33:52','2022-04-28 16:33:52'),(67,'Pat NIÑA L MENDOZA','Ninamendoza1914@gmail.com',NULL,'$2y$10$JrIDrfpcmjJlBDq5G6pt/eK6ycuL.t1uKc2Ur3FHBzolRd4dabUxG',NULL,NULL,'+639679435188',NULL,NULL,NULL,'medium',1,'2022-04-28 16:34:49','2022-04-28 16:34:49'),(68,'Pat MARK KEVIN J PAJO','markkevinpajo@gmail.com',NULL,'$2y$10$MApswzBI9PCmlzeJVEAZJe3AeJnJCsv3Q8C0SrV/W8oO1JfAiEPtW',NULL,NULL,'+639615227478',NULL,NULL,NULL,'medium',1,'2022-04-28 16:36:15','2022-04-28 16:36:15'),(69,'Pat DEAN DB MARTIN','Deltamike122294@gmail.com',NULL,'$2y$10$0OaXwPc.yH06xZmgBaYG3uMtvzTJsZon7O40vS9GtgybqxJMEhMTK',NULL,NULL,'+639616714408',NULL,NULL,NULL,'medium',1,'2022-04-28 16:37:12','2022-04-28 16:37:12'),(70,'ATC Secretariat','2020atcsec@gmail.com',NULL,'$2y$10$kYiGkeBiQQ8idy31SHODZ.KEBcdTryj.BRtgqaB2Z9bWDumPoyLva',NULL,NULL,'+639054545351',NULL,NULL,NULL,'medium',1,'2022-04-28 17:01:44','2022-04-28 17:01:44'),(71,'Council 1','council1@gmail.com',NULL,'$2y$10$FFVcCaSWGJCPC07lrh5eo.fflm19S5EQ3Vdn68ZUayRGPyDodxMTW',NULL,NULL,'123456789',NULL,NULL,NULL,'medium',1,'2022-04-28 17:04:31','2022-04-28 17:04:31'),(72,'Council 2','council2@gmail.com',NULL,'$2y$10$bB8aRWxPX5nYiWZ8KcnuluHm4QNEO1fvLQNKX2JE/VKtv94m7r7Qi',NULL,NULL,'123456789',NULL,NULL,NULL,'medium',1,'2022-04-28 17:05:16','2022-04-28 17:05:16'),(73,'Council 3','council3@gmail.com',NULL,'$2y$10$B0I2eji9vf4jZNj4bg6MVucIcu22C/TGsglU.16OzEXZBuv0ZSRau',NULL,NULL,'123456789',NULL,NULL,NULL,'medium',1,'2022-04-28 17:05:57','2022-04-28 17:05:57'),(74,'Council 4','council4@gmail.com',NULL,'$2y$10$Yvh9jh3ZQ/bkgt3FnAd5HuMXMsUjrN5KrbCRCh41z5AerUoWLBAJi',NULL,NULL,'123456789',NULL,NULL,NULL,'medium',1,'2022-04-28 17:06:32','2022-04-28 17:06:32'),(75,'Council 5','council5@gmail.com',NULL,'$2y$10$SgE.e8k.r6g/DIHrZx3WE.epkh1UNIwpUiWC80pWoZFAVsDmof9R.',NULL,NULL,'12345678',NULL,NULL,NULL,'medium',1,'2022-04-28 17:07:12','2022-04-28 17:07:12'),(76,'Council 6','council6@gmail.com',NULL,'$2y$10$IknaVG6ApzwiFQll.fT/7eNPvTnquJxC.5Fp9A5TbJbcNyc0V5YtW',NULL,NULL,'123456789',NULL,NULL,NULL,'medium',1,'2022-04-28 17:08:09','2022-04-28 17:08:09'),(77,'Council 7','council7@gmail.com',NULL,'$2y$10$i6.kyRvmiv/v5WOaYuIrgOxzPzI1mm1NfkUdr/2y17urzWTOjtw6C',NULL,NULL,'123456789',NULL,NULL,NULL,'medium',1,'2022-04-28 17:08:39','2022-04-28 17:08:39'),(78,'Council 8','council8@gmail.com',NULL,'$2y$10$hZDADJJQv78QplLo4oQAM.NQyPZ3S/CTcfAgxLkVcvP5oC5tXglmC',NULL,NULL,'123456789',NULL,NULL,NULL,'medium',1,'2022-04-28 17:09:12','2022-04-28 17:09:12'),(79,'Council 9','council9@gmail.com',NULL,'$2y$10$cFIkNjyzVgHsGBGeDAq6dO/g2ZfnhqnNEl1rFI1xk4WSgriLVOF/e',NULL,NULL,'123456789',NULL,NULL,NULL,'medium',1,'2022-04-28 17:10:20','2022-04-28 17:10:20'),(80,'Applicant 1','applicant1@gmail.com',NULL,'$2y$10$1BnhnGFTVMGVOxBfxnQi5eQpyvM/lXTYmt.Uyg3GqGCYr580/1rTO',NULL,NULL,'+639567933529',NULL,NULL,NULL,'medium',1,'2022-04-28 17:11:31','2022-04-28 17:11:31'),(81,'Applicant 2','jessen.ayala@gmail.com',NULL,'$2y$10$ZTeP5V9rno84NxnKiOm7yO9mABXDq9mX6ccW91I3itvR42bao7/gm',NULL,NULL,'+639051074034',NULL,NULL,NULL,'medium',1,'2022-04-28 17:12:51','2022-04-28 17:36:27'),(82,'Review Committee 5','reviewcommittee@gmail.com',NULL,'$2y$10$ATbi4EfJiNY94s85y8DaO.WzJ2UDxiUC7rc15PNuQkeUY50gRbT7K',NULL,NULL,'123456789',NULL,NULL,NULL,'medium',1,'2022-04-28 17:36:07','2022-04-28 17:42:07');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `votes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `application_id` int unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('approved','disapproved') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-28 10:43:18
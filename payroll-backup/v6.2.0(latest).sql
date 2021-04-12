-- MySQL dump 10.13  Distrib 5.7.29, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: payroll
-- ------------------------------------------------------
-- Server version	5.7.29

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `day` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_time` timestamp NULL DEFAULT NULL,
  `exit_time` timestamp NULL DEFAULT NULL,
  `updatable_flag` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_day_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'HR',NULL,'2020-02-20 04:00:44','2020-03-11 03:17:07'),(2,'V2',NULL,'2020-02-20 04:00:50','2020-03-11 03:17:28'),(3,'Accounts',NULL,'2020-03-11 03:30:20','2020-03-11 03:30:50'),(4,'Creative',NULL,'2020-03-11 03:37:43','2020-03-11 03:37:43');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `designations`
--

DROP TABLE IF EXISTS `designations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designations`
--

LOCK TABLES `designations` WRITE;
/*!40000 ALTER TABLE `designations` DISABLE KEYS */;
INSERT INTO `designations` VALUES (1,1,'Head of HR',NULL,'2020-02-20 04:01:08','2020-03-11 04:36:04'),(2,2,'Principle Software Engineer',NULL,'2020-02-20 04:01:41','2020-02-20 04:01:41'),(3,2,'Trainee Software Engineer',NULL,'2020-02-20 04:01:58','2020-02-20 04:01:58'),(4,2,'Intern',NULL,'2020-02-20 04:02:03','2020-02-20 04:02:03'),(5,3,'Executive',NULL,'2020-03-11 03:35:36','2020-03-11 03:35:36'),(6,4,'Visualizer',NULL,'2020-03-11 03:38:11','2020-03-11 03:38:11'),(7,4,'Intern',NULL,'2020-03-21 05:46:52','2020-03-21 05:46:52'),(8,3,'Intern',NULL,'2020-03-31 07:46:03','2020-03-31 07:46:03');
/*!40000 ALTER TABLE `designations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_categories`
--

DROP TABLE IF EXISTS `leave_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `leave_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_limit` int(11) NOT NULL,
  `times_can_take` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_categories`
--

LOCK TABLES `leave_categories` WRITE;
/*!40000 ALTER TABLE `leave_categories` DISABLE KEYS */;
INSERT INTO `leave_categories` VALUES (1,'Casual',22,NULL,NULL,'2020-02-20 05:10:23','2020-02-20 05:10:23'),(2,'Sick',22,NULL,NULL,'2020-02-20 05:11:07','2020-02-20 05:11:07'),(3,'Block',22,NULL,NULL,'2020-02-20 05:11:20','2020-02-20 05:11:20'),(4,'Paternity',15,2,NULL,'2020-02-20 05:15:29','2020-02-20 05:15:29'),(5,'Maternity',180,2,NULL,'2020-02-20 06:03:31','2020-02-20 06:03:31');
/*!40000 ALTER TABLE `leave_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_counts`
--

DROP TABLE IF EXISTS `leave_counts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_counts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `leave_category_id` int(11) NOT NULL,
  `leave_left` int(11) NOT NULL,
  `leave_count_start` date NOT NULL,
  `leave_count_expired` date NOT NULL,
  `times_already_taken` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_counts`
--

LOCK TABLES `leave_counts` WRITE;
/*!40000 ALTER TABLE `leave_counts` DISABLE KEYS */;
INSERT INTO `leave_counts` VALUES (1,1,1,22,'2019-11-03','2020-11-03',0,'2020-02-20 05:10:23','2020-02-20 05:16:22'),(2,1,2,22,'2019-11-03','2020-11-03',0,'2020-02-20 05:11:07','2020-02-20 05:16:22'),(3,1,3,22,'2019-11-03','2020-11-03',0,'2020-02-20 05:11:20','2020-02-20 05:16:22'),(4,2,1,0,'2020-01-16','2021-01-16',0,'2020-02-20 05:13:24','2020-04-02 04:34:03'),(5,2,2,0,'2020-01-16','2021-01-16',0,'2020-02-20 05:13:24','2020-04-02 04:34:03'),(6,2,3,0,'2020-01-16','2021-01-16',0,'2020-02-20 05:13:24','2020-04-02 04:34:03'),(7,3,1,0,'2020-02-01','2021-02-01',0,'2020-02-20 05:14:29','2020-03-02 04:15:01'),(8,3,2,0,'2020-02-01','2021-02-01',0,'2020-02-20 05:14:29','2020-03-02 04:15:01'),(9,3,3,0,'2020-02-01','2021-02-01',0,'2020-02-20 05:14:29','2020-03-02 04:15:01'),(10,1,4,15,'2019-11-03','2020-11-03',0,'2020-02-20 05:15:29','2020-02-20 05:16:22'),(11,2,4,15,'2020-01-16','2021-01-16',0,'2020-02-20 05:15:29','2020-02-20 05:15:29'),(12,3,5,180,'2020-02-01','2021-02-01',0,'2020-02-20 06:03:31','2020-02-20 06:03:31'),(13,4,1,9,'2020-03-01','2021-03-01',0,'2020-03-11 03:40:39','2020-03-31 08:00:29'),(14,4,2,9,'2020-03-01','2021-03-01',0,'2020-03-11 03:40:39','2020-03-31 08:00:29'),(15,4,3,9,'2020-03-01','2021-03-01',0,'2020-03-11 03:40:39','2020-03-31 08:00:29'),(16,4,4,15,'2020-03-01','2021-03-01',0,'2020-03-11 03:40:39','2020-03-11 03:40:39'),(17,5,1,22,'2020-03-01','2021-03-01',0,'2020-03-11 03:48:10','2020-03-31 07:58:58'),(18,5,2,22,'2020-03-01','2021-03-01',0,'2020-03-11 03:48:10','2020-03-31 07:58:58'),(19,5,3,22,'2020-03-01','2021-03-01',0,'2020-03-11 03:48:10','2020-03-31 07:58:58'),(20,5,5,180,'2020-03-01','2021-03-01',0,'2020-03-11 03:48:10','2020-03-11 03:48:10'),(21,6,1,22,'2020-03-12','2021-03-12',0,'2020-03-11 04:09:27','2020-03-11 04:09:27'),(22,6,2,22,'2020-03-12','2021-03-12',0,'2020-03-11 04:09:27','2020-03-11 04:09:27'),(23,6,3,22,'2020-03-12','2021-03-12',0,'2020-03-11 04:09:27','2020-03-11 04:09:27'),(24,6,4,15,'2020-03-12','2021-03-12',0,'2020-03-11 04:09:27','2020-03-11 04:09:27'),(25,7,1,0,'2020-03-15','2021-03-15',0,'2020-03-15 03:21:51','2020-03-21 06:49:28'),(26,7,2,0,'2020-03-15','2021-03-15',0,'2020-03-15 03:21:51','2020-03-21 06:49:28'),(27,7,3,0,'2020-03-15','2021-03-15',0,'2020-03-15 03:21:51','2020-03-21 06:49:28'),(28,7,5,180,'2020-03-15','2021-03-15',0,'2020-03-15 03:21:51','2020-03-15 03:21:51'),(29,8,1,0,'2020-03-21','2021-03-21',0,'2020-03-21 05:28:55','2020-03-21 05:36:13'),(30,8,2,0,'2020-03-21','2021-03-21',0,'2020-03-21 05:28:55','2020-03-21 05:36:13'),(31,8,3,0,'2020-03-21','2021-03-21',0,'2020-03-21 05:28:55','2020-03-21 05:36:13'),(32,8,5,180,'2020-03-21','2021-03-21',0,'2020-03-21 05:28:55','2020-03-21 05:28:55'),(33,9,1,22,'2020-03-31','2021-03-31',0,'2020-03-31 07:47:58','2020-03-31 07:47:58'),(34,9,2,22,'2020-03-31','2021-03-31',0,'2020-03-31 07:47:58','2020-03-31 07:47:58'),(35,9,3,22,'2020-03-31','2021-03-31',0,'2020-03-31 07:47:58','2020-03-31 07:47:58'),(36,9,4,15,'2020-03-31','2021-03-31',0,'2020-03-31 07:47:58','2020-03-31 07:47:58'),(37,10,1,18,'2020-03-31','2021-03-31',0,'2020-03-31 07:48:58','2020-03-31 07:54:55'),(38,10,2,18,'2020-03-31','2021-03-31',0,'2020-03-31 07:48:58','2020-03-31 07:54:55'),(39,10,3,18,'2020-03-31','2021-03-31',0,'2020-03-31 07:48:58','2020-03-31 07:54:55'),(40,10,4,15,'2020-03-31','2021-03-31',0,'2020-03-31 07:48:58','2020-03-31 07:48:58'),(41,11,1,22,'2020-03-31','2021-03-31',0,'2020-03-31 10:08:29','2020-03-31 10:08:29'),(42,11,2,22,'2020-03-31','2021-03-31',0,'2020-03-31 10:08:29','2020-03-31 10:08:29'),(43,11,3,22,'2020-03-31','2021-03-31',0,'2020-03-31 10:08:29','2020-03-31 10:08:29'),(44,11,4,15,'2020-03-31','2021-03-31',0,'2020-03-31 10:08:29','2020-03-31 10:08:29');
/*!40000 ALTER TABLE `leave_counts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leaves`
--

DROP TABLE IF EXISTS `leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leaves` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `leave_category_id` int(11) NOT NULL,
  `leave_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `application_date` timestamp NULL DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `unpaid_count` int(11) DEFAULT NULL,
  `approval_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `last_accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaves`
--

LOCK TABLES `leaves` WRITE;
/*!40000 ALTER TABLE `leaves` DISABLE KEYS */;
INSERT INTO `leaves` VALUES (1,2,1,'Casual needed','2020-03-02 10:13:32','Mar','2020','2020-03-02','2020-03-20',0,'Accepted','2020-03-02 10:14:53','2020-03-02 04:13:32','2020-03-02 04:14:53'),(2,3,2,'I am sick','2020-03-02 10:14:30','Mar','2020','2020-03-02','2020-04-13',3,'Accepted','2020-03-02 10:15:01','2020-03-02 04:14:30','2020-03-02 04:15:01'),(3,2,1,'test','2020-04-02 10:32:12','Apr','2020','2020-04-02','2020-04-28',11,'Accepted','2020-04-02 10:34:03','2020-04-02 04:32:12','2020-04-02 04:34:03'),(4,3,1,'problem','2020-04-02 10:33:16','Apr','2020','2020-04-02','2020-04-14',7,'Accepted','2020-04-02 10:33:50','2020-04-02 04:33:16','2020-04-02 04:33:50'),(5,8,3,'My First Block','2020-03-21 11:34:38','Mar','2020','2020-03-21','2020-04-30',5,'Accepted','2020-03-21 11:36:13','2020-03-21 05:34:38','2020-03-21 05:36:13'),(6,8,2,'I am sick','2020-03-21 11:35:48','Mar','2020','2020-03-21','2020-04-07',0,'Accepted','2020-03-21 11:36:08','2020-03-21 05:35:48','2020-03-21 05:36:08'),(7,7,3,'Boro Leave','2020-03-21 12:48:48','Mar','2020','2020-03-21','2020-06-09',13,'Accepted','2020-03-21 12:49:28','2020-03-21 06:48:48','2020-03-21 06:49:28'),(8,9,1,'Cox\'s Bazar Jabo','2020-03-31 13:50:07','Mar','2020','2020-03-31','2020-04-02',NULL,'Pending',NULL,'2020-03-31 07:50:07','2020-03-31 07:50:07'),(9,10,1,'Bandarban Jabo','2020-03-31 13:50:52','Mar','2020','2020-03-31','2020-04-05',0,'Accepted','2020-03-31 13:54:55','2020-03-31 07:50:52','2020-03-31 07:54:55'),(11,4,1,'Aro ekta biye','2020-03-31 13:52:11','Apr','2020','2020-04-12','2020-04-15',0,'Accepted','2020-03-31 14:00:29','2020-03-31 07:52:11','2020-03-31 08:00:29'),(12,4,3,'Onek gulo biye','2020-03-31 13:53:06','Mar','2020','2020-03-31','2020-04-09',0,'Accepted','2020-03-31 14:00:14','2020-03-31 07:53:06','2020-03-31 08:00:14'),(13,5,1,'Rajjo Chalabo','2020-03-31 13:55:54','Mar','2020','2020-03-31','2020-04-02',0,'Rejected',NULL,'2020-03-31 07:55:54','2020-03-31 07:58:58');
/*!40000 ALTER TABLE `leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_histories`
--

DROP TABLE IF EXISTS `loan_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_histories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month_count` int(11) NOT NULL,
  `contract_duration` int(11) NOT NULL,
  `actual_loan_amount` double NOT NULL,
  `current_loan_amount` double NOT NULL,
  `paid_this_month` double NOT NULL,
  `total_paid_amount` double NOT NULL,
  `loan_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_histories`
--

LOCK TABLES `loan_histories` WRITE;
/*!40000 ALTER TABLE `loan_histories` DISABLE KEYS */;
INSERT INTO `loan_histories` VALUES (1,2,'Mar','2020',0,4,3517,3517,0,0,'started','2020-03-28 13:35:50','2020-03-28 13:35:50'),(2,7,'Mar','2020',0,3,27055,27055,0,0,'started','2020-03-28 13:43:14','2020-03-28 13:43:14'),(3,8,'Mar','2020',0,5,4000,4000,0,0,'started','2020-03-28 13:44:54','2020-03-28 13:44:54'),(4,2,'Apr','2020',1,4,3517,2637.75,879.25,879.25,'running','2020-04-30 00:15:03','2020-04-30 00:15:03'),(5,7,'Apr','2020',1,3,27055,18036.666666666664,9018.333333333334,9018.333333333334,'running','2020-04-30 00:17:53','2020-04-30 00:17:53'),(6,8,'Apr','2020',1,5,4000,3200,800,800,'running','2020-04-30 00:18:53','2020-04-30 00:18:53'),(7,2,'May','2020',2,4,3517,1758.5,879.25,1758.5,'running','2020-05-30 00:19:28','2020-05-30 00:19:28'),(8,7,'May','2020',2,3,27055,9018.333333333332,9018.333333333334,18036.666666666668,'running','2020-05-30 00:19:31','2020-05-30 00:19:31'),(9,8,'May','2020',2,5,4000,2400,800,1600,'running','2020-05-30 00:19:33','2020-05-30 00:19:33'),(15,2,'Jun','2020',3,4,3517,879.25,879.25,2637.75,'running','2020-06-30 00:37:14','2020-06-30 00:37:14'),(16,7,'Jun','2020',3,3,27055,0,9018.333333333332,27055,'finished','2020-06-30 00:38:45','2020-06-30 00:38:45'),(17,8,'Jun','2020',3,5,4000,1600,800,2400,'running','2020-06-30 00:39:01','2020-06-30 00:39:01'),(18,7,'Jun','2020',0,3,1000,1000,0,0,'started','2020-06-30 00:41:33','2020-06-30 00:41:33'),(19,2,'Jul','2020',4,4,3517,0,879.25,3517,'finished','2020-07-30 00:51:07','2020-07-30 00:51:07'),(20,7,'Jul','2020',1,3,1000,666.6666666666667,333.3333333333333,333.3333333333333,'running','2020-07-30 00:51:22','2020-07-30 00:51:22'),(21,8,'Jul','2020',4,5,4000,800,800,3200,'running','2020-07-30 00:51:44','2020-07-30 00:51:44'),(22,7,'Aug','2020',2,3,1000,333.33333333333337,333.3333333333333,666.6666666666666,'running','2020-08-30 01:12:19','2020-08-30 01:12:19'),(23,8,'Aug','2020',5,5,4000,0,800,4000,'finished','2020-08-30 01:12:56','2020-08-30 01:12:56'),(24,7,'Sep','2020',3,3,1000,0,333.33333333333337,1000,'finished','2020-09-30 01:16:25','2020-09-30 01:16:25'),(27,9,'Mar','2020',0,3,5000,5000,0,0,'started','2020-03-31 09:27:05','2020-03-31 09:27:05'),(28,6,'Mar','2020',0,4,80000,80000,0,0,'started','2020-03-31 09:27:37','2020-03-31 09:27:37'),(29,6,'Apr','2020',1,4,80000,60000,20000,20000,'running','2020-04-30 09:35:47','2020-04-30 09:35:47'),(30,9,'Apr','2020',1,3,5000,3333.333333333333,1666.6666666666667,1666.6666666666667,'running','2020-04-30 09:36:12','2020-04-30 09:36:12'),(31,6,'May','2020',2,4,80000,40000,20000,40000,'running','2020-05-30 09:36:51','2020-05-30 09:36:51'),(32,9,'May','2020',2,3,5000,1666.6666666666665,1666.6666666666667,3333.3333333333335,'running','2020-05-30 09:39:07','2020-05-30 09:39:07'),(33,6,'Jun','2020',3,4,80000,20000,20000,60000,'running','2020-06-30 09:39:15','2020-06-30 09:39:15'),(34,9,'Jun','2020',3,3,5000,0,1666.6666666666665,5000,'finished','2020-06-30 09:39:33','2020-06-30 09:39:33'),(35,6,'Jul','2020',4,4,80000,0,20000,80000,'finished','2020-07-30 09:40:18','2020-07-30 09:40:18'),(37,11,'Mar','2020',0,3,2000,2000,0,0,'started','2020-03-31 10:15:37','2020-03-31 10:15:37');
/*!40000 ALTER TABLE `loan_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_requests`
--

DROP TABLE IF EXISTS `loan_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `available_amount` double NOT NULL,
  `requested_amount` double NOT NULL,
  `contract_duration` int(11) NOT NULL,
  `approval_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_requests`
--

LOCK TABLES `loan_requests` WRITE;
/*!40000 ALTER TABLE `loan_requests` DISABLE KEYS */;
INSERT INTO `loan_requests` VALUES (1,2,'2020-03-28 19:37:13',9000,3517,4,'Accepted','2020-03-28 07:21:25','2020-03-28 13:37:13'),(2,3,'2020-03-31 15:15:51',8000,2200,3,'Pending','2020-03-28 07:25:16','2020-03-31 09:15:51'),(3,7,'2020-03-28 19:43:14',36000,27055,3,'Accepted','2020-03-28 07:25:53','2020-03-28 13:43:14'),(4,8,'2020-03-28 19:44:54',7200,4000,5,'Accepted','2020-03-28 07:27:14','2020-03-28 13:44:54'),(5,5,'2020-03-28 19:34:34',19192,1200,10,'Rejected','2020-03-28 13:33:49','2020-03-28 13:34:34'),(6,7,'2020-06-30 06:41:33',36000,1000,3,'Accepted','2020-06-30 00:40:31','2020-06-30 00:41:33'),(7,6,'2020-03-31 15:27:37',1300000,80000,4,'Accepted','2020-03-31 08:48:46','2020-03-31 09:27:37'),(8,9,'2020-03-31 15:27:05',12000,5000,3,'Accepted','2020-03-31 09:09:38','2020-03-31 09:27:05'),(10,11,'2020-03-31 16:15:37',14100,2000,3,'Accepted','2020-03-31 10:13:11','2020-03-31 10:15:37');
/*!40000 ALTER TABLE `loan_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_06_01_000001_create_oauth_auth_codes_table',1),(4,'2016_06_01_000002_create_oauth_access_tokens_table',1),(5,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(6,'2016_06_01_000004_create_oauth_clients_table',1),(7,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(8,'2019_06_11_064730_create_departments_table',1),(9,'2019_06_11_070020_create_designations_table',1),(10,'2019_06_11_080154_create_payments_table',1),(11,'2019_06_11_081155_create_attendances_table',1),(12,'2019_06_11_083636_create_leave_categories_table',1),(13,'2019_06_11_091846_create_salaries_table',1),(14,'2019_06_11_093031_create_leaves_table',1),(15,'2019_06_11_093746_create_working_days_table',1),(16,'2019_06_25_055849_create_companies_table',1),(17,'2019_07_01_082619_create_leave_counts_table',1),(18,'2019_12_01_045413_create_provident_funds_table',1),(19,'2019_12_09_222203_create_loan_histories_table',1),(20,'2019_12_09_235752_create_loan_requests_table',1),(21,'2019_12_14_033749_create_loan_pay_backs_table',1),(22,'2020_03_15_075417_add_kaha',2),(23,'2020_03_30_151817_create_roles_table',3),(24,'2020_03_30_152932_t_e_m_p_t_e_m_p',3),(25,'2020_03_30_174955_oreoreore',4),(26,'2020_03_30_181743_abcd',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` VALUES ('0172622c4fe529180e2b945a180f380bf7b06b6df1d9761e6670ad5c61e61fe6bd10a37c7067fd67',1,1,'Payroll Backend','[]',1,'2020-03-14 23:34:42','2020-03-14 23:34:42','2020-09-15 05:34:42'),('02163a2828a52e30980c7268ad7563eb3363754bd4f38dc6d68c456bda5977cfa3e9d762f5f81142',1,1,'X Payroll','[]',1,'2020-06-30 00:41:18','2020-06-30 00:41:18','2020-12-30 06:41:17'),('027ae9b51b129af1a75036eabb67e7493a71be708bdaf56d0cf19d1f4f89000d705e73a3e275d5fa',1,1,'X Payroll','[]',1,'2020-03-31 08:45:54','2020-03-31 08:45:54','2020-10-01 14:45:54'),('068899d17981b83faddf96e7b13967b0b5c9d48e5aba9e96cb6d21740b9578ce561c8bb7b8a6615e',3,1,'X Payroll','[]',1,'2020-03-22 01:43:01','2020-03-22 01:43:01','2020-09-22 07:43:01'),('07182300a627b8a8553df575bbddab04a221a447d13ca84b1fa1148c0cc49fcdd7d54116a36227e8',4,1,'X Payroll','[]',1,'2020-03-31 07:54:23','2020-03-31 07:54:23','2020-10-01 13:54:23'),('0907ad081a11f7a0e77c661eb2a25efce457549dcb3976cdddc351c48a5dbab1c7440dd9be947fbe',1,1,'X Payroll','[]',1,'2020-03-29 06:50:47','2020-03-29 06:50:47','2020-09-29 12:50:47'),('09aec60714acdce33acd72c51c619782dddf1d053c5855bc3e7f161e6edc6e776a26cbb6660aaa36',1,1,'Payroll Backend','[]',0,'2020-03-11 02:45:13','2020-03-11 02:45:13','2020-09-11 08:45:13'),('0b3e9fa843abe5c33b90e1b73c6f487b55976cbfd64b361814b3a7f5c73de1c0f988eabcb9ee7c52',1,1,'X Payroll','[]',1,'2020-03-28 13:31:43','2020-03-28 13:31:43','2020-09-28 19:31:42'),('0cd7d9422de6243ad451d85a1856b59bc26439b04ee462f47dc8a3434d21821339c2a7eaef81f91a',1,1,'X Payroll','[]',1,'2020-05-30 00:36:16','2020-05-30 00:36:16','2020-11-30 06:36:16'),('11a8f6edb99a2df4483ecb589d89200a1142356e4148fb0f3b825693587cd2f5c21f21c14a9346da',9,1,'X Payroll','[]',1,'2020-03-31 07:49:21','2020-03-31 07:49:21','2020-10-01 13:49:21'),('12576e91e54bb2245d1b936f4570a2fe350601df7d7b1d465c45c49abbbc4e994606f7ecec26c789',1,1,'X Payroll','[]',1,'2020-03-31 07:53:30','2020-03-31 07:53:30','2020-10-01 13:53:30'),('1910e448481ff28e2cf1e0fc31c377082453a4bddb01defbde60c9cb0ac25b3e78339ffeae68173b',1,1,'X Payroll','[]',1,'2020-03-31 07:53:19','2020-03-31 07:53:19','2020-10-01 13:53:19'),('1b826afb39a206573dbe89afebad6fb10219d5392018fac2d708e9aa0c282fcde427a1ace89c602b',3,1,'Payroll Backend','[]',1,'2020-03-02 04:11:41','2020-03-02 04:11:41','2020-09-02 10:11:41'),('1d903aafdce1f1ce37e7329df5936e3213e30ff8eee0aef82d5d5774200ec79202656e71d58ad831',1,1,'X Payroll','[]',1,'2020-03-21 05:36:03','2020-03-21 05:36:03','2020-09-21 11:36:03'),('1e3da0631d847d658e5c8d30a476a1d9aa7ae33c59843d35d9e73279655512104ad9651ea890a5ad',2,1,'Payroll Backend','[]',1,'2020-03-10 00:05:22','2020-03-10 00:05:22','2020-09-10 06:05:22'),('1f4618a48efb57cfc9b0899810b58422fe44f3847d8f0bac82b7fd16e4e6272f2a3769d5f3794d1f',4,1,'X Payroll','[]',1,'2020-03-31 07:51:04','2020-03-31 07:51:04','2020-10-01 13:51:04'),('1fb24d01894939c8aa4240152a6c6f3f0ba5ad311ea82ca80364322565053c288ea942a35fd677c3',1,1,'X Payroll','[]',1,'2020-03-28 07:27:37','2020-03-28 07:27:37','2020-09-28 13:27:37'),('21f4cc2bc5962077edec00ec98d6f7f5b1fbef8d9a428d71b4013704fc1280ad655e4866c106c012',1,1,'Payroll Backend','[]',1,'2020-03-10 00:02:53','2020-03-10 00:02:53','2020-09-10 06:02:53'),('221e6afe6dd733c9bba471c327f2c382f072238731e74dde77a1c3a2e3afbfccad028400a3c4e3bb',1,1,'X Payroll','[]',1,'2020-03-30 09:41:30','2020-03-30 09:41:30','2020-09-30 15:41:30'),('24fab50cd893dee2c695302c53e270d990370e229eaf5312c39e38cf0d1c2d9f1d59615eb3d385d5',5,1,'X Payroll','[]',1,'2020-03-28 13:32:12','2020-03-28 13:32:12','2020-09-28 19:32:12'),('255cf24f70a5ada21396f08b663c87eab9b291f8a37e92eaddb0b5d7ee293915fb3400fb3a652e1f',1,1,'Payroll Backend','[]',1,'2020-03-14 23:52:51','2020-03-14 23:52:51','2020-09-15 05:52:51'),('25bf4597523a4a82421ab29b19a35b2681d0c259a9c6343c8d8240169f9e8d37deda89ccb3e25148',1,1,'X Payroll','[]',0,'2020-05-30 09:36:07','2020-05-30 09:36:07','2020-11-30 15:36:07'),('2721b79f23c6fa17835c65136a6b117d94801a9336fc71f0478bc1678c07a88743038df46de89177',6,1,'X Payroll','[]',1,'2020-03-22 01:44:34','2020-03-22 01:44:34','2020-09-22 07:44:34'),('276d13c31f9787cee37036cf7caf5ff6f7d06269366cc8754fb544a91f2d284fce30767c099b7593',7,1,'X Payroll','[]',1,'2020-06-30 00:39:50','2020-06-30 00:39:50','2020-12-30 06:39:50'),('281bccaf5940da710cbfa1e24490caa92c1f36c880c87550a004023ae4649ce0603b8d4c95eb62e0',1,1,'Payroll Backend','[]',1,'2020-09-11 01:11:09','2020-09-11 01:11:09','2021-03-11 07:11:09'),('29783034c3f2b6b6574c479f702fbbcaab9eb1f0262343d004fadf9f4451a2646b08ffc989059426',1,1,'X Payroll','[]',1,'2020-03-31 10:09:25','2020-03-31 10:09:25','2020-10-01 16:09:25'),('29fe709a1fd66bbc597cb3e0b0de21b67077b084b276782ac447d7beabe4f5ba7832b7577fc0ce5a',1,1,'X Payroll','[]',1,'2020-03-21 06:49:46','2020-03-21 06:49:46','2020-09-21 12:49:46'),('2c404eb0a41c9e61bad7def55cdc3f9b4d61cb985f550fd580d5885b04a1fe68e1bbc11cfe021395',7,1,'X Payroll','[]',1,'2020-05-30 00:34:23','2020-05-30 00:34:23','2020-11-30 06:34:23'),('2f8cff798b6c42c3c66bc11c4566900cdfd38c2190e6b37bccb6f9bfccdbf2e9065db29bd543386f',1,1,'X Payroll','[]',1,'2020-03-20 23:50:27','2020-03-20 23:50:27','2020-09-21 05:50:26'),('31fb21068777fb25c4078a7aef0bc690de46690aa584e43791337a2548759abcb4bc30fadab36cf8',9,1,'X Payroll','[]',1,'2020-03-31 08:50:33','2020-03-31 08:50:33','2020-10-01 14:50:33'),('36c14a84a9a6e311c27af5e946d5d65bac123dbcd6d87cab0943f6411258dc10624abcc2b2f27acc',1,1,'X Payroll','[]',1,'2020-03-22 01:40:31','2020-03-22 01:40:31','2020-09-22 07:40:31'),('382cec6cb22fd91eab02e650183a5b1bc751e5555c44b01c61ad4a9f9e486130690ece36622fa621',2,1,'Payroll Backend','[]',1,'2020-03-09 23:53:06','2020-03-09 23:53:06','2020-09-10 05:53:06'),('3961e500889324dae5d6617de00fcef8ff93b4a8cbb2c18f6e492c5b40c97559438608e007d27238',2,1,'Payroll Backend','[]',1,'2020-03-02 04:13:07','2020-03-02 04:13:07','2020-09-02 10:13:07'),('41146e92535401c01986f4549faa1d2b0bb48e348e19380faa4272ac769225fd568ef13610d75c49',1,1,'X Payroll','[]',1,'2020-03-30 09:54:11','2020-03-30 09:54:11','2020-09-30 15:54:11'),('45de5c75f84b9db9b1e64c4cf44483328bf462be4d449f19ec4ec8ca0f8a9506d9bd29c54b5fb97f',10,1,'X Payroll','[]',1,'2020-03-31 07:50:20','2020-03-31 07:50:20','2020-10-01 13:50:20'),('4674a862726599eae66a60c202dcba7cf59895a059555a42f8b72a95a521b082a89ed5ea1962e3b2',1,1,'X Payroll','[]',0,'2020-03-30 01:23:43','2020-03-30 01:23:43','2020-09-30 07:23:42'),('49021f7e8e19d122fde9627aa11be5badd10549f82db01eb69ee6f89e5942269bcff084262237618',1,1,'Payroll Backend','[]',0,'2020-02-20 03:59:58','2020-02-20 03:59:58','2020-08-20 09:59:58'),('585afdfa725303e706eab032cbccc301fbf63b74ae346c8b942d1b606fe87c5eff281fb79f5a6147',8,1,'X Payroll','[]',1,'2020-03-21 05:29:28','2020-03-21 05:29:28','2020-09-21 11:29:28'),('60597ff011ed2c78e9c6b8c987d06bf5aff0224df9e3393446f92d446db1b4cccea814b2de500b66',3,1,'Payroll Backend','[]',1,'2020-02-20 05:16:48','2020-02-20 05:16:48','2020-08-20 11:16:48'),('63777e92a4ebf064962a6154bb4ca61248cf800fa63824693bde2b861de7d4b950ac155dbe7e7d51',1,1,'X Payroll','[]',1,'2020-03-28 05:06:33','2020-03-28 05:06:33','2020-09-28 11:06:33'),('66f6fea0be35347352788de5df0df87f9cefd1ee2c610c3264c7da024ea0fc80c72a05f9db90dbfd',9,1,'X Payroll','[]',1,'2020-03-30 09:43:46','2020-03-30 09:43:46','2020-09-30 15:43:46'),('6719c8563e2a6074d1cf11fbd3cb1ff5ed2fd4a17f65beaec8e761a3c0d409f63b08e5e1cfed80dc',8,1,'X Payroll','[]',1,'2020-03-28 07:26:39','2020-03-28 07:26:39','2020-09-28 13:26:39'),('6d63e4d700b60d4122f48c027e5de6e1529e1e563687b7dbd6de6c88e81e5472982f1a4510c8bfad',1,1,'Payroll Backend','[]',1,'2020-04-09 23:56:59','2020-04-09 23:56:59','2020-10-10 05:56:59'),('70e231a43450b2e6e74f1dc82f2ea1dab937b760b828a6febd81f1d8d9d15bd7a9494d8e4e4184d0',2,1,'X Payroll','[]',1,'2020-03-28 07:18:31','2020-03-28 07:18:31','2020-09-28 13:18:31'),('71a0af2607aa15d24309c5d85a042a52a65d2399012d94f87b4e918b8005e1cda5b7890bfc545ef6',1,1,'X Payroll','[]',1,'2020-03-30 00:05:23','2020-03-30 00:05:23','2020-09-30 06:05:23'),('788a1502ac2603600092422a8491cef593d922a83de653649127087b9deb8a55daa37232d78b5d33',1,1,'Payroll Backend','[]',1,'2020-04-02 04:33:30','2020-04-02 04:33:30','2020-10-02 10:33:30'),('7c523a7af5ddee3ae131f509f94775bc7600a42502890c81c6f7b7cab7dec6fd76c0a680b329c81b',6,1,'X Payroll','[]',1,'2020-03-30 09:44:45','2020-03-30 09:44:45','2020-09-30 15:44:45'),('7c8650a707fe1c9e33e2b6fce711183c7cef820545c5e1c6f1d37064fa09c973941b00b8332b3634',1,1,'Payroll Backend','[]',1,'2020-03-11 01:55:34','2020-03-11 01:55:34','2020-09-11 07:55:34'),('813012bdc4d3273d468e4514ec471d67c0991cd29639cc62fca39bd62743b24ede60043132a7f524',3,1,'Payroll Backend','[]',1,'2020-02-20 06:02:33','2020-02-20 06:02:33','2020-08-20 12:02:33'),('84564760537aa732262a828460ba816d1010c4931e3f6b67e89a0eca3df3afb0a388f74bf9e9d28d',1,1,'X Payroll','[]',1,'2020-08-30 01:14:40','2020-08-30 01:14:40','2021-03-02 07:14:40'),('849a412f3546876dd6e5c91a438202ec66638543f0273cf300e147fc2ce225e7e02ad06dd97c0503',1,1,'X Payroll','[]',0,'2020-03-31 22:35:41','2020-03-31 22:35:41','2020-10-01 04:35:40'),('8561164bbdb6561c73e7e56dd8cf0b8c2ed19e9f486594cfd938ca5165663ad071f44d17521f2a0d',2,1,'Payroll Backend','[]',1,'2020-02-20 05:16:18','2020-02-20 05:16:18','2020-08-20 11:16:18'),('8647025b78bf90de44c84d96458c40c2b7375338213ae6f4dba958c52722abdbe598df63d38b33bc',1,1,'X Payroll','[]',0,'2020-03-30 11:32:02','2020-03-30 11:32:02','2020-09-30 17:32:02'),('879648a6cabff713f24a7f136fe9613f719e81e6098f44be753862fcfeba9e94325f76e856eaea1e',2,1,'Payroll Backend','[]',1,'2020-05-09 23:58:38','2020-05-09 23:58:38','2020-11-10 05:58:38'),('8ac8fed14a4f592addb05c77e9031fae4e56c93583bb63dfc5702344b26492b703db35e6ec8bf704',2,1,'Payroll Backend','[]',1,'2020-03-11 01:58:48','2020-03-11 01:58:48','2020-09-11 07:58:48'),('8becb4ca13cbc715affc7df4dd4f4957c8c1671b4e589c82cf1e6f5051983e30132f2e8f7ee6e9c1',7,1,'X Payroll','[]',1,'2020-03-22 01:45:00','2020-03-22 01:45:00','2020-09-22 07:45:00'),('8c8e8502db8309dc6c0a525cf7c85ad738f4472d50ee03ae4cf2feabd3c4d36c7652b52db1f4c5af',1,1,'Payroll Backend','[]',1,'2020-03-14 23:57:34','2020-03-14 23:57:34','2020-09-15 05:57:34'),('8f1d9d9c8dd6919a162db99d4241e50ab87a842a345207d98ea81aa0af7d2195bbf453cf7da69050',1,1,'X Payroll','[]',1,'2020-03-31 09:09:59','2020-03-31 09:09:59','2020-10-01 15:09:59'),('943a7b22daa14333d8563e6fa42ca5531e4580db565006cdc654a45a4fc4a239bc3af6ab26cf2426',5,1,'X Payroll','[]',1,'2020-03-31 07:55:14','2020-03-31 07:55:14','2020-10-01 13:55:14'),('945f789a037cb6ce5a7d8813dec060b61b7ccffcfe60206a9645c6b20a55cbbaa18ab7e32dbf2f03',1,1,'X Payroll','[]',1,'2020-03-21 06:49:08','2020-03-21 06:49:08','2020-09-21 12:49:07'),('947e60b724a8aa1e5159cb3e2e77a4ef0c71646a697dfcb3d9f75b3afa0074e5fe746c02e341ea7b',9,1,'X Payroll','[]',1,'2020-03-31 09:09:23','2020-03-31 09:09:23','2020-10-01 15:09:23'),('9480d835803e5b3225205aa68f960fede0a97d1b694e22b91b0797872567d7f37e31102cf7481987',3,1,'Payroll Backend','[]',1,'2020-04-02 04:32:39','2020-04-02 04:32:39','2020-10-02 10:32:39'),('94a433225a4ec08968ea7217a250367b37a4a85882b09fc8961252d1ca9b53b6d9f3584fa49209fa',1,1,'X Payroll','[]',1,'2020-04-22 04:40:56','2020-04-22 04:40:56','2020-10-22 10:40:55'),('994393517a13b07eac430aa18ad4044f794a3d9c0bde87a160bb63c93b3838d3885ae96a7eeeb9ad',1,1,'X Payroll','[]',1,'2020-03-30 09:45:13','2020-03-30 09:45:13','2020-09-30 15:45:13'),('9a6c74b0ca2d23c44e28eaea261d3b908cee70159544618cc006e6c0ca11e285bcbc79ed4f9cc66f',2,1,'Payroll Backend','[]',1,'2020-03-02 04:10:43','2020-03-02 04:10:43','2020-09-02 10:10:43'),('9a8c6fce168202f2674e9da76f09cf67ed8c3166077f0009a1c95ce93a1fad0cd28758c625fa6c2c',1,1,'Payroll Backend','[]',1,'2020-05-09 23:58:30','2020-05-09 23:58:30','2020-11-10 05:58:30'),('9db23f31245806cce5707251cdbe0af9235f7ac4f9d0bc27f880c47a1772cb775842085b4cfe33da',1,1,'X Payroll','[]',1,'2020-03-31 09:05:10','2020-03-31 09:05:10','2020-10-01 15:05:10'),('9f3412a31dea1cf68845c277f61c60138f58e6dfbdbb21bc77e39fef464462bc4484c782aa0d56fc',2,1,'Payroll Backend','[]',1,'2020-04-02 04:31:38','2020-04-02 04:31:38','2020-10-02 10:31:38'),('a05942a6583df31ffb41cf10a1a04cbff3d513520741172da83172fec36a4c91cba59ac7ee74936e',11,1,'X Payroll','[]',1,'2020-03-31 10:12:01','2020-03-31 10:12:01','2020-10-01 16:12:01'),('a2bd488a1de2043035239eb612aa7146d2c9c222d37e97098d68e4d7cd60642bc74c00788a594b2c',1,1,'X Payroll','[]',1,'2020-03-31 10:13:22','2020-03-31 10:13:22','2020-10-01 16:13:22'),('a79e5bcf6cb9d9a1a6e60435290543923928f447cbd1d1c014780807253b07ae88ef54ae34107ce0',7,1,'X Payroll','[]',1,'2020-03-28 14:02:52','2020-03-28 14:02:52','2020-09-28 20:02:52'),('aa860a228cdfb6cb4c18aef506b579b9f28acd79d3ee62ede91e484263a8ed7833e53aa02766ae41',2,1,'Payroll Backend','[]',1,'2020-02-20 06:04:38','2020-02-20 06:04:38','2020-08-20 12:04:38'),('ab78bda590c74cee6d4c77352b1174063884f0404cba99e7db89ba495b4d1d7bf7d0bfb4879fb750',1,1,'X Payroll','[]',0,'2020-03-21 05:36:03','2020-03-21 05:36:03','2020-09-21 11:36:03'),('acbbfcb8b55caca55cbde9d8b570b158f18b3e0257f7f7c9fe8f118891de0f1625bbf9196a379311',1,1,'X Payroll','[]',1,'2020-03-21 06:47:26','2020-03-21 06:47:26','2020-09-21 12:47:25'),('aec7e48907bccec6354b22062dc571a34a84212fbb1416223902f438e1919616af2924ce3a41154f',1,1,'X Payroll','[]',1,'2020-03-31 08:49:37','2020-03-31 08:49:37','2020-10-01 14:49:37'),('b231756f22a696cd5c1a4a92f6e27b153aff62c7af877b8756e1dcb11303d3ec62ac093509a3a156',4,1,'X Payroll','[]',1,'2020-03-31 08:01:20','2020-03-31 08:01:20','2020-10-01 14:01:20'),('b337227525a87cd10a40d152080f0e35df6ea953a25dac449a981aa75309aa50af9236dd057258a9',2,1,'Payroll Backend','[]',1,'2020-03-02 04:31:06','2020-03-02 04:31:06','2020-09-02 10:31:06'),('b5e7a8022d4282e07d72543efdabd22f0cf4f4cd2aeb1f2dd71cfcbe7314279c7ac38aa9b705fb42',8,1,'X Payroll','[]',1,'2020-03-30 00:04:45','2020-03-30 00:04:45','2020-09-30 06:04:44'),('b80dba4eacaf5ae274c4023a7642cc6d37df6d7bac6191213ca6a8a9ac2da7878e0c49d843d60400',7,1,'X Payroll','[]',1,'2020-03-21 06:49:03','2020-03-21 06:49:03','2020-09-21 12:49:03'),('b89141b82be4b144a14e84be644af76b64529c0cb25c20eb80c831904edc79be4cfc6f4f67f9b788',7,1,'X Payroll','[]',1,'2020-03-22 01:45:57','2020-03-22 01:45:57','2020-09-22 07:45:57'),('ba6f36fe572665e8c22d3755541c65e23fa52e23a2b78146d1e4fc92f3d69e9cda6918bdd4600ed3',1,1,'X Payroll','[]',0,'2020-03-29 00:20:54','2020-03-29 00:20:54','2020-09-29 06:20:52'),('bb0cc528bca5d1f52bed2d4ffab4272037c970645bb5fb56d9890dd4c5cc896e8ba137611fa9c835',1,1,'X Payroll','[]',1,'2020-05-22 04:42:14','2020-05-22 04:42:14','2020-11-22 10:42:14'),('bcfb60a0b0d450bacb30bbb96c533bdc287c8fc9e49ea1c7a18547fcb94792de55936031f0e74468',1,1,'X Payroll','[]',1,'2020-03-28 05:08:27','2020-03-28 05:08:27','2020-09-28 11:08:27'),('bde181ef193fd053e61e42f94c90ddaa69ce980b6ca25ab0dfbe2001efd33c3b23e5d549cf8bb70b',3,1,'Payroll Backend','[]',1,'2020-03-02 04:11:00','2020-03-02 04:11:00','2020-09-02 10:11:00'),('bdec0cf94297efc73cdb51d9d7553ed32145318a31bac0825831ad16a75ff15605c80450c4232fd4',7,1,'X Payroll','[]',1,'2020-03-21 06:47:53','2020-03-21 06:47:53','2020-09-21 12:47:53'),('c42cff155a93dfe21f000efbea6c5dda79babd8eb1fb679125065c4925611f2a21f4c26fc967e52a',1,1,'Payroll Backend','[]',0,'2020-02-20 05:12:42','2020-02-20 05:12:42','2020-08-20 11:12:42'),('c5275910f750cb21fbbac6d40b26a593fc48009286d927257b155daa6ff358977ffa5897065e15c1',1,1,'X Payroll','[]',1,'2020-03-31 07:45:15','2020-03-31 07:45:15','2020-10-01 13:45:15'),('c8ca253f6746be0f7f617b632aefc4a7178f901876a441032e1ed19036c726ab2e3e0bb2525ccd4b',5,1,'Payroll Backend','[]',0,'2020-03-11 03:55:07','2020-03-11 03:55:07','2020-09-11 09:55:07'),('c9b339291d13c1c2ce0cda062ba610ee349a4efb4ce8f52308d3e8f67bb512c25b31279dcc3cb3be',1,1,'X Payroll','[]',1,'2020-03-31 07:56:36','2020-03-31 07:56:36','2020-10-01 13:56:36'),('cf35639a51a86a120b2b7f596c90ada5853c73830c76bf626f2e80bf68baccec675b80ad35270e11',1,1,'Payroll Backend','[]',1,'2020-04-11 01:07:13','2020-04-11 01:07:13','2020-10-11 07:07:13'),('cf4ebad60e6ef19ac3404c32e3660db62803dd846a991ecfe65220e8d461753233e6e73df2aa376f',2,1,'X Payroll','[]',1,'2020-03-28 07:20:54','2020-03-28 07:20:54','2020-09-28 13:20:54'),('d02024d8b609a7b078ceb6b1b1cb5c65e9166f21dfe6bc3f55ac27b18f584c92e9525af99049c4c2',6,1,'X Payroll','[]',1,'2020-03-31 08:46:26','2020-03-31 08:46:26','2020-10-01 14:46:26'),('d0d95475d3edd3cb73f6bb5b285cef17940327d4d2531905085254d72463e0d8009a2f27058d92a6',1,1,'X Payroll','[]',1,'2020-03-22 00:06:48','2020-03-22 00:06:48','2020-09-22 06:06:47'),('d28459db9fd076c5b667b3d1119ff42db801f74c39c8bc6f8c1996ba91a4e72e54a1525e878a012f',1,1,'Payroll Backend','[]',1,'2020-03-02 04:09:00','2020-03-02 04:09:00','2020-09-02 10:09:00'),('d2ea27ff7e2e815bc04404d4c3beb2b0480fdfcaa456b3c383b3e37c85337d1ea7209137aab66232',1,1,'X Payroll','[]',1,'2020-03-31 10:11:26','2020-03-31 10:11:26','2020-10-01 16:11:26'),('d3f8170acbd9c62c06998d6e48d257618757725de4f57e1a885310fefd74149eb975ac4d3c3d9850',9,1,'X Payroll','[]',1,'2020-03-30 10:00:38','2020-03-30 10:00:38','2020-09-30 16:00:38'),('d4a87bc1d9b73b74a6c56db9fa857a33f8825adeb62f9e1381f42db6509ff69d104f5709644649a2',1,1,'X Payroll','[]',0,'2020-03-22 04:43:26','2020-03-22 04:43:26','2020-09-22 10:43:25'),('d5207abcf8df89f3b3606163c8db71b6113f08991431486ee8bcd1732f5ee9a5c057937492acd7de',1,1,'X Payroll','[]',1,'2020-03-30 10:01:55','2020-03-30 10:01:55','2020-09-30 16:01:55'),('d5360349a10d130ea51d3f809eb5e9455fc4e7c8e569b35db4bf533ee47b790c23ea6ee6ceebf0d6',1,1,'X Payroll','[]',1,'2020-03-30 09:59:52','2020-03-30 09:59:52','2020-09-30 15:59:51'),('d814b7ef1a7800f16f07b0a792fa460e8e8683bd3dd6ed30f3596d90c856969720ec875540c305fe',3,1,'Payroll Backend','[]',1,'2020-03-02 04:13:54','2020-03-02 04:13:54','2020-09-02 10:13:54'),('dabe0913f0c215ed0b54019c1a6b264130cb21454f0a28e319754d4fc9456aca7df255d5f4657e63',2,1,'Payroll Backend','[]',1,'2020-04-09 23:57:02','2020-04-09 23:57:02','2020-10-10 05:57:02'),('dec4222b16026b38f1d3e0e8cf362ba4b99077d07a3505b2bd3ff365301077b3cef0927d67e61b56',2,1,'Payroll Backend','[]',1,'2020-02-20 05:59:44','2020-02-20 05:59:44','2020-08-20 11:59:44'),('e00d27cc95ba9d96517c72179791c482313ed2ad56024ec011eafd989bdd6650ceb6e6b1c31c761d',1,1,'Payroll Backend','[]',1,'2020-03-09 23:53:01','2020-03-09 23:53:01','2020-09-10 05:53:01'),('e1dc8f5f1807afde6bde201f4e76ac5ea89a4cd5456d51162d8d7c3dd191dc38a15390e9e5959ef7',11,1,'X Payroll','[]',1,'2020-03-31 10:09:03','2020-03-31 10:09:03','2020-10-01 16:09:03'),('e5d033a641256c57c5788733d213c748084bea07696a5278772ad19a9804862d1472eca4bbd4c9ba',5,1,'X Payroll','[]',1,'2020-03-22 01:43:55','2020-03-22 01:43:55','2020-09-22 07:43:55'),('e7c8899af30b2459df5492f0d35467673ae6133f27057d9dcd10385a894bad6597a24331f060921b',10,1,'X Payroll','[]',1,'2020-03-30 10:00:58','2020-03-30 10:00:58','2020-09-30 16:00:58'),('e8a03a16b94e61b1f68d9b9a15c3b8bfcd19f81cb8936c3b67f30da6eed000ae00666ac25023b760',1,1,'X Payroll','[]',1,'2020-04-30 09:35:17','2020-04-30 09:35:17','2020-10-30 15:35:16'),('ecb989bc5026c872d1d308adae6e5fe79303331686533285b25f9d1837427d38906ec4444574bf3b',1,1,'X Payroll','[]',1,'2020-03-21 05:24:39','2020-03-21 05:24:39','2020-09-21 11:24:39'),('ece2c5b7273130ea247fa7e5c2b8805d9de44216b2c587f435f94222b584d8aecfdc3f020e619a8c',3,1,'X Payroll','[]',1,'2020-03-28 07:21:55','2020-03-28 07:21:55','2020-09-28 13:21:55'),('ef595ebb4d6593cd1a03f4ad0767e9d3e9de3867ab62aadb9dbb1ab434fafd4cb4d007a3cf22682e',1,1,'Payroll Backend','[]',1,'2020-03-15 01:49:01','2020-03-15 01:49:01','2020-09-15 07:49:01'),('f3468b4bc78c35864aa2688df3ca3d86b1f34ec2a2619a1145b4b3c3ff930a187efed5db701a9f0c',1,1,'X Payroll','[]',1,'2020-03-28 13:34:04','2020-03-28 13:34:04','2020-09-28 19:34:04'),('f3c709e774da46f9ceffd362c3eacf36ebbffcd9b5074d92113d22d7f1ccfdaffb47c13a5bbcfa73',4,1,'X Payroll','[]',1,'2020-03-22 01:43:25','2020-03-22 01:43:25','2020-09-22 07:43:25'),('f3d5ba5f77dc0e0448fbed4d7638228ef901aecbc2049a8e9ca7dfffb6a40b0a7ae91b0a2508ce4b',1,1,'Payroll Backend','[]',0,'2020-03-11 01:10:37','2020-03-11 01:10:37','2020-09-11 07:10:37'),('f65ba85f61cf3fa80a5ed4eec04825d7c05b536018d5f328c2af7f7a7a57f6ce0efed52c776de92a',8,1,'X Payroll','[]',1,'2020-03-22 01:47:25','2020-03-22 01:47:25','2020-09-22 07:47:25'),('f73691f2c3d3179cd0971075adae74128cbcb77d053e0a0a095764134e784e8772aa6b17d28e5e7a',3,1,'Payroll Backend','[]',0,'2020-02-20 06:05:03','2020-02-20 06:05:03','2020-08-20 12:05:03'),('fb3f598d7517fbb983385261898ee9381047b6b2f8a4df88a70e766d444a79496aad1af7e4d83848',7,1,'X Payroll','[]',1,'2020-03-28 07:26:13','2020-03-28 07:26:13','2020-09-28 13:26:13'),('fe11911300dae365911aecd5ddf93931cb0f225bda501cd2cc54bd1aca0bfb5a201e95dbc0b9f58c',7,1,'X Payroll','[]',1,'2020-03-28 07:25:26','2020-03-28 07:25:26','2020-09-28 13:25:26'),('ff5946e225bdf6632770262546177d1a93e2937481a973b0cd8ae8c97237912808e83e85ff73ccc7',2,1,'X Payroll','[]',1,'2020-03-22 01:42:23','2020-03-22 01:42:23','2020-09-22 07:42:23');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES (1,NULL,'Payroll Backend Personal Access Client','ibyjEbWCZOGmlM5J4ShNn6nAoNTP7sypMBYKlyLa','http://localhost',1,0,0,'2020-02-20 03:59:51','2020-02-20 03:59:51'),(2,NULL,'Payroll Backend Password Grant Client','R7vuEPnAY7GFgeU7dmSFm3xLdYH6D0PmXWAar48E','http://localhost',0,1,0,'2020-02-20 03:59:51','2020-02-20 03:59:51');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` VALUES (1,1,'2020-02-20 03:59:51','2020-02-20 03:59:51');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_monthly_cost` double NOT NULL,
  `payable_amount` double NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,2,'Mar','2020',1350,7650,'2020-03-30 06:12:19','2020-03-30 00:12:19','2020-03-30 00:12:19'),(2,3,'Mar','2020',2100,5900,'2020-03-30 06:12:40','2020-03-30 00:12:40','2020-03-30 00:12:40'),(3,7,'Mar','2020',16300,19700,'2020-03-30 06:12:48','2020-03-30 00:12:48','2020-03-30 00:12:48'),(4,8,'Mar','2020',2060,5140,'2020-03-30 06:12:56','2020-03-30 00:12:56','2020-03-30 00:12:56'),(5,2,'Apr','2020',5529.25,3470.75,'2020-04-30 06:15:03','2020-04-30 00:15:03','2020-04-30 00:15:03'),(6,3,'Apr','2020',3166.67,4833.33,'2020-04-30 06:17:30','2020-04-30 00:17:30','2020-04-30 00:17:30'),(7,7,'Apr','2020',9718.333333333334,26281.67,'2020-04-30 06:17:53','2020-04-30 00:17:53','2020-04-30 00:17:53'),(8,8,'Apr','2020',1660,5540,'2020-04-30 06:18:53','2020-04-30 00:18:53','2020-04-30 00:18:53'),(9,2,'May','2020',2229.25,6770.75,'2020-05-30 06:19:28','2020-05-30 00:19:28','2020-05-30 00:19:28'),(10,3,'May','2020',1300,6700,'2020-05-30 06:19:29','2020-05-30 00:19:29','2020-05-30 00:19:29'),(11,7,'May','2020',9718.333333333334,26281.67,'2020-05-30 06:19:31','2020-05-30 00:19:31','2020-05-30 00:19:31'),(12,8,'May','2020',1660,5540,'2020-05-30 06:19:33','2020-05-30 00:19:33','2020-05-30 00:19:33'),(22,2,'Jun','2020',2229.25,6770.75,'2020-06-30 06:37:14','2020-06-30 00:37:14','2020-06-30 00:37:14'),(23,3,'Jun','2020',1300,6700,'2020-06-30 06:38:04','2020-06-30 00:38:04','2020-06-30 00:38:04'),(24,7,'Jun','2020',9718.333333333334,26281.67,'2020-06-30 06:38:45','2020-06-30 00:38:45','2020-06-30 00:38:45'),(25,8,'Jun','2020',1660,5540,'2020-06-30 06:39:00','2020-06-30 00:39:00','2020-06-30 00:39:00'),(26,2,'Jul','2020',2229.25,6770.75,'2020-07-30 06:51:07','2020-07-30 00:51:07','2020-07-30 00:51:07'),(27,3,'Jul','2020',1300,6700,'2020-07-30 06:51:14','2020-07-30 00:51:14','2020-07-30 00:51:14'),(28,7,'Jul','2020',1033.3333333333333,34966.67,'2020-07-30 06:51:22','2020-07-30 00:51:22','2020-07-30 00:51:22'),(29,8,'Jul','2020',1660,5540,'2020-07-30 06:51:44','2020-07-30 00:51:44','2020-07-30 00:51:44'),(30,2,'Aug','2020',1350,7650,'2020-08-30 07:11:35','2020-08-30 01:11:35','2020-08-30 01:11:35'),(31,3,'Aug','2020',1300,6700,'2020-08-30 07:12:01','2020-08-30 01:12:01','2020-08-30 01:12:01'),(32,7,'Aug','2020',1033.3333333333333,34966.67,'2020-08-30 07:12:19','2020-08-30 01:12:19','2020-08-30 01:12:19'),(33,8,'Aug','2020',1660,5540,'2020-08-30 07:12:55','2020-08-30 01:12:55','2020-08-30 01:12:55'),(34,2,'Sep','2020',1350,7650,'2020-09-30 07:16:07','2020-09-30 01:16:07','2020-09-30 01:16:07'),(35,3,'Sep','2020',1300,6700,'2020-09-30 07:16:14','2020-09-30 01:16:14','2020-09-30 01:16:14'),(36,7,'Sep','2020',1033.3333333333333,34966.67,'2020-09-30 07:16:25','2020-09-30 01:16:25','2020-09-30 01:16:25'),(37,8,'Sep','2020',860,6340,'2020-09-30 07:16:31','2020-09-30 01:16:31','2020-09-30 01:16:31'),(38,6,'Mar','2020',65900,1234100,'2020-03-31 15:30:07','2020-03-31 09:30:07','2020-03-31 09:30:07'),(40,9,'Mar','2020',900,11100,'2020-03-31 15:34:15','2020-03-31 09:34:15','2020-03-31 09:34:15'),(41,6,'Apr','2020',85900,1214100,'2020-04-30 15:35:47','2020-04-30 09:35:47','2020-04-30 09:35:47'),(42,9,'Apr','2020',2566.666666666667,9433.33,'2020-04-30 15:36:12','2020-04-30 09:36:12','2020-04-30 09:36:12'),(43,6,'May','2020',85900,1214100,'2020-05-30 15:36:50','2020-05-30 09:36:50','2020-05-30 09:36:50'),(45,9,'May','2020',2566.666666666667,9433.33,'2020-05-30 15:39:07','2020-05-30 09:39:07','2020-05-30 09:39:07'),(46,6,'Jun','2020',85900,1214100,'2020-06-30 15:39:15','2020-06-30 09:39:15','2020-06-30 09:39:15'),(47,9,'Jun','2020',2566.666666666667,9433.33,'2020-06-30 15:39:33','2020-06-30 09:39:33','2020-06-30 09:39:33'),(48,6,'Jul','2020',85900,1214100,'2020-07-30 15:40:18','2020-07-30 09:40:18','2020-07-30 09:40:18'),(49,9,'Jul','2020',900,11100,'2020-07-30 15:40:33','2020-07-30 09:40:33','2020-07-30 09:40:33'),(50,6,'Aug','2020',65900,1234100,'2020-08-30 15:41:12','2020-08-30 09:41:12','2020-08-30 09:41:12'),(51,9,'Aug','2020',900,11100,'2020-08-30 15:41:25','2020-08-30 09:41:25','2020-08-30 09:41:25');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provident_funds`
--

DROP TABLE IF EXISTS `provident_funds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provident_funds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_balance` double NOT NULL,
  `gross_salary` double NOT NULL,
  `deposit_rate` double NOT NULL,
  `deposit_balance` double NOT NULL,
  `opening_and_deposit` double NOT NULL,
  `payment_in_times` int(11) NOT NULL,
  `company_contribution_rate` double NOT NULL,
  `company_contribution` double NOT NULL,
  `closing_balance` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provident_funds`
--

LOCK TABLES `provident_funds` WRITE;
/*!40000 ALTER TABLE `provident_funds` DISABLE KEYS */;
INSERT INTO `provident_funds` VALUES (1,2,'Mar','2020',0,9000,0.05,450,450,1,0,0,450,'2020-03-30 00:12:19','2020-03-30 00:12:19'),(2,3,'Mar','2020',0,8000,0.05,400,400,1,0,0,400,'2020-03-30 00:12:40','2020-03-30 00:12:40'),(3,8,'Mar','2020',0,7200,0.05,360,360,1,0,0,360,'2020-03-30 00:12:56','2020-03-30 00:12:56'),(4,2,'Apr','2020',450,9000,0.05,450,900,2,0,0,900,'2020-04-30 00:15:03','2020-04-30 00:15:03'),(5,3,'Apr','2020',400,8000,0.05,400,800,2,0,0,800,'2020-04-30 00:17:30','2020-04-30 00:17:30'),(6,8,'Apr','2020',360,7200,0.05,360,720,2,0,0,720,'2020-04-30 00:18:53','2020-04-30 00:18:53'),(7,2,'May','2020',900,9000,0.05,450,1350,3,0,0,1350,'2020-05-30 00:19:28','2020-05-30 00:19:28'),(8,3,'May','2020',800,8000,0.05,400,1200,3,0,0,1200,'2020-05-30 00:19:29','2020-05-30 00:19:29'),(9,8,'May','2020',720,7200,0.05,360,1080,3,0,0,1080,'2020-05-30 00:19:33','2020-05-30 00:19:33'),(17,2,'Jun','2020',1350,9000,0.05,450,1800,4,0,0,1800,'2020-06-30 00:37:14','2020-06-30 00:37:14'),(18,3,'Jun','2020',1200,8000,0.05,400,1600,4,0,0,1600,'2020-06-30 00:38:04','2020-06-30 00:38:04'),(19,8,'Jun','2020',1080,7200,0.05,360,1440,4,0,0,1440,'2020-06-30 00:39:00','2020-06-30 00:39:00'),(20,2,'Jul','2020',1800,9000,0.05,450,2250,5,0,0,2250,'2020-07-30 00:51:07','2020-07-30 00:51:07'),(21,3,'Jul','2020',1600,8000,0.05,400,2000,5,0,0,2000,'2020-07-30 00:51:14','2020-07-30 00:51:14'),(22,8,'Jul','2020',1440,7200,0.05,360,1800,5,0,0,1800,'2020-07-30 00:51:44','2020-07-30 00:51:44'),(23,2,'Aug','2020',2250,9000,0.05,450,2700,6,0,0,2700,'2020-08-30 01:11:35','2020-08-30 01:11:35'),(24,3,'Aug','2020',2000,8000,0.05,400,2400,6,0,0,2400,'2020-08-30 01:12:01','2020-08-30 01:12:01'),(25,8,'Aug','2020',1800,7200,0.05,360,2160,6,0,0,2160,'2020-08-30 01:12:55','2020-08-30 01:12:55'),(26,2,'Sep','2020',2700,9000,0.05,450,3150,7,0,0,3150,'2020-09-30 01:16:07','2020-09-30 01:16:07'),(27,3,'Sep','2020',2400,8000,0.05,400,2800,7,0,0,2800,'2020-09-30 01:16:14','2020-09-30 01:16:14'),(28,8,'Sep','2020',2160,7200,0.05,360,2520,7,0,0,2520,'2020-09-30 01:16:31','2020-09-30 01:16:31'),(29,6,'Mar','2020',0,1300000,0.05,65000,65000,1,0,0,65000,'2020-03-31 09:30:07','2020-03-31 09:30:07'),(31,6,'Apr','2020',65000,1300000,0.05,65000,130000,2,0,0,130000,'2020-04-30 09:35:47','2020-04-30 09:35:47'),(32,6,'May','2020',130000,1300000,0.05,65000,195000,3,0,0,195000,'2020-05-30 09:36:50','2020-05-30 09:36:50'),(34,6,'Jun','2020',195000,1300000,0.05,65000,260000,4,0,0,260000,'2020-06-30 09:39:15','2020-06-30 09:39:15'),(35,6,'Jul','2020',260000,1300000,0.05,65000,325000,5,0,0,325000,'2020-07-30 09:40:18','2020-07-30 09:40:18'),(36,6,'Aug','2020',325000,1300000,0.05,65000,390000,6,0,0,390000,'2020-08-30 09:41:13','2020-08-30 09:41:13');
/*!40000 ALTER TABLE `provident_funds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','2020-03-30 11:24:06','2020-03-30 11:24:06'),(2,'leader','2020-03-30 11:24:45','2020-03-30 11:24:45'),(3,'user','2020-03-30 11:25:02','2020-03-30 11:25:02');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salaries`
--

DROP TABLE IF EXISTS `salaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salaries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `basic_salary` double NOT NULL,
  `house_rent_allowance` double DEFAULT NULL,
  `medical_allowance` double DEFAULT NULL,
  `special_allowance` double DEFAULT NULL,
  `fuel_allowance` double DEFAULT NULL,
  `phone_bill_allowance` double DEFAULT NULL,
  `other_allowance` double DEFAULT NULL,
  `tax_deduction` double DEFAULT NULL,
  `provident_fund` double DEFAULT NULL,
  `other_deduction` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salaries`
--

LOCK TABLES `salaries` WRITE;
/*!40000 ALTER TABLE `salaries` DISABLE KEYS */;
INSERT INTO `salaries` VALUES (1,2,9000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,450,900,'2020-03-02 04:09:17','2020-03-22 04:28:27'),(2,3,8000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,400,900,'2020-03-02 04:09:41','2020-03-22 04:31:10'),(3,5,19000,155,NULL,NULL,NULL,37,NULL,NULL,959.6,900,'2020-03-11 03:57:34','2020-03-22 04:33:41'),(4,4,22000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100,NULL,'2020-03-11 04:10:28','2020-03-22 04:33:22'),(5,6,1300000,NULL,NULL,NULL,NULL,NULL,NULL,0,65000,900,'2020-03-11 04:20:00','2020-03-22 04:29:46'),(6,8,7000,NULL,NULL,NULL,NULL,NULL,200,NULL,360,500,'2020-03-21 05:58:35','2020-03-21 05:58:35'),(7,7,30000,1000,NULL,NULL,NULL,NULL,5000,NULL,0,700,'2020-03-21 06:44:49','2020-03-21 06:44:49'),(8,9,9000,3000,NULL,NULL,NULL,NULL,NULL,NULL,0,900,'2020-03-31 09:07:20','2020-03-31 09:08:38'),(9,11,14000,NULL,NULL,NULL,NULL,NULL,100,NULL,705,1200,'2020-03-31 10:09:57','2020-03-31 10:09:57');
/*!40000 ALTER TABLE `salaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `fathers_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marital_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bangladeshi',
  `permanent_address` text COLLATE utf8mb4_unicode_ci,
  `present_address` text COLLATE utf8mb4_unicode_ci,
  `passport_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `id_of_leader` int(11) NOT NULL,
  `salary_id` int(11) DEFAULT NULL,
  `working_day_id` int(11) DEFAULT NULL,
  `joining_date` date NOT NULL,
  `deposit_pf` int(11) NOT NULL DEFAULT '0',
  `verification_code` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_user_name_unique` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'Miton Ahmed',NULL,'miton@gmail.com',NULL,'$2y$10$8fMEOkHuFUAd0XHbNz05J./h/yqmeOwyCGELZwCFq9/M0HWv4EvE6',NULL,NULL,'male',NULL,'bangladeshi',NULL,NULL,NULL,NULL,'1234567890',NULL,1,1,1,0,NULL,NULL,'2018-11-03',1,NULL,NULL,'2020-02-20 03:57:17','2020-03-30 11:28:45',NULL),(2,NULL,'Ibrahim Khalil',NULL,'ibrahim.khalil@v2.ltd',NULL,'$2y$10$ZszOgRH6qC7TXsy0BKEowOmpMuu8zotyoeY1yhvpKZuwCoM8Ydtye',NULL,NULL,'male',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1234567890',NULL,4,2,3,6,1,1,'2020-01-16',1,NULL,NULL,'2020-02-20 05:13:24','2020-03-30 12:26:25',NULL),(3,NULL,'Name Less',NULL,'ibra804him@gmail.com',NULL,'$2y$10$s4DVKftsg2qFRjeyLZjgSulRCJ.CrgvYuW8NeBeYzqETfO6SQfrQu',NULL,NULL,'female',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1234567890',NULL,4,2,3,6,2,2,'2020-02-01',1,NULL,NULL,'2020-02-20 05:14:29','2020-03-30 12:26:44',NULL),(4,NULL,'Monim Ahmed Sajan',NULL,'sajan@gmail.com',NULL,'$2y$10$fvVTgFUw5j.FGqkwQj.z0.wd6KXOFdi7GZvLYYZGNEYe0eJfLrZDW',NULL,NULL,'male',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1234567890',NULL,5,3,2,1,4,3,'2020-03-01',1,NULL,NULL,'2020-03-11 03:40:39','2020-03-30 12:27:13',NULL),(5,NULL,'Sheikh Hasina',NULL,'hasina@gmail.com',NULL,'$2y$10$5iKgmOX4Smhb4l6tLQ374.2V2RQSeG0Zdzqk3/p/fWeiPIZhwtzE6',NULL,NULL,'female',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1234567890',NULL,6,4,2,1,3,4,'2020-03-01',1,NULL,NULL,'2020-03-11 03:48:10','2020-03-30 12:27:36',NULL),(6,NULL,'Babu Babu',NULL,'babu@gmail.com',NULL,'$2y$10$RERoZMZxWKK8YyB.K5wt2usdsyAtaRj8sQKQCwZgusqJP5P/S4cs.',NULL,NULL,'male',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1234567890',NULL,2,2,2,1,5,5,'2020-03-12',1,NULL,NULL,'2020-03-11 04:09:27','2020-03-30 12:27:41',NULL),(7,NULL,'Tona Ammu',NULL,'tona@gmail.com',NULL,'$2y$10$d2vm5LzlhOUTNPJeaYqfBOf8ZLKhheQsS4A7sE.91RiwFnw.m.Q8q',NULL,NULL,'female',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1234567890',NULL,3,2,3,6,7,6,'2020-03-15',0,NULL,NULL,'2020-03-15 03:21:51','2020-03-30 12:28:28',NULL),(8,NULL,'Noha',NULL,'bsse0804@iit.du.ac.bd',NULL,'$2y$10$6DIW9C5hgZcgOcDSEUrQu.qrvB8.Dc5EljSLk7A19IY9l92Z6p0l6',NULL,NULL,'female',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1966654188',NULL,7,4,3,5,6,7,'2020-03-21',1,NULL,NULL,'2020-03-21 05:28:55','2020-03-30 12:29:25',NULL),(9,NULL,'Jaman Ahmed',NULL,'jaman@gmail.com',NULL,'$2y$10$QCj5sV47x0sZrDijeQWovemWbUAiHIFIr7Jx8p41yImqhYV87J6.i',NULL,NULL,'male',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1995773775',NULL,8,3,3,4,8,8,'2020-03-31',0,NULL,NULL,'2020-03-31 07:47:57','2020-03-31 09:07:21',NULL),(10,NULL,'Rihan Shah',NULL,'rihan@gmail.com',NULL,'$2y$10$SAB6DmXLRGRTAW09gAjmjOHoLgZZ/LQIaL9pR4lo3IbiRJKCQz9uS',NULL,NULL,'male',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1995773775',NULL,8,3,3,4,NULL,9,'2020-03-31',1,NULL,NULL,'2020-03-31 07:48:58','2020-03-31 07:49:00',NULL),(11,NULL,'Anwar Hossen',NULL,'anwar@gmail.com',NULL,'$2y$10$z/u26ACiH3CgTn1QbPyxdOXqySPQT0lPvB4EDyRiDLSHQPdDOil7i',NULL,NULL,'male',NULL,'Bangladeshi',NULL,NULL,NULL,NULL,'1966654188',NULL,7,4,3,5,9,10,'2020-03-31',1,NULL,NULL,'2020-03-31 10:08:28','2020-03-31 10:09:57',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `working_days`
--

DROP TABLE IF EXISTS `working_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `working_days` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sunday` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `monday` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `tuesday` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `wednesday` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `thursday` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `friday` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `saturday` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `working_days`
--

LOCK TABLES `working_days` WRITE;
/*!40000 ALTER TABLE `working_days` DISABLE KEYS */;
INSERT INTO `working_days` VALUES (1,'true','true','true','true','true','false','false','2020-02-20 05:13:25','2020-02-20 05:13:25'),(2,'false','true','true','true','true','false','false','2020-02-20 05:14:31','2020-02-20 05:14:31'),(3,'true','true','true','true','true','false','true','2020-03-11 03:40:41','2020-03-11 03:40:41'),(4,'true','true','true','true','true','true','true','2020-03-11 03:48:11','2020-03-11 03:48:11'),(5,'true','true','true','true','true','false','false','2020-03-11 04:09:28','2020-03-11 04:09:28'),(6,'true','false','true','false','true','false','false','2020-03-15 03:21:53','2020-03-15 03:21:53'),(7,'true','true','true','false','false','false','false','2020-03-21 05:28:57','2020-03-21 05:28:57'),(8,'true','true','true','true','true','false','false','2020-03-31 07:48:00','2020-03-31 07:48:00'),(9,'true','true','true','true','true','false','false','2020-03-31 07:49:00','2020-03-31 07:49:00'),(10,'false','true','true','true','true','false','false','2020-03-31 10:08:30','2020-03-31 10:08:30');
/*!40000 ALTER TABLE `working_days` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-01 11:09:29

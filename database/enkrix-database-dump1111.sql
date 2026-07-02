mysqldump: [Warning] Using a password on the command line interface can be insecure.
-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: enkrix_inventory
-- ------------------------------------------------------
-- Server version	8.0.46

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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int DEFAULT NULL,
  `entity_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_logs_user` (`user_id`),
  KEY `idx_logs_entity` (`entity_type`,`entity_id`),
  KEY `idx_logs_created` (`created_at`),
  CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'System Admin','system_init','system',NULL,'Enkrix IMS','System initialized with seed data','127.0.0.1','2026-04-28 23:00:26'),(2,1,'System Admin','login','auth',1,'System Admin','User logged in','188.28.191.112','2026-04-29 08:40:37'),(3,1,'System Admin','logout','auth',1,'System Admin','User logged out','188.28.191.112','2026-04-29 08:41:53'),(4,1,'System Admin','login','auth',1,'System Admin','User logged in','188.28.191.112','2026-04-29 08:48:02'),(5,1,'System Admin','login','auth',1,'System Admin','User logged in','188.28.191.112','2026-04-29 08:49:39'),(6,1,'System Admin','logout','auth',1,'System Admin','User logged out','188.28.191.112','2026-04-29 08:50:03'),(7,1,'System Admin','logout','auth',1,'System Admin','User logged out','188.28.191.112','2026-04-29 09:13:46'),(8,1,'System Admin','login','auth',1,'System Admin','User logged in','188.28.191.112','2026-04-29 09:36:01'),(9,1,'System Admin','logout','auth',1,'System Admin','User logged out','188.28.191.112','2026-04-29 09:43:27'),(10,1,'System Admin','login','auth',1,'System Admin','User logged in','188.28.191.112','2026-04-29 09:47:40'),(11,1,'System Admin','logout','auth',1,'System Admin','User logged out','188.28.191.112','2026-04-29 09:58:24'),(12,1,'System Admin','login','auth',1,'System Admin','User logged in','188.28.191.112','2026-04-29 10:54:23'),(13,1,'System Admin','created','user',2,'Enkrix Luxury','User account created','92.40.188.2','2026-04-29 11:23:58'),(14,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','86.181.62.171','2026-04-29 11:27:16'),(15,1,'System Admin','deleted','inventory_item',3,'Padded Chairs','Item deleted','92.40.188.2','2026-04-29 11:29:56'),(16,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','86.181.62.171','2026-04-29 11:55:29'),(17,1,'System Admin','deleted','inventory_item',7,'Yamaha P-125 Keyboard','Item deleted','188.28.191.65','2026-04-29 13:58:52'),(18,1,'System Admin','deleted','inventory_item',8,'Acoustic Guitar','Item deleted','188.28.191.65','2026-04-29 13:58:56'),(19,1,'System Admin','deleted','inventory_item',9,'Training Manuals','Item deleted','188.28.191.65','2026-04-29 13:59:00'),(20,1,'System Admin','deleted','inventory_item',2,'Yamaha MG10 Mixer','Item deleted','188.28.191.65','2026-04-29 13:59:33'),(21,1,'System Admin','deleted','inventory_item',1,'Shure SM58 Microphone','Item deleted','188.28.191.65','2026-04-29 13:59:36'),(22,1,'System Admin','deleted','inventory_item',10,'Printer Paper (Ream)','Item deleted','188.28.191.65','2026-04-29 13:59:41'),(23,1,'System Admin','deleted','inventory_item',4,'Folding Tables','Item deleted','188.28.191.65','2026-04-29 13:59:50'),(24,1,'System Admin','deleted','inventory_item',5,'Epson Projector EB-X51','Item deleted','188.28.191.65','2026-04-29 13:59:53'),(25,1,'System Admin','deleted','inventory_item',6,'Canon DSLR Camera 250D','Item deleted','188.28.191.65','2026-04-29 13:59:55'),(26,1,'System Admin','deleted','category',1,'Audio Equipment','Category deleted','188.28.191.65','2026-04-29 14:01:10'),(27,1,'System Admin','deleted','category',5,'Books & Materials','Category deleted','188.28.191.65','2026-04-29 14:01:18'),(28,1,'System Admin','deleted','category',2,'Furniture','Category deleted','188.28.191.65','2026-04-29 14:01:20'),(29,1,'System Admin','deleted','category',3,'Media Devices','Category deleted','188.28.191.65','2026-04-29 14:01:23'),(30,1,'System Admin','deleted','category',4,'Musical Instruments','Category deleted','188.28.191.65','2026-04-29 14:01:26'),(31,1,'System Admin','deleted','category',6,'Supplies & Consumables','Category deleted','188.28.191.65','2026-04-29 14:01:29'),(32,1,'System Admin','created','category',7,'Caps','Category created','188.28.191.65','2026-04-29 14:13:03'),(33,1,'System Admin','created','inventory_item',11,'Ankara Cap','Item created','188.28.191.65','2026-04-29 14:13:48'),(34,1,'System Admin','sold','sale',1,'Ankara Cap','Sold 2 × Ankara Cap @ £50.00','188.28.191.65','2026-04-29 14:14:57'),(35,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','86.181.62.171','2026-04-29 14:40:56'),(36,2,'Enkrix Luxury','created','inventory_item',12,'blue cap','Item created','86.181.62.171','2026-04-29 14:47:03'),(37,2,'Enkrix Luxury','sold','sale',2,'blue cap','Sold 5 × blue cap @ £20.00','86.181.62.171','2026-04-29 14:50:14'),(38,2,'Enkrix Luxury','created','expense',1,'post office','Recorded expense: post office — £30.00','86.181.62.171','2026-04-29 14:53:28'),(39,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','86.179.192.228','2026-05-04 12:05:48'),(40,2,'Enkrix Luxury','created','category',8,'Aso-Oke Mini Fringe Bag','Category created','86.179.192.228','2026-05-04 12:10:49'),(41,2,'Enkrix Luxury','created','category',9,'Àkànké Ankara Tote Bag','Category created','86.179.192.228','2026-05-04 12:11:39'),(42,2,'Enkrix Luxury','created','inventory_item',13,'Àkànké Ankara Tote Bag with Wooden Handles','Item created','86.179.192.228','2026-05-04 12:16:44'),(43,2,'Enkrix Luxury','updated','inventory_item',12,'Rhinestones caps','Item updated','86.179.192.228','2026-05-04 12:19:44'),(44,2,'Enkrix Luxury','updated','inventory_item',12,'Rhinestones caps','Item updated','86.179.192.228','2026-05-04 12:19:45'),(45,2,'Enkrix Luxury','updated','inventory_item',11,'Afrofusion Caps','Item updated','86.179.192.228','2026-05-04 12:23:38'),(46,2,'Enkrix Luxury','created','category',10,'Ankara Earings','Category created','86.179.192.228','2026-05-04 12:30:39'),(47,2,'Enkrix Luxury','deactivated','user',1,'System Admin','User deactivated','86.179.192.228','2026-05-04 12:48:06'),(48,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','188.28.191.109','2026-05-09 18:25:46'),(49,2,'Enkrix Luxury','updated','inventory_item',11,'Afrofusion Caps','Item updated','188.28.191.109','2026-05-09 18:32:07'),(50,2,'Enkrix Luxury','updated','inventory_item',11,'Afrofusion Caps','Item updated','188.28.191.109','2026-05-09 18:37:23'),(51,2,'Enkrix Luxury','activated','user',1,'System Admin','User activated','188.28.191.109','2026-05-10 06:52:22'),(52,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-13 09:39:25'),(53,2,'Enkrix Luxury','created','category',11,'Women\'s Thick Fleece-Lined Hooded','Category created','81.151.150.14','2026-05-13 09:42:56'),(54,2,'Enkrix Luxury','created','inventory_item',14,'Women\'s Thick Fleece-Lined Hooded','Item created','81.151.150.14','2026-05-13 09:47:56'),(55,2,'Enkrix Luxury','updated','inventory_item',11,'Afrofusion Caps','Item updated','81.151.150.14','2026-05-13 09:50:06'),(56,2,'Enkrix Luxury','created','category',12,'Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes','Category created','81.151.150.14','2026-05-13 09:52:45'),(57,2,'Enkrix Luxury','created','inventory_item',15,'Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes 44-50 Navy & Blue Striped Design','Item created','81.151.150.14','2026-05-13 09:55:15'),(58,2,'Enkrix Luxury','updated','inventory_item',12,'Rhinestones caps','Item updated','81.151.150.14','2026-05-13 09:55:55'),(59,2,'Enkrix Luxury','updated','inventory_item',13,'Àkànké Ankara Tote Bag with Wooden Handles','Item updated','81.151.150.14','2026-05-13 09:56:30'),(60,2,'Enkrix Luxury','created','category',13,'Relaxed Fit Tracksuit Set – Royal Blue with Yellow Stripes for Sizes','Category created','81.151.150.14','2026-05-13 10:13:23'),(61,2,'Enkrix Luxury','created','inventory_item',16,'Relaxed Fit Tracksuit Set – Royal Blue with Yellow Stripes for Sizes 44–50 Comfortable and Stylish','Item created','81.151.150.14','2026-05-13 10:15:34'),(62,2,'Enkrix Luxury','created','category',14,'Royal Rhinestone Hooded Faux Fur Coat for Women','Category created','81.151.150.14','2026-05-13 10:37:13'),(63,2,'Enkrix Luxury','created','inventory_item',17,'Royal Rhinestone Hooded Faux Fur Coat for Women','Item created','81.151.150.14','2026-05-13 10:42:34'),(64,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-14 08:58:43'),(65,2,'Enkrix Luxury','created','category',15,'Rhinestone Red Jumper','Category created','81.151.150.14','2026-05-14 09:01:12'),(66,2,'Enkrix Luxury','created','category',16,'Red Rhinestone hoodie','Category created','81.151.150.14','2026-05-14 09:01:41'),(67,2,'Enkrix Luxury','created','category',17,'Blue Roundneck Tshirt with Short','Category created','81.151.150.14','2026-05-14 09:02:30'),(68,2,'Enkrix Luxury','created','inventory_item',18,'Luxe Bear Hoodie','Item created','81.151.150.14','2026-05-14 09:06:48'),(69,2,'Enkrix Luxury','created','inventory_item',19,'Rose Sparkle Jumper - Oversized Fit (Red)','Item created','81.151.150.14','2026-05-14 09:09:32'),(70,2,'Enkrix Luxury','created','inventory_item',20,'Crystal Teddy Two-Piece Set (Blue)','Item created','81.151.150.14','2026-05-14 09:14:20'),(71,2,'Enkrix Luxury','updated','category',17,'Blue Roundneck Tshirt with Short','Category updated','81.151.150.14','2026-05-14 09:14:55'),(72,2,'Enkrix Luxury','created','inventory_item',21,'Aso-Oke Mini Fringe Bag','Item created','81.151.150.14','2026-05-14 09:20:04'),(73,2,'Enkrix Luxury','created','category',18,'Ankara Mini Bag','Category created','81.151.150.14','2026-05-14 09:21:13'),(74,2,'Enkrix Luxury','created','inventory_item',22,'Ankara Mini Bag','Item created','81.151.150.14','2026-05-14 09:23:05'),(75,2,'Enkrix Luxury','updated','inventory_item',22,'Ankara Mini Bag','Item updated','81.151.150.14','2026-05-14 09:23:43'),(76,2,'Enkrix Luxury','updated','inventory_item',13,'Àkànké Ankara Tote Bag with Wooden Handles','Item updated','81.151.150.14','2026-05-14 09:24:01'),(77,2,'Enkrix Luxury','updated','inventory_item',13,'Àkànké Ankara Tote Bag with Wooden Handles','Item updated','81.151.150.14','2026-05-14 09:24:14'),(78,2,'Enkrix Luxury','updated','inventory_item',12,'Rhinestones caps','Item updated','81.151.150.14','2026-05-14 09:24:29'),(79,2,'Enkrix Luxury','updated','inventory_item',13,'Àkànké Ankara Tote Bag with Wooden Handles','Item updated','81.151.150.14','2026-05-14 09:24:48'),(80,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-14 11:00:54'),(81,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-14 11:23:20'),(82,2,'Enkrix Luxury','created','category',19,'Luxury Teddy Rhinestone Set – Chocolate','Category created','81.151.150.14','2026-05-14 11:25:06'),(83,2,'Enkrix Luxury','created','inventory_item',23,'Luxury Teddy Rhinestone Set – Chocolate','Item created','81.151.150.14','2026-05-14 11:27:36'),(84,2,'Enkrix Luxury','assigned','assignment',1,'Rhinestones caps','Assigned 10 unit(s) to Michael/Operations','81.151.150.14','2026-05-14 12:26:19'),(85,2,'Enkrix Luxury','returned','assignment',1,'Rhinestones caps','Returned 10 unit(s) from Michael/Operations','81.151.150.14','2026-05-14 12:26:52'),(86,2,'Enkrix Luxury','created','category',20,'Aso-Oke Autogele Rhinestone','Category created','81.151.150.14','2026-05-14 12:42:35'),(87,2,'Enkrix Luxury','created','category',21,'Sego Autogele','Category created','81.151.150.14','2026-05-14 12:43:04'),(88,2,'Enkrix Luxury','created','category',22,'Premium Silk Autogele','Category created','81.151.150.14','2026-05-14 12:43:34'),(89,2,'Enkrix Luxury','created','category',23,'Sequin Autogele','Category created','81.151.150.14','2026-05-14 12:43:53'),(90,2,'Enkrix Luxury','created','category',24,'Rose flower Autogele','Category created','81.151.150.14','2026-05-14 12:44:12'),(91,2,'Enkrix Luxury','created','inventory_item',24,'Aso-Oke Autogele Rhinestones','Item created','81.151.150.14','2026-05-14 12:50:52'),(92,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-14 13:03:54'),(93,2,'Enkrix Luxury','created','inventory_item',25,'Queens Sego Glam Autogele','Item created','81.151.150.14','2026-05-14 13:09:23'),(94,2,'Enkrix Luxury','created','inventory_item',26,'Silk Statement Autogele','Item created','81.151.150.14','2026-05-14 13:13:14'),(95,2,'Enkrix Luxury','created','inventory_item',27,'Sequin Autogele','Item created','81.151.150.14','2026-05-14 13:17:26'),(96,2,'Enkrix Luxury','created','inventory_item',28,'Rose-Flower Autogele','Item created','81.151.150.14','2026-05-14 13:19:58'),(97,2,'Enkrix Luxury','created','category',25,'LazraTurkey Dress','Category created','81.151.150.14','2026-05-14 13:21:32'),(98,2,'Enkrix Luxury','created','inventory_item',29,'Lazra Turkey Dress - Red','Item created','81.151.150.14','2026-05-14 13:26:27'),(99,2,'Enkrix Luxury','updated','inventory_item',29,'Lazra Turkey Dress - Red','Item updated','81.151.150.14','2026-05-14 13:27:15'),(100,2,'Enkrix Luxury','created','category',26,'Coffee Black two-piece set','Category created','81.151.150.14','2026-05-14 13:48:05'),(101,2,'Enkrix Luxury','created','inventory_item',30,'Coffee Black Positive Set','Item created','81.151.150.14','2026-05-14 13:51:03'),(102,2,'Enkrix Luxury','sold','sale',3,'Coffee Black Positive Set','Sold 1 × Coffee Black Positive Set @ £30.00','81.151.150.14','2026-05-14 13:52:43'),(103,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-14 14:05:12'),(104,2,'Enkrix Luxury','created','category',27,'Enkrix Felino Luxe Rhinestone Tracksuit Set','Category created','81.151.150.14','2026-05-14 14:05:51'),(105,2,'Enkrix Luxury','created','inventory_item',31,'Enkrix Felino Luxe Rhinestone Tracksuit Set','Item created','81.151.150.14','2026-05-14 14:07:36'),(106,2,'Enkrix Luxury','sold','sale',4,'Crystal Teddy Two-Piece Set (Blue)','Sold 1 × Crystal Teddy Two-Piece Set (Blue) @ £30.00','81.151.150.14','2026-05-14 14:10:14'),(107,2,'Enkrix Luxury','sold','sale',5,'Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes 44-50 Navy & Blue Striped Design','Sold 1 × Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes 44-50 Navy & Blue Striped Design @ £40.00','81.151.150.14','2026-05-14 14:11:21'),(108,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-14 15:15:17'),(109,2,'Enkrix Luxury','sold','sale',6,'Rose Sparkle Jumper - Oversized Fit (Red)','Sold 6 × Rose Sparkle Jumper - Oversized Fit (Red) @ £15.00','81.151.150.14','2026-05-14 15:16:18'),(110,2,'Enkrix Luxury','created','category',28,'Enkrix Everyday Luxe Set – Pink Sparkle Edition','Category created','81.151.150.14','2026-05-14 15:17:40'),(111,2,'Enkrix Luxury','created','inventory_item',32,'Enkrix Everyday Luxe Set – Pink Sparkle Edition','Item created','81.151.150.14','2026-05-14 15:25:06'),(112,2,'Enkrix Luxury','updated','inventory_item',32,'Enkrix Everyday Luxe Set – Pink Sparkle Edition','Item updated','81.151.150.14','2026-05-14 15:25:44'),(113,2,'Enkrix Luxury','sold','sale',7,'Enkrix Everyday Luxe Set – Pink Sparkle Edition','Sold 2 × Enkrix Everyday Luxe Set – Pink Sparkle Edition @ £40.00','81.151.150.14','2026-05-14 15:26:34'),(114,2,'Enkrix Luxury','sold','sale',8,'Luxe Bear Hoodie','Sold 2 × Luxe Bear Hoodie @ £15.00','81.151.150.14','2026-05-14 15:28:07'),(115,2,'Enkrix Luxury','created','category',29,'Stretch Pleated Top & Wide-Leg Pants Set','Category created','81.151.150.14','2026-05-14 15:35:32'),(116,2,'Enkrix Luxury','created','inventory_item',33,'Stretch Pleated Top & Wide-Leg Pants Set','Item created','81.151.150.14','2026-05-14 15:38:42'),(117,2,'Enkrix Luxury','sold','sale',9,'Stretch Pleated Top & Wide-Leg Pants Set','Sold 1 × Stretch Pleated Top & Wide-Leg Pants Set @ £43.99','81.151.150.14','2026-05-14 15:40:34'),(118,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','188.28.191.112','2026-05-14 16:03:00'),(119,2,'Enkrix Luxury','created','inventory_item',34,'Michael Alex','Item created','188.28.191.112','2026-05-14 16:04:33'),(120,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-14 16:07:18'),(121,2,'Enkrix Luxury','updated','sale',6,'Rose Sparkle Jumper - Oversized Fit (Red)','Edited sale — qty: 5, price: £15.00','81.151.150.14','2026-05-14 16:26:52'),(122,2,'Enkrix Luxury','deleted','inventory_item',34,'Michael Alex','Item deleted','81.151.150.14','2026-05-14 16:29:24'),(123,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-14 16:59:35'),(124,2,'Enkrix Luxury','created','category',31,'Senegalese Ankara Short Dress','Category created','81.151.150.14','2026-05-14 17:00:19'),(125,2,'Enkrix Luxury','updated','inventory_item',15,'Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes 44-50 Navy & Blue Striped Design','Item updated','81.151.150.14','2026-05-14 18:36:54'),(126,2,'Enkrix Luxury','created','category',32,'Enkrix Smokestone Teddy Luxe Tracksuit Set','Category created','81.151.150.14','2026-05-14 18:40:38'),(127,2,'Enkrix Luxury','created','inventory_item',35,'Enkrix Smokestone Teddy Luxe Tracksuit Set','Item created','81.151.150.14','2026-05-14 18:42:58'),(128,2,'Enkrix Luxury','created','category',33,'Enkrix Noir Luxe Bear Lounge Set','Category created','81.151.150.14','2026-05-14 18:44:09'),(129,2,'Enkrix Luxury','created','inventory_item',36,'Enkrix Noir Luxe Bear Lounge Set','Item created','81.151.150.14','2026-05-14 18:50:32'),(130,2,'Enkrix Luxury','updated','category',27,'Enkrix Felino Luxe Rhinestone Tracksuit Set','Category updated','81.151.150.14','2026-05-14 18:54:29'),(131,2,'Enkrix Luxury','updated','category',19,'Luxury Teddy Rhinestone Set – Chocolate','Category updated','81.151.150.14','2026-05-14 18:54:49'),(132,2,'Enkrix Luxury','created','inventory_item',37,'Senegalese Ankara short dress','Item created','81.151.150.14','2026-05-14 19:03:54'),(133,2,'Enkrix Luxury','sold','sale',10,'Senegalese Ankara short dress','Sold 13 × Senegalese Ankara short dress @ £60.00','81.151.150.14','2026-05-14 19:04:39'),(134,2,'Enkrix Luxury','created','category',34,'Ankara Autogele','Category created','81.151.150.14','2026-05-14 19:06:58'),(135,2,'Enkrix Luxury','created','category',35,'Ankara necklaces','Category created','81.151.150.14','2026-05-14 19:07:30'),(136,2,'Enkrix Luxury','created','inventory_item',38,'Ankara Necklaces','Item created','81.151.150.14','2026-05-14 19:16:29'),(137,2,'Enkrix Luxury','created','inventory_item',39,'Ankara Autogele','Item created','81.151.150.14','2026-05-14 19:39:43'),(138,2,'Enkrix Luxury','created','category',36,'Royal Yellow Senegalese Lace Boubou with Rhinestone & Embroidery Detailing','Category created','81.151.150.14','2026-05-14 19:42:43'),(139,2,'Enkrix Luxury','created','inventory_item',40,'Royal Yellow Senegalese Lace Boubou with Rhinestone & Embroidery Detailing','Item created','81.151.150.14','2026-05-14 19:45:29'),(140,2,'Enkrix Luxury','sold','sale',11,'Royal Yellow Senegalese Lace Boubou with Rhinestone & Embroidery Detailing','Sold 2 × Royal Yellow Senegalese Lace Boubou with Rhinestone & Embroidery Detailing @ £150.00','81.151.150.14','2026-05-14 19:46:05'),(141,2,'Enkrix Luxury','created','category',37,'Queen of Elegance Kaftan','Category created','81.151.150.14','2026-05-14 19:48:16'),(142,2,'Enkrix Luxury','created','inventory_item',41,'Queen of Elegance Kaftan Pink','Item created','81.151.150.14','2026-05-14 19:50:24'),(143,2,'Enkrix Luxury','sold','sale',12,'Queen of Elegance Kaftan Pink','Sold 1 × Queen of Elegance Kaftan Pink @ £150.00','81.151.150.14','2026-05-14 19:51:18'),(144,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-05-14 20:15:24'),(145,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-06-01 09:14:09'),(146,2,'Enkrix Luxury','sold','sale',13,'Ankara Necklaces','Sold 2 × Ankara Necklaces @ £10.00','81.151.150.14','2026-06-01 09:16:34'),(147,2,'Enkrix Luxury','created','category',38,'Red Christmas Jumper','Category created','81.151.150.14','2026-06-01 09:21:26'),(148,2,'Enkrix Luxury','created','inventory_item',42,'Christmas Jumper','Item created','81.151.150.14','2026-06-01 09:25:30'),(149,2,'Enkrix Luxury','login','auth',2,'Enkrix Luxury','User logged in','81.151.150.14','2026-06-11 18:25:36'),(150,2,'Enkrix Luxury','created','inventory_item',43,'Ankara Autogele','Item created','81.151.150.14','2026-06-11 18:29:41'),(151,2,'Enkrix Luxury','sold','sale',14,'Ankara Autogele','Sold 1 × Ankara Autogele @ £25.00','81.151.150.14','2026-06-11 18:30:19'),(152,2,'Enkrix Luxury','updated','sale',14,'Ankara Autogele','Edited sale — qty: 1, price: £25.00','81.151.150.14','2026-06-11 18:30:45'),(153,2,'Enkrix Luxury','sold','sale',15,'Ankara Necklaces','Sold 1 × Ankara Necklaces @ £10.00','81.151.150.14','2026-06-11 18:31:41'),(154,2,'Enkrix Luxury','sold','sale',16,'Ankara Mini Bag','Sold 1 × Ankara Mini Bag @ £25.00','81.151.150.14','2026-06-11 18:33:42'),(155,1,'System Admin','login','auth',1,'System Admin','User logged in','188.28.191.73','2026-07-02 17:13:11');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `assigned_to_type` enum('department','individual') COLLATE utf8mb4_unicode_ci NOT NULL,
  `assigned_to_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_assigned` int NOT NULL,
  `assigned_by` int NOT NULL,
  `assignment_date` date NOT NULL,
  `expected_return_date` date DEFAULT NULL,
  `actual_return_date` date DEFAULT NULL,
  `status` enum('active','returned','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_assignments_item` (`item_id`),
  KEY `idx_assignments_status` (`status`),
  KEY `idx_assignments_assigned_by` (`assigned_by`),
  CONSTRAINT `fk_assignments_item` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_assignments_user` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignments`
--

LOCK TABLES `assignments` WRITE;
/*!40000 ALTER TABLE `assignments` DISABLE KEYS */;
INSERT INTO `assignments` VALUES (1,12,'individual','Michael/Operations',10,2,'2026-05-14','2026-05-15','2026-05-14','returned','Make sure you sort everything out  and send the remaining to the warehouse','2026-05-14 12:26:19','2026-05-14 12:26:52');
/*!40000 ALTER TABLE `assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#D4A853',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`),
  KEY `idx_categories_created_by` (`created_by`),
  CONSTRAINT `fk_categories_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (7,'Caps','','#d4a853',1,'2026-04-29 14:13:03','2026-04-29 14:13:03'),(8,'Aso-Oke Mini Fringe Bag','Aso Oke Bags','#d40c53',2,'2026-05-04 12:10:49','2026-05-04 12:10:49'),(9,'Àkànké Ankara Tote Bag','','#d4a853',2,'2026-05-04 12:11:39','2026-05-04 12:11:39'),(10,'Ankara Earings','','#d4a8f9',2,'2026-05-04 12:30:39','2026-05-04 12:30:39'),(11,'Women\'s Thick Fleece-Lined Hooded','Winter Parka Warm Drawstring Waist Coat Charcoal Grey with Pockets and Button Closure','#796853',2,'2026-05-13 09:42:56','2026-05-13 09:42:56'),(12,'Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes','44-50 Navy & Blue Striped Design','#a5a8fb',2,'2026-05-13 09:52:45','2026-05-13 09:52:45'),(13,'Relaxed Fit Tracksuit Set – Royal Blue with Yellow Stripes for Sizes','44–50 Comfortable and Stylish Turkey design','#d4a853',2,'2026-05-13 10:13:23','2026-05-13 10:13:23'),(14,'Royal Rhinestone Hooded Faux Fur Coat for Women','','#ada8ac',2,'2026-05-13 10:37:13','2026-05-13 10:37:13'),(15,'Rhinestone Red Jumper','','#ff0b53',2,'2026-05-14 09:01:12','2026-05-14 09:01:12'),(16,'Red Rhinestone hoodie','','#ec2f53',2,'2026-05-14 09:01:41','2026-05-14 09:01:41'),(17,'Blue Roundneck Tshirt with Short','','#3aa8ef',2,'2026-05-14 09:02:30','2026-05-14 09:14:55'),(18,'Ankara Mini Bag','','#3ba8f5',2,'2026-05-14 09:21:13','2026-05-14 09:21:13'),(19,'Luxury Teddy Rhinestone Set – Chocolate','','#999100',2,'2026-05-14 11:25:06','2026-05-14 18:54:49'),(20,'Aso-Oke Autogele Rhinestone','','#25a853',2,'2026-05-14 12:42:35','2026-05-14 12:42:35'),(21,'Sego Autogele','','#d4a853',2,'2026-05-14 12:43:04','2026-05-14 12:43:04'),(22,'Premium Silk Autogele','','#d4a853',2,'2026-05-14 12:43:34','2026-05-14 12:43:34'),(23,'Sequin Autogele','','#d4a853',2,'2026-05-14 12:43:53','2026-05-14 12:43:53'),(24,'Rose flower Autogele','','#d4a853',2,'2026-05-14 12:44:12','2026-05-14 12:44:12'),(25,'LazraTurkey Dress','','#f22653',2,'2026-05-14 13:21:32','2026-05-14 13:21:32'),(26,'Coffee Black two-piece set','','#d4a853',2,'2026-05-14 13:48:05','2026-05-14 13:48:05'),(27,'Enkrix Felino Luxe Rhinestone Tracksuit Set','','#88860d',2,'2026-05-14 14:05:51','2026-05-14 18:54:29'),(28,'Enkrix Everyday Luxe Set – Pink Sparkle Edition','','#d427aa',2,'2026-05-14 15:17:40','2026-05-14 15:17:40'),(29,'Stretch Pleated Top & Wide-Leg Pants Set','Mustard and Green','#d4a853',2,'2026-05-14 15:35:32','2026-05-14 15:35:32'),(31,'Senegalese Ankara Short Dress','','#3ba8ff',2,'2026-05-14 17:00:19','2026-05-14 17:00:19'),(32,'Enkrix Smokestone Teddy Luxe Tracksuit Set','','#637598',2,'2026-05-14 18:40:38','2026-05-14 18:40:38'),(33,'Enkrix Noir Luxe Bear Lounge Set','','#af8f8b',2,'2026-05-14 18:44:09','2026-05-14 18:44:09'),(34,'Ankara Autogele','','#4ea8fe',2,'2026-05-14 19:06:58','2026-05-14 19:06:58'),(35,'Ankara necklaces','','#d4a853',2,'2026-05-14 19:07:30','2026-05-14 19:07:30'),(36,'Royal Yellow Senegalese Lace Boubou with Rhinestone & Embroidery Detailing','','#d4a853',2,'2026-05-14 19:42:43','2026-05-14 19:42:43'),(37,'Queen of Elegance Kaftan','Pink and black','#d4177a',2,'2026-05-14 19:48:16','2026-05-14 19:48:16'),(38,'Red Christmas Jumper','','#d40c53',2,'2026-06-01 09:21:26','2026-06-01 09:21:26');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'General',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `expense_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `recorded_by` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_expense_date` (`expense_date`),
  KEY `idx_recorded_by` (`recorded_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'post office','General',30.00,'2026-04-29','',2,'2026-04-29 14:53:28');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_items`
--

DROP TABLE IF EXISTS `inventory_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL DEFAULT '0',
  `quantity_assigned` int NOT NULL DEFAULT '0',
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_status` enum('New','Good','Fair','Damaged') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Good',
  `location` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `low_stock_threshold` int NOT NULL DEFAULT '5',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_items_barcode` (`barcode`),
  KEY `idx_items_category` (`category_id`),
  KEY `idx_items_condition` (`condition_status`),
  KEY `idx_items_location` (`location`),
  KEY `idx_items_created_by` (`created_by`),
  CONSTRAINT `fk_items_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_items_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_items`
--

LOCK TABLES `inventory_items` WRITE;
/*!40000 ALTER TABLE `inventory_items` DISABLE KEYS */;
INSERT INTO `inventory_items` VALUES (11,'Afrofusion Caps',7,'African Ankara caps',12,2,'pcs','New','Swindon','2025-11-07',7.00,15.00,5,'img_6a04494e8f6935.37161732.jpeg',NULL,1,'2026-04-29 14:13:48','2026-05-13 09:50:06'),(12,'Rhinestones caps',7,'',30,5,'pcs','New','Swindon','2025-11-03',5.00,15.00,5,'img_6a044aab209232.54607595.jpeg',NULL,2,'2026-04-29 14:47:03','2026-05-14 12:26:52'),(13,'Àkànké Ankara Tote Bag with Wooden Handles',9,'Available in a variety of vibrant Ankara prints',5,0,'pcs','New','Swindon','2025-11-07',6.00,20.00,1,'img_6a044acdf28710.98984824.jpeg',NULL,2,'2026-05-04 12:16:44','2026-05-14 09:24:48'),(14,'Women\'s Thick Fleece-Lined Hooded',11,'Winter Parka Warm Drawstring Waist Coat Charcoal Grey with Pockets and Button Closure',8,0,'pcs','New','Swindon','2025-10-31',20.00,45.00,2,'img_6a0448cce70517.21231796.jpeg',NULL,2,'2026-05-13 09:47:56','2026-05-13 09:47:56'),(15,'Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes 44-50 Navy & Blue Striped Design',12,'Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes 44-50 Navy & Blue Striped Design',3,1,'pcs','New','Swindon','2025-08-22',25.00,40.00,1,'img_6a061646b9f2e7.50821935.jpeg',NULL,2,'2026-05-13 09:55:15','2026-05-14 18:36:54'),(16,'Relaxed Fit Tracksuit Set – Royal Blue with Yellow Stripes for Sizes 44–50 Comfortable and Stylish',13,'',4,0,'4 pcs','New','Swindon','2025-08-25',25.00,40.00,1,'img_6a044f4686d6b6.30416825.jpeg',NULL,2,'2026-05-13 10:15:34','2026-05-13 10:15:34'),(17,'Royal Rhinestone Hooded Faux Fur Coat for Women',14,'',5,0,'pcs','New','Swindon','2025-11-03',25.00,80.00,1,'img_6a04559ab46368.64629308.jpeg',NULL,2,'2026-05-13 10:42:34','2026-05-13 10:42:34'),(18,'Luxe Bear Hoodie',16,'',4,2,'4 pcs','New','Swindon','2025-11-03',10.00,30.00,1,'img_6a0590a7f0dfc4.05284025.jpeg',NULL,2,'2026-05-14 09:06:47','2026-05-14 15:28:07'),(19,'Rose Sparkle Jumper - Oversized Fit (Red)',15,'',10,5,'10pcs','New','Swindon','2025-11-03',10.00,30.00,1,'img_6a05914c664fb6.76406879.png',NULL,2,'2026-05-14 09:09:32','2026-05-14 16:26:52'),(20,'Crystal Teddy Two-Piece Set (Blue)',17,'',5,1,'5pcs','New','Swindon','2025-11-03',12.00,30.00,1,'img_6a05926ce87f06.55159531.jpeg',NULL,2,'2026-05-14 09:14:20','2026-05-14 14:10:14'),(21,'Aso-Oke Mini Fringe Bag',8,'',12,0,'12pcs','New','Swindon','2025-10-29',10.00,30.00,1,'img_6a0593c4af6ad2.85253043.jpeg',NULL,2,'2026-05-14 09:20:04','2026-05-14 09:20:04'),(22,'Ankara Mini Bag',18,'',12,1,'12pcs','New','Swindon','2025-10-29',8.00,25.00,1,'img_6a05949f34f752.03386016.jpeg',NULL,2,'2026-05-14 09:23:05','2026-06-11 18:33:42'),(23,'Luxury Teddy Rhinestone Set – Chocolate',19,'',5,0,'1 pcs','New','Swindon','2025-11-03',18.00,50.00,1,'img_6a05b1a89008a3.96314747.jpeg',NULL,2,'2026-05-14 11:27:36','2026-05-14 11:27:36'),(24,'Aso-Oke Autogele Rhinestones',20,'',20,0,'20pcs','New','Swindon','2025-08-12',18.00,45.00,1,'img_6a05c52c292c65.37064064.jpeg',NULL,2,'2026-05-14 12:50:52','2026-05-14 12:50:52'),(25,'Queens Sego Glam Autogele',21,'',10,0,'10pcs','New','Swindon',NULL,15.00,40.00,2,'img_6a05c983512904.84411093.png',NULL,2,'2026-05-14 13:09:23','2026-05-14 13:09:23'),(26,'Silk Statement Autogele',22,'',10,0,'10pcs','New','Swindon','2025-07-22',8.00,25.00,1,'img_6a05ca6a4d7ad9.14799684.jpeg',NULL,2,'2026-05-14 13:13:14','2026-05-14 13:13:14'),(27,'Sequin Autogele',23,'',10,0,'10pcs','New','Swindon','2025-07-22',7.00,25.00,1,'img_6a05cb66b17a72.24570024.jpeg',NULL,2,'2026-05-14 13:17:26','2026-05-14 13:17:26'),(28,'Rose-Flower Autogele',24,'',10,0,'10pcs','New','Swindon','2025-07-22',5.00,20.00,1,'img_6a05cbfea34db5.43797567.png',NULL,2,'2026-05-14 13:19:58','2026-05-14 13:19:58'),(29,'Lazra Turkey Dress - Red',25,'',4,0,'4pcs','New','Swindon','2025-10-01',25.00,60.00,1,'img_6a05cdb369dfc9.66054958.jpeg',NULL,2,'2026-05-14 13:26:27','2026-05-14 13:27:15'),(30,'Coffee Black Positive Set',26,'',10,1,'10pcs','New','Swindon','2025-11-03',15.00,40.00,1,'img_6a05d347d664d9.19300555.jpeg',NULL,2,'2026-05-14 13:51:03','2026-05-14 13:52:43'),(31,'Enkrix Felino Luxe Rhinestone Tracksuit Set',27,'',5,0,'5pcs','New','Swindon','2025-11-03',18.00,60.00,1,'img_6a05d7287df231.73199005.jpeg',NULL,2,'2026-05-14 14:07:36','2026-05-14 14:07:36'),(32,'Enkrix Everyday Luxe Set – Pink Sparkle Edition',28,'',7,2,'7pcs','New','Swindon','2025-11-03',20.00,60.00,1,'img_6a05e978da1978.43854077.png',NULL,2,'2026-05-14 15:25:06','2026-05-14 15:26:34'),(33,'Stretch Pleated Top & Wide-Leg Pants Set',29,'',2,1,'2pcs','New','Swindon','2025-06-14',17.00,43.99,0,'img_6a05ec82e0cba5.98496849.jpeg',NULL,2,'2026-05-14 15:38:42','2026-05-14 15:40:34'),(35,'Enkrix Smokestone Teddy Luxe Tracksuit Set',32,'',3,0,'pcs','New','Swindon','2025-11-03',20.00,0.00,1,'img_6a0617b2a4cd03.05967450.jpeg',NULL,2,'2026-05-14 18:42:58','2026-05-14 18:42:58'),(36,'Enkrix Noir Luxe Bear Lounge Set',33,'',5,0,'5pcs','New','Swindon','2025-11-03',18.00,45.00,1,'img_6a0619783413b8.05578393.jpeg',NULL,2,'2026-05-14 18:50:32','2026-05-14 18:50:32'),(37,'Senegalese Ankara short dress',31,'',27,13,'pcs','New','Swindon','2025-07-29',30.00,60.00,2,'img_6a061c9a4ffc22.16618427.jpeg',NULL,2,'2026-05-14 19:03:54','2026-05-14 19:04:39'),(38,'Ankara Necklaces',35,'',12,3,'12pcs','New','Swindon','2025-10-29',4.00,10.00,1,'img_6a061f8d8b5320.24127703.jpeg',NULL,2,'2026-05-14 19:16:29','2026-06-11 18:31:41'),(39,'Ankara Autogele',34,'',3,0,'3pcs','New','Swindon',NULL,12.00,30.00,1,'img_6a0624ff4070e1.21681514.jpeg',NULL,2,'2026-05-14 19:39:43','2026-05-14 19:39:43'),(40,'Royal Yellow Senegalese Lace Boubou with Rhinestone & Embroidery Detailing',36,'',2,2,'2pcs','New','Swindon','2025-07-21',80.00,150.00,0,'img_6a062659cc37f6.82203172.jpeg',NULL,2,'2026-05-14 19:45:29','2026-05-14 19:46:05'),(41,'Queen of Elegance Kaftan Pink',37,'Pink and black',1,1,'1pcs','New','Swindon','2025-07-21',90.00,150.00,0,'img_6a0627800e7e32.38270231.jpeg',NULL,2,'2026-05-14 19:50:24','2026-05-14 19:51:18'),(42,'Christmas Jumper',38,'',3,0,'3pcs','New','','2025-10-27',8.00,20.00,1,'img_6a1d500a14a395.83027501.jpeg',NULL,2,'2026-06-01 09:25:30','2026-06-01 09:25:30'),(43,'Ankara Autogele',34,'',3,1,'1pcs','New','Swindon','2025-10-17',15.00,30.00,1,'img_6a2afe95655349.30715647.jpeg',NULL,2,'2026-06-11 18:29:41','2026-06-11 18:30:19');
/*!40000 ALTER TABLE `inventory_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','Full system control, manage users, view audit logs','2026-04-28 23:00:26'),(2,'Inventory Manager','Manage items & stock, assign/recover items','2026-04-28 23:00:26'),(3,'Viewer','Read-only access, view reports only','2026-04-28 23:00:26');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int unsigned NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_sold` int unsigned NOT NULL DEFAULT '1',
  `cost_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_revenue` decimal(12,2) NOT NULL DEFAULT '0.00',
  `profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sold_by` int unsigned NOT NULL,
  `sale_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_item_id` (`item_id`),
  KEY `idx_sale_date` (`sale_date`),
  KEY `idx_sold_by` (`sold_by`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,11,'Ankara Cap',2,25.04,50.00,50.08,100.00,49.92,1,'2026-04-29','','2026-04-29 14:14:57'),(2,12,'blue cap',5,10.00,20.00,50.00,100.00,50.00,2,'2026-04-29','','2026-04-29 14:50:14'),(3,30,'Coffee Black Positive Set',1,15.00,30.00,15.00,30.00,15.00,2,'2026-05-14','Was on sale','2026-05-14 13:52:43'),(4,20,'Crystal Teddy Two-Piece Set (Blue)',1,12.00,30.00,12.00,30.00,18.00,2,'2026-04-16','','2026-05-14 14:10:14'),(5,15,'Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes 44-50 Navy & Blue Striped Design',1,25.00,40.00,25.00,40.00,15.00,2,'2026-05-11','','2026-05-14 14:11:21'),(6,19,'Rose Sparkle Jumper - Oversized Fit (Red)',5,10.00,15.00,50.00,75.00,25.00,2,'2026-05-14','on sales','2026-05-14 15:16:18'),(7,32,'Enkrix Everyday Luxe Set – Pink Sparkle Edition',2,20.00,40.00,40.00,80.00,40.00,2,'2025-12-19','','2026-05-14 15:26:34'),(8,18,'Luxe Bear Hoodie',2,10.00,15.00,20.00,30.00,10.00,2,'2026-05-14','','2026-05-14 15:28:07'),(9,33,'Stretch Pleated Top & Wide-Leg Pants Set',1,17.00,43.99,17.00,43.99,26.99,2,'2025-09-12','','2026-05-14 15:40:34'),(10,37,'Senegalese Ankara short dress',13,30.00,60.00,390.00,780.00,390.00,2,'2025-12-11','','2026-05-14 19:04:39'),(11,40,'Royal Yellow Senegalese Lace Boubou with Rhinestone & Embroidery Detailing',2,80.00,150.00,160.00,300.00,140.00,2,'2025-11-13','','2026-05-14 19:46:05'),(12,41,'Queen of Elegance Kaftan Pink',1,90.00,150.00,90.00,150.00,60.00,2,'2025-09-18','','2026-05-14 19:51:18'),(13,38,'Ankara Necklaces',2,4.00,10.00,8.00,20.00,12.00,2,'2026-05-17','','2026-06-01 09:16:34'),(14,43,'Ankara Autogele',1,15.00,25.00,15.00,25.00,10.00,2,'2026-06-09','','2026-06-11 18:30:19'),(15,38,'Ankara Necklaces',1,4.00,10.00,4.00,10.00,6.00,2,'2026-06-09','','2026-06-11 18:31:41'),(16,22,'Ankara Mini Bag',1,8.00,25.00,8.00,25.00,17.00,2,'2026-06-09','','2026-06-11 18:33:42');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_movements` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int unsigned NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `movement_type` enum('stock_in','stock_out','sale','assignment','return','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_change` int NOT NULL,
  `quantity_before` int NOT NULL DEFAULT '0',
  `quantity_after` int NOT NULL DEFAULT '0',
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_item_id` (`item_id`),
  KEY `idx_movement_type` (`movement_type`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
INSERT INTO `stock_movements` VALUES (1,11,'Ankara Cap','sale',-2,100,98,'sale',1,'Sold 2 unit(s) at £50.00',1,'2026-04-29 14:14:57'),(2,12,'blue cap','sale',-5,50,45,'sale',2,'Sold 5 unit(s) at £20.00',2,'2026-04-29 14:50:14'),(3,30,'Coffee Black Positive Set','sale',-1,10,9,'sale',3,'Sold 1 unit(s) at £30.00',2,'2026-05-14 13:52:43'),(4,20,'Crystal Teddy Two-Piece Set (Blue)','sale',-1,5,4,'sale',4,'Sold 1 unit(s) at £30.00',2,'2026-05-14 14:10:14'),(5,15,'Enkrix \"Just Be Kind\" 3-Piece Layered Set for Women Sizes 44-50 Navy & Blue Striped Design','sale',-1,3,2,'sale',5,'Sold 1 unit(s) at £40.00',2,'2026-05-14 14:11:21'),(6,19,'Rose Sparkle Jumper - Oversized Fit (Red)','sale',-6,10,4,'sale',6,'Sold 6 unit(s) at £15.00',2,'2026-05-14 15:16:18'),(7,32,'Enkrix Everyday Luxe Set – Pink Sparkle Edition','sale',-2,7,5,'sale',7,'Sold 2 unit(s) at £40.00',2,'2026-05-14 15:26:34'),(8,18,'Luxe Bear Hoodie','sale',-2,4,2,'sale',8,'Sold 2 unit(s) at £15.00',2,'2026-05-14 15:28:07'),(9,33,'Stretch Pleated Top & Wide-Leg Pants Set','sale',-1,2,1,'sale',9,'Sold 1 unit(s) at £43.99',2,'2026-05-14 15:40:34'),(10,19,'Rose Sparkle Jumper - Oversized Fit (Red)','adjustment',1,10,11,'sale',6,'Sale #6 edited: qty 6 → 5',2,'2026-05-14 16:26:52'),(11,37,'Senegalese Ankara short dress','sale',-13,27,14,'sale',10,'Sold 13 unit(s) at £60.00',2,'2026-05-14 19:04:39'),(12,40,'Royal Yellow Senegalese Lace Boubou with Rhinestone & Embroidery Detailing','sale',-2,2,0,'sale',11,'Sold 2 unit(s) at £150.00',2,'2026-05-14 19:46:05'),(13,41,'Queen of Elegance Kaftan Pink','sale',-1,1,0,'sale',12,'Sold 1 unit(s) at £150.00',2,'2026-05-14 19:51:18'),(14,38,'Ankara Necklaces','sale',-2,12,10,'sale',13,'Sold 2 unit(s) at £10.00',2,'2026-06-01 09:16:34'),(15,43,'Ankara Autogele','sale',-1,3,2,'sale',14,'Sold 1 unit(s) at £25.00',2,'2026-06-11 18:30:19'),(16,38,'Ankara Necklaces','sale',-1,12,11,'sale',15,'Sold 1 unit(s) at £10.00',2,'2026-06-11 18:31:41'),(17,22,'Ankara Mini Bag','sale',-1,12,11,'sale',16,'Sold 1 unit(s) at £25.00',2,'2026-06-11 18:33:42');
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Admin','admin@enkrix.local','$2y$12$XuK8sewmjnjHn2nKWtu22OER3bRl806btQE4vSDcg5JZrgDZbGNym',1,1,'2026-07-02 17:13:11','2026-04-28 23:00:26','2026-07-02 17:13:11'),(2,'Enkrix Luxury','enkrixessentials@gmail.com','$2y$12$gVHFPeOd5czugWzCUXIVNOMPQNOEkfDORo6Bh978LHBekE30vjiKi',1,1,'2026-06-11 18:25:36','2026-04-29 11:23:58','2026-06-11 18:25:36');
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

-- Dump completed on 2026-07-02 19:20:27

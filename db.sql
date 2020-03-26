-- MySQL dump 10.16  Distrib 10.1.37-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: shop
-- ------------------------------------------------------
-- Server version	10.1.37-MariaDB

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
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `cart_id` int(32) NOT NULL AUTO_INCREMENT,
  `user_id` int(32) DEFAULT NULL,
  `order_id` int(32) DEFAULT NULL,
  PRIMARY KEY (`cart_id`),
  UNIQUE KEY `cart_id` (`cart_id`),
  KEY `cart_ibfk_1` (`order_id`),
  KEY `cart_ibfk_2` (`user_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (23,2,23),(24,2,24),(25,2,25),(26,15,26),(27,15,27);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_item`
--

DROP TABLE IF EXISTS `cart_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_item` (
  `cart_id` int(32) NOT NULL,
  `product_id` int(32) NOT NULL,
  `quantity` int(32) DEFAULT NULL,
  PRIMARY KEY (`cart_id`,`product_id`),
  KEY `cart_item_ibfk_2` (`product_id`),
  CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_item`
--

LOCK TABLES `cart_item` WRITE;
/*!40000 ALTER TABLE `cart_item` DISABLE KEYS */;
INSERT INTO `cart_item` VALUES (23,3,1),(23,5,1),(24,1,1),(24,6,1),(25,2,4),(25,4,2),(25,5,4),(26,1,8),(26,3,8),(27,4,1);
/*!40000 ALTER TABLE `cart_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorys`
--

DROP TABLE IF EXISTS `categorys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorys` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorys`
--

LOCK TABLES `categorys` WRITE;
/*!40000 ALTER TABLE `categorys` DISABLE KEYS */;
INSERT INTO `categorys` VALUES (1,'Như cc','BÙI'),(2,'Killing','hehe'),(3,'Football',''),(6,'Multiplayer','');
/*!40000 ALTER TABLE `categorys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorys_link_products`
--

DROP TABLE IF EXISTS `categorys_link_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorys_link_products` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `product_id` int(32) NOT NULL,
  `category_id` int(32) NOT NULL,
  `category_name` varchar(30) DEFAULT NULL,
  `product_title` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `category_id` (`category_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `categorys_link_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categorys` (`id`),
  CONSTRAINT `categorys_link_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorys_link_products`
--

LOCK TABLES `categorys_link_products` WRITE;
/*!40000 ALTER TABLE `categorys_link_products` DISABLE KEYS */;
INSERT INTO `categorys_link_products` VALUES (70,1,2,'Killing','Nintendo Switch with Gray Joy‑Con - HAC-001(-01)'),(72,1,6,'Multiplayer','Nintendo Switch with Gray Joy‑Con - HAC-001(-01)'),(75,2,2,'Killing','AdidasAdidas Originals Trefoil Hoody'),(76,2,6,'Multiplayer','AdidasAdidas Originals Trefoil Hoody'),(79,50,1,'Như cc','4'),(80,50,2,'Killing','4'),(81,50,3,'Football','4'),(82,50,6,'Multiplayer','4'),(87,3,2,'Killing','Timberland'),(88,3,6,'Multiplayer','Timberland'),(91,51,2,'Killing','Icon for shell'),(92,51,3,'Football','Icon for shell'),(93,52,2,'Killing','Picture'),(94,52,3,'Football','Picture');
/*!40000 ALTER TABLE `categorys_link_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(32) DEFAULT NULL,
  `type` enum('header','thumbnail','slide') DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `date_create` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (61,1,'thumbnail','dPoRIFVnF95W2FQsueTuGoGD.jpg','2019-10-24 14:12:51'),(62,1,'thumbnail','LCBUWwJJ6JKOYvWpSXjdQb54.jpg','2019-10-24 14:12:51'),(65,1,'thumbnail','9ApiKarz6p2s6kzRADwARkz7.jpg','2019-10-24 14:42:24'),(66,1,'thumbnail','diYxhTVB4dGPCTD-ngy-zfZc.jpg','2019-10-24 14:42:24'),(67,1,'thumbnail','j2VMIeOVeinmmoAd4kM4cXUB.jpg','2019-10-24 14:43:02'),(68,1,'thumbnail','o_2nXjYFmG9rNEqPokRCFMG2.jpg','2019-10-24 14:43:02'),(71,1,'thumbnail','h7nX_QSxHXgRPPtRXKSRjN86.jpg','2019-10-24 23:53:14'),(151,50,'thumbnail','6R0DOLslZfuUUG1k23SBrKkA.png','2019-12-16 17:58:16'),(152,3,'thumbnail','XuoKv31G02Zo35lQAroW81U_.png','2019-12-16 18:00:52'),(153,51,'thumbnail','xeb-Z-ADRSQJrHxuN7S7SG61.jpg','2020-03-24 16:47:18'),(154,51,'thumbnail','PImgB7MjruYAnySrsAnLYVd-.png','2020-03-24 16:47:18'),(155,51,'thumbnail','KTi3m7z1XtIfq9rxndqJJT9o.png','2020-03-24 16:47:18'),(156,52,'thumbnail','-h4stJU1J8dLLo8E2_olOcnM.png','2020-03-24 16:57:54');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `user_id` int(32) DEFAULT NULL,
  `address` varchar(60) DEFAULT NULL,
  `phone` char(13) DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `status` varchar(32) DEFAULT NULL,
  `paymentID` char(50) DEFAULT NULL,
  `order_id` int(32) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`order_id`),
  KEY `cart_ibfk_1` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW3QS2Q2U2645627R554051F',1),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW3QS2Q2U2645627R554051F',2),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',3),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',4),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',5),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',6),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',7),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',8),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',9),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',10),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',11),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',12),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',13),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW34JKI76G0958788596314D',14),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW4AJ2Q3LA939451M839714P',15),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LW4ARMQ0FM265334M047643C',16),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LXFED3A7RJ920306N6081935',17),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LXFESGI96E86297VN6475601',18),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LX3UPGA3X015549GM102415C',19),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LX5QMUY8CL13236VH6876937',20),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LZ3ZBQY8SW34939C15806818',21),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LZ3ZMEA06V162659S731634E',22),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LZ3ZMVY6FL90846JB564831T',23),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LZ3ZTPA50S551194V135151G',24),(2,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LZ4IK7A83721669AA5194508',25),(15,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LZ4LOMA87V62877XB514742W',26),(15,'1 Main St, San Jose',NULL,'sb-547n437457828@personal.example.com','approved','PAYID-LZ4LZRY6ML10167JT5033934',27);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` longtext,
  `price` decimal(5,2) DEFAULT NULL,
  `image` varchar(2083) DEFAULT NULL,
  `status` enum('Available','Out of stock','Coming Soon','Unavailable') DEFAULT NULL,
  `rate` enum('1','2','3','4','5') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Nintendo Switch with Gray Joy‑Con - HAC-001(-01)','Play your way with the Nintendo Switch gaming system. Whether you’re at home or on the go, solo or with friends, the Nintendo Switch system is designed to fit your life. Dock your Nintendo Switch to enjoy HD gaming on your TV. Heading out? Just undock your console and keep playing in handheld mode\r\nThis model includes battery life of approximately 4.5 to 9 hours\r\nThe battery life will depend on the games you play. For instance, the battery will last approximately 5.5 hours for The Legend of Zelda: Breath of the Wild (games sold separately)\r\nModel number HAC 001(01)',500.00,'_bIf-q2GlbLT_cUI1dNpnet_.jpg','Available','5'),(2,'Adidas Originals Trefoil Hoody',' adidas originals\r\n- Best for lifestyle\r\n- adidas brand logo pullover hoodie',70.00,'F_IQj-oYiyNb9b4wcg2rVEk4.jpg','Available','5'),(3,'Timberland','Solid tone high top leather boots\r\n- Waterproof\r\n- Cow leather upper\r\n- Cow leather inner\r\n- Rubber outsole\r\n- Heel height: 4cm',20.00,'NisO1zbBN4oTr2bbcPKIzyMO.jpg','Available','5'),(4,'Adidas Originals PE Rolltop Backpack',' adidas originals\r\n- Best for lifestyle\r\n- Polyester',90.00,'adidas-6709-4779111-1.jpg','Available','5'),(5,'PUMA Men\'s Lifestyle Brief 1 Pack 907404','Your must-have daily essentials from PUMA.\r\n\r\nMinimalistic design\r\nSoft, breathable and elasticated materials',999.99,'puma-9984-5163211-1.jpg','Available','5'),(6,'Utility Front Buttoned Dress','Solid shade button front drawstring waist maxi dress\r\n- Unlined\r\n- Shawl lapel neckline\r\n- Regular fit\r\n- Front button and drawstring waist fastening',70.00,'lubna-3181-1100711-2.jpg','Available','5'),(50,'4','4',4.00,'CtdDFT8KJYWvqf1e_b_LRSYh.png','Unavailable','4'),(51,'Icon for shell','icon',122.00,'bm0G5QbT-MVM4wu_8g2_9b7h.png','Available','5'),(52,'Picture','Picture big mode',111.00,'uBLSnlhpYK9w6Wb66qXtgHmC.jpg','Available','5');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(32) NOT NULL AUTO_INCREMENT,
  `username` varchar(54) DEFAULT NULL,
  `password` char(60) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `status` enum('Waiting','Active','Disable','Lock') DEFAULT NULL,
  `date_create` datetime DEFAULT CURRENT_TIMESTAMP,
  `type` enum('admin','user') DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'dinh','$2y$10$iZaWaGyh7hg/7rjYW9bCCOH6olT4MoIvGY75ZTf4mxEbeZdfbmC7i','haiheo','Active','2019-10-25 23:17:44','user'),(4,'khang','$2y$10$JzhpVEUsOUZPvL/OSN9bou3Se0N023qt7ynTnFw7aA658JSMZrqgi','dmm','Active','2019-10-26 23:25:25','admin'),(6,'khang','$2y$10$OGtVh0iXCb7yU83VhAy5qeFikFn8Fx17A08RJCYKRPQaii7/XkN1u','haiheo','Active','2019-10-26 23:47:10','user'),(7,'admin','$2y$10$sVW3pzg0t0kE1nQVSrXPNuDROxj/zAvb5qzYkGyJA0DvpuNQBjtae','haiheo','Active','2019-10-27 00:01:50','admin'),(8,'dinh','$2y$10$FOh4QrNaNScBdINCbOc9XOvx2Ld4ujjqCAnLAPtCv0j45wnHc9KGK','haiheo','Active','2019-10-27 00:02:35','admin'),(9,'dinh1','$2y$10$GAQVvYWpLe7sj4lEHWtLYuqEN7SOpjv0lK2zIgYTHHtvTV9Af2KgK','haiheo','Active','2019-10-27 23:40:27','admin'),(10,NULL,'$2y$10$NIXJC7YtdcqMshcb12t6A.QfJ7lmIdidPw6iKDw50QPWn7706iy2q',NULL,'Active','2019-12-04 16:34:56','user'),(11,'dinh123','$2y$10$gFHx7NHPoYUJmRqT6vuo1Ouidqxs/9yzDsriRTMN/pQkc18kxcuS2','Dinh ku to','Active','2020-03-23 13:11:12','user'),(12,'ccc','$2y$10$lVUNCyDTyPsZuhM64ZA8zu19A58smRJ6GI8YYRhi.VeHO4dDIK7Ie','Dinh ku to','Active','2020-03-23 13:16:07','user'),(13,'dddd','$2y$10$sle3i8jTQKMENfIJAC/FhOMozBYbBFb4sOuCn9qe4Z.o.UFE35Ejm','Dinh ku to','Active','2020-03-23 13:16:27','user'),(14,'dinh123vvvvss','$2y$10$Anjen/Tm7hY0cgiRTnOghu92BV0/V.WQ5F.RwvIVXDa00/mqPA3bC','Dinh ku to','Active','2020-03-23 13:16:34','user'),(15,'dinhpopinder','$2y$10$cvtVDAtkW4ZdMU7b/hSM2OFyUmmcWcu2P06DzgcKmge3p37oAFjFa','Dinh popinder','Active','2020-03-23 13:17:37','user');
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

-- Dump completed on 2020-03-26  8:04:03

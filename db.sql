-- MariaDB dump 10.19  Distrib 10.6.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: user_0bot0
-- ------------------------------------------------------
-- Server version	10.6.12-MariaDB-1:10.6.12+maria~ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users_jambs`
--

DROP TABLE IF EXISTS `users_jambs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_jambs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `jamb` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_jambs`
--

LOCK TABLES `users_jambs` WRITE;
/*!40000 ALTER TABLE `users_jambs` DISABLE KEYS */;
INSERT INTO `users_jambs` VALUES (1,11,'Жрала Триган'),(2,11,'Постоянно пытается наебать папу'),(3,11,'Бухала водку в 13 лет'),(4,11,'Наебенилась в слюни и блевала на 16-ти летие Димы');
/*!40000 ALTER TABLE `users_jambs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_nicks`
--

DROP TABLE IF EXISTS `users_nicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_nicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vk_id` int(11) NOT NULL,
  `nick` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_nicks`
--

LOCK TABLES `users_nicks` WRITE;
/*!40000 ALTER TABLE `users_nicks` DISABLE KEYS */;
INSERT INTO `users_nicks` VALUES (2,340571002,'Серенький'),(3,480935933,'Ангелочек'),(4,514760790,'Лайт'),(5,529527934,'Дочуня'),(6,581965553,'Ледибосс'),(7,598857572,'Орех'),(8,606378890,'Негритёнок'),(9,622735479,'Жена Димы'),(10,623965188,'Блатной'),(11,628749417,'Поля'),(12,659116049,'Валира'),(13,718397645,'Братишка'),(14,740092405,'Маугли'),(15,691762954,'Сайрус'),(16,654954502,'ЖенаКирюши'),(17,559920769,'Убийца наркоманов'),(18,749610825,'Лисёнок'),(19,21577652,'СукабляМегаБОСС'),(20,547909776,'Элька'),(21,566239937,'Пиздюк');
/*!40000 ALTER TABLE `users_nicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_photos`
--

DROP TABLE IF EXISTS `users_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `photo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_photos`
--

LOCK TABLES `users_photos` WRITE;
/*!40000 ALTER TABLE `users_photos` DISABLE KEYS */;
INSERT INTO `users_photos` VALUES (1,11,'216901410_457239056'),(2,3,'216901410_457239054'),(3,3,'216901410_457239055'),(4,7,'216901410_457239053'),(5,7,'216901410_457239050'),(6,31,'216901410_457239052'),(7,12,'216901410_457239051'),(8,12,'216901410_457239050'),(9,22,'216901410_457239060'),(10,22,'216901410_457239061'),(11,31,'216901410_457239062'),(12,12,'216901410_457239063'),(13,7,'216901410_457239063'),(14,7,'216901410_457239064'),(15,12,'216901410_457239064'),(16,12,'216901410_457239065'),(17,11,'216901410_457239066'),(18,11,'216901410_457239067'),(19,8,'216901410_457239068'),(20,17,'216901410_457239069');
/*!40000 ALTER TABLE `users_photos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-17  3:23:13

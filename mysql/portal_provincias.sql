-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: portal
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.13-MariaDB

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
-- Table structure for table `provincias`
--

DROP TABLE IF EXISTS `provincias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provincias` (
  `idProvincias` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`idProvincias`)
) ENGINE=InnoDB AUTO_INCREMENT=319 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincias`
--

LOCK TABLES `provincias` WRITE;
/*!40000 ALTER TABLE `provincias` DISABLE KEYS */;
INSERT INTO `provincias` VALUES (266,'No importa'),(267,'Alava'),(268,'Albacete'),(269,'Alicante'),(270,'Almeria'),(271,'Asturias'),(272,'Avila'),(273,'Badajoz'),(274,'Barcelona'),(275,'Burgos'),(276,'Caceres'),(277,'Cadiz'),(278,'Cantabria'),(279,'Castellon'),(280,'Ceuta'),(281,'Ciudad Real'),(282,'Cordoba'),(283,'Cuenca'),(284,'Girona'),(285,'Las Palmas'),(286,'Granada'),(287,'Guadalajara'),(288,'Guipuzcua'),(289,'Huelva'),(290,'Huesca'),(291,'Illes Balears'),(292,'Jaen'),(293,'A Coruña'),(294,'La Rioja'),(295,'Leon'),(296,'Lleida'),(297,'Lugo'),(298,'Madrid'),(299,'Malaga'),(300,'Melilla'),(301,'Murcia'),(302,'Navarra'),(303,'Ourense'),(304,'Palencia'),(305,'Pontevedra'),(306,'Salamanca'),(307,'Segovia'),(308,'Sevilla'),(309,'Soria'),(310,'Tarragona'),(311,'Santa Cruz de Tenerife'),(312,'Teruel'),(313,'Toledo'),(314,'Valencia'),(315,'Valladolid'),(316,'Vizcaya'),(317,'Zamora'),(318,'Zaragoza');
/*!40000 ALTER TABLE `provincias` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-19 23:35:49

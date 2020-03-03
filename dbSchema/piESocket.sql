-- MySQL dump 10.17  Distrib 10.3.22-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: openNetworkHMI_DB_pi
-- ------------------------------------------------------
-- Server version	10.3.22-MariaDB-1:10.3.22+maria~stretch

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
-- Current Database: `openNetworkHMI_DB_pi`
--

/*!40000 DROP DATABASE IF EXISTS `openNetworkHMI_DB_pi`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `openNetworkHMI_DB_pi` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `openNetworkHMI_DB_pi`;

--
-- Table structure for table `alarms_definition`
--

DROP TABLE IF EXISTS `alarms_definition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarms_definition` (
  `adid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Alarm definition identifier',
  `adtid` int(10) unsigned NOT NULL COMMENT 'Tag identifier',
  `adPriority` tinyint(3) unsigned NOT NULL COMMENT 'Alarm priority',
  `adMessage` text NOT NULL COMMENT 'Alarm message',
  `adTrigger` int(10) unsigned NOT NULL COMMENT 'Alarm trigger type',
  `adTriggerB` tinyint(1) NOT NULL COMMENT 'Tag binary value that triggers alarm',
  `adTriggerN` bigint(20) NOT NULL COMMENT 'Tag numeric value that triggers alarm',
  `adTriggerR` float NOT NULL COMMENT 'Tag real value that triggers alarm',
  `adAutoAck` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Alarm automatic acknowledgment',
  `adActive` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Alarm is active',
  `adPending` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Alarm is pending',
  `adFeedbackNotACK` int(10) unsigned DEFAULT NULL COMMENT 'Tag informs controller that alarm is not acknowledgment',
  `adHWAck` int(10) unsigned DEFAULT NULL COMMENT 'Tag HW alarm acknowledgment',
  `adEnable` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable alarm definition',
  PRIMARY KEY (`adid`),
  UNIQUE KEY `adtid` (`adtid`),
  KEY `adTrigger` (`adTrigger`),
  KEY `adFeedbackNotACK` (`adFeedbackNotACK`),
  KEY `adHWAck` (`adHWAck`),
  CONSTRAINT `alarms_definition_ibfk_1` FOREIGN KEY (`adtid`) REFERENCES `tags` (`tid`),
  CONSTRAINT `alarms_definition_ibfk_2` FOREIGN KEY (`adTrigger`) REFERENCES `alarms_triggers` (`atid`),
  CONSTRAINT `alarms_definition_ibfk_3` FOREIGN KEY (`adFeedbackNotACK`) REFERENCES `tags` (`tid`),
  CONSTRAINT `alarms_definition_ibfk_4` FOREIGN KEY (`adHWAck`) REFERENCES `tags` (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alarms_definition`
--

LOCK TABLES `alarms_definition` WRITE;
/*!40000 ALTER TABLE `alarms_definition` DISABLE KEYS */;
INSERT INTO `alarms_definition` VALUES (1,17,1,'Socket 1 triger too fast',1,1,0,0,0,0,0,9,NULL,1),(2,18,1,'Socket 2 triger too fast',1,1,0,0,0,0,0,10,NULL,1),(3,19,1,'Socket 3 triger too fast',1,1,0,0,0,0,0,11,NULL,1),(4,20,1,'Socket 4 triger too fast',1,1,0,0,0,0,0,12,NULL,1);
/*!40000 ALTER TABLE `alarms_definition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alarms_history`
--

DROP TABLE IF EXISTS `alarms_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarms_history` (
  `ahid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Alarm history identifier',
  `ahadid` int(10) unsigned NOT NULL COMMENT 'Alarm definition identifier',
  `ah_onTimestamp` timestamp NULL DEFAULT NULL COMMENT 'Alarm appearance timestamp',
  `ah_offTimestamp` timestamp NULL DEFAULT NULL COMMENT 'Alarm disappear timestamp',
  `ah_ackTimestamp` timestamp NULL DEFAULT NULL COMMENT 'Alarm acknowledgment timestamp',
  PRIMARY KEY (`ahid`),
  KEY `ahadid` (`ahadid`),
  CONSTRAINT `alarms_history_ibfk_1` FOREIGN KEY (`ahadid`) REFERENCES `alarms_definition` (`adid`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alarms_history`
--

LOCK TABLES `alarms_history` WRITE;
/*!40000 ALTER TABLE `alarms_history` DISABLE KEYS */;
INSERT INTO `alarms_history` VALUES (84,2,'2020-02-29 15:29:00','2020-02-29 15:29:05','2020-02-29 15:29:46'),(85,3,'2020-02-29 15:30:02','2020-02-29 15:30:07','2020-02-29 15:30:31'),(86,4,'2020-02-29 15:31:01','2020-02-29 15:31:06','2020-02-29 15:31:18'),(87,2,'2020-02-29 15:38:59','2020-02-29 15:39:04','2020-02-29 15:39:15'),(88,2,'2020-02-29 16:02:06','2020-02-29 16:02:11','2020-02-29 16:02:17'),(89,3,'2020-02-29 16:16:42','2020-02-29 16:16:48','2020-02-29 16:16:50'),(90,2,'2020-02-29 16:28:16','2020-02-29 16:28:21','2020-02-29 16:28:24'),(91,3,'2020-02-29 16:35:34','2020-02-29 16:35:39','2020-02-29 16:35:47'),(92,1,'2020-02-29 16:36:46','2020-02-29 16:36:51','2020-02-29 16:36:54');
/*!40000 ALTER TABLE `alarms_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alarms_pending`
--

DROP TABLE IF EXISTS `alarms_pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarms_pending` (
  `apid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Pending alarm identifier',
  `apadid` int(10) unsigned NOT NULL COMMENT 'Alarm definition identifier',
  `ap_active` tinyint(1) NOT NULL COMMENT 'Alarm is active',
  `ap_ack` tinyint(1) NOT NULL COMMENT 'Alarm is acknowledgment',
  `ap_onTimestamp` timestamp NULL DEFAULT NULL COMMENT 'Alarm appearance timestamp',
  `ap_offTimestamp` timestamp NULL DEFAULT NULL COMMENT 'Alarm disappear timestamp',
  `ap_ackTimestamp` timestamp NULL DEFAULT NULL COMMENT 'Alarm acknowledgment timestamp',
  PRIMARY KEY (`apid`),
  UNIQUE KEY `apadid` (`apadid`) USING BTREE,
  CONSTRAINT `alarms_pending_ibfk_1` FOREIGN KEY (`apadid`) REFERENCES `alarms_definition` (`adid`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alarms_pending`
--

LOCK TABLES `alarms_pending` WRITE;
/*!40000 ALTER TABLE `alarms_pending` DISABLE KEYS */;
/*!40000 ALTER TABLE `alarms_pending` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr1_alarms_pending` AFTER INSERT ON `alarms_pending` FOR EACH ROW UPDATE alarms_definition SET adPending = 1, adActive = 1 WHERE adid = NEW.apadid */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr4_alarms_pending` AFTER UPDATE ON `alarms_pending` FOR EACH ROW IF OLD.ap_active <> NEW.ap_active THEN
BEGIN
UPDATE alarms_definition SET adActive = NEW.ap_active WHERE adid = NEW.apadid;
END;
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr2_alarms_pending` AFTER DELETE ON `alarms_pending`
 FOR EACH ROW UPDATE alarms_definition SET adPending = 0, adActive = 0 WHERE adid = OLD.apadid */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr3_alarms_pending` AFTER DELETE ON `alarms_pending` FOR EACH ROW INSERT INTO alarms_history (ahid, ahadid, ah_onTimestamp, ah_offTimestamp, ah_ackTimestamp) VALUES( NULL, OLD.apadid, OLD.ap_onTimestamp, OLD.ap_offTimestamp, OLD.ap_ackTimestamp) */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `alarms_triggers`
--

DROP TABLE IF EXISTS `alarms_triggers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alarms_triggers` (
  `atid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Alarm trigger identifier',
  `atName` varchar(20) NOT NULL COMMENT 'Alarm trigger name',
  PRIMARY KEY (`atid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alarms_triggers`
--

LOCK TABLES `alarms_triggers` WRITE;
/*!40000 ALTER TABLE `alarms_triggers` DISABLE KEYS */;
INSERT INTO `alarms_triggers` VALUES (1,'BIN'),(2,'Tag>value'),(3,'Tag<value'),(4,'Tag>=value'),(5,'Tag<=value'),(6,'Tag=value'),(7,'Tag!=value');
/*!40000 ALTER TABLE `alarms_triggers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_users`
--

DROP TABLE IF EXISTS `app_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(254) NOT NULL,
  `userRole` varchar(20) NOT NULL DEFAULT 'ROLE_USER',
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C2502824F85E0677` (`username`),
  UNIQUE KEY `UNIQ_C2502824E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_users`
--

LOCK TABLES `app_users` WRITE;
/*!40000 ALTER TABLE `app_users` DISABLE KEYS */;
INSERT INTO `app_users` VALUES (1,'admin','$argon2id$v=19$m=65536,t=4,p=1$SmuocW3QX/K37D6kd65Vrg$gSQR3S2rdRHwoamkxI0VYi9nSP95eEoQtdVD/246gMI','admin@localhost.lh5','ROLE_ADMIN',1);
/*!40000 ALTER TABLE `app_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuration` (
  `cName` varchar(40) NOT NULL,
  `cValue` varchar(200) NOT NULL,
  PRIMARY KEY (`cName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuration`
--

LOCK TABLES `configuration` WRITE;
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;
INSERT INTO `configuration` VALUES ('ackAccessRole','ROLE_GUEST'),('alarmingUpdateInterval','100'),('connectionDriver','SHM'),('modbusIP','192.168.56.102'),('modbusPollingInterval','20'),('modbusPort','502'),('modbusRegCount','20'),('modbusSlaveID','15'),('processUpdateInterval','100'),('scriptSystemExecuteScript','php /home/virtual/Dokumenty/openNetworkHMI/PI_SOCKET/openNetworkHMI/openNetworkHMI_web/bin/console app:run-script'),('scriptSystemUpdateInterval','100'),('serverAppPath','/home/virtual/Dokumenty/openNetworkHMI/PI_SOCKET/openNetworkHMI/openNetworkHMI_service/build/app/'),('serverRestart','0'),('shmSegmentName','piESocket_SHM'),('socketMaxConn','10'),('socketPort','8080'),('tagLoggerUpdateInterval','100'),('userScriptsPath','/home/virtual/Dokumenty/openNetworkHMI/PI_SOCKET/openNetworkHMI/userScripts/'),('webAppPath','/home/virtual/Dokumenty/openNetworkHMI/PI_SOCKET/openNetworkHMI/openNetworkHMI_web/');
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_BIT_1`
--

DROP TABLE IF EXISTS `log_BIT_1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_BIT_1` (
  `lbtid` int(11) unsigned NOT NULL COMMENT 'Tag identifier',
  `lbValue` tinyint(1) NOT NULL COMMENT 'Tag value',
  `lbTimeStamp` timestamp(3) NOT NULL DEFAULT current_timestamp(3) ON UPDATE current_timestamp(3) COMMENT 'Tag value timestamp',
  PRIMARY KEY (`lbTimeStamp`),
  KEY `lbtid` (`lbtid`) USING BTREE,
  CONSTRAINT `log_BIT_1_ibfk_1` FOREIGN KEY (`lbtid`) REFERENCES `tags` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_BIT_1`
--

LOCK TABLES `log_BIT_1` WRITE;
/*!40000 ALTER TABLE `log_BIT_1` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_BIT_1` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`admin`@`localhost`*/ /*!50003 TRIGGER `tr1_log_BIT_1` AFTER INSERT ON `log_BIT_1` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.lbTimeStamp, ltLastValue = CAST(NEW.lbValue AS CHAR(50)) WHERE lttid = NEW.lbtid */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `log_BIT_2`
--

DROP TABLE IF EXISTS `log_BIT_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_BIT_2` (
  `lbtid` int(11) unsigned NOT NULL COMMENT 'Tag identifier',
  `lbValue` tinyint(1) NOT NULL COMMENT 'Tag value',
  `lbTimeStamp` timestamp(3) NOT NULL DEFAULT current_timestamp(3) ON UPDATE current_timestamp(3) COMMENT 'Tag value timestamp',
  PRIMARY KEY (`lbTimeStamp`),
  KEY `lbtid` (`lbtid`) USING BTREE,
  CONSTRAINT `log_BIT_2_ibfk_1` FOREIGN KEY (`lbtid`) REFERENCES `tags` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_BIT_2`
--

LOCK TABLES `log_BIT_2` WRITE;
/*!40000 ALTER TABLE `log_BIT_2` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_BIT_2` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`admin`@`localhost`*/ /*!50003 TRIGGER `tr1_log_BIT_2` AFTER INSERT ON `log_BIT_2` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.lbTimeStamp, ltLastValue = CAST(NEW.lbValue AS CHAR(50)) WHERE lttid = NEW.lbtid */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `log_BIT_3`
--

DROP TABLE IF EXISTS `log_BIT_3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_BIT_3` (
  `lbtid` int(11) unsigned NOT NULL COMMENT 'Tag identifier',
  `lbValue` tinyint(1) NOT NULL COMMENT 'Tag value',
  `lbTimeStamp` timestamp(3) NOT NULL DEFAULT current_timestamp(3) ON UPDATE current_timestamp(3) COMMENT 'Tag value timestamp',
  PRIMARY KEY (`lbTimeStamp`),
  KEY `lbtid` (`lbtid`) USING BTREE,
  CONSTRAINT `log_BIT_3_ibfk_1` FOREIGN KEY (`lbtid`) REFERENCES `tags` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_BIT_3`
--

LOCK TABLES `log_BIT_3` WRITE;
/*!40000 ALTER TABLE `log_BIT_3` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_BIT_3` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`admin`@`localhost`*/ /*!50003 TRIGGER `tr1_log_BIT_3` AFTER INSERT ON `log_BIT_3` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.lbTimeStamp, ltLastValue = CAST(NEW.lbValue AS CHAR(50)) WHERE lttid = NEW.lbtid */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `log_BIT_4`
--

DROP TABLE IF EXISTS `log_BIT_4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_BIT_4` (
  `lbtid` int(11) unsigned NOT NULL COMMENT 'Tag identifier',
  `lbValue` tinyint(1) NOT NULL COMMENT 'Tag value',
  `lbTimeStamp` timestamp(3) NOT NULL DEFAULT current_timestamp(3) ON UPDATE current_timestamp(3) COMMENT 'Tag value timestamp',
  PRIMARY KEY (`lbTimeStamp`),
  KEY `lbtid` (`lbtid`) USING BTREE,
  CONSTRAINT `log_BIT_4_ibfk_1` FOREIGN KEY (`lbtid`) REFERENCES `tags` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_BIT_4`
--

LOCK TABLES `log_BIT_4` WRITE;
/*!40000 ALTER TABLE `log_BIT_4` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_BIT_4` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`admin`@`localhost`*/ /*!50003 TRIGGER `tr1_log_BIT_4` AFTER INSERT ON `log_BIT_4` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.lbTimeStamp, ltLastValue = CAST(NEW.lbValue AS CHAR(50)) WHERE lttid = NEW.lbtid */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `log_intervals`
--

DROP TABLE IF EXISTS `log_intervals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_intervals` (
  `liid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Interval identifier',
  `liName` varchar(20) NOT NULL COMMENT 'Interval name',
  PRIMARY KEY (`liid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_intervals`
--

LOCK TABLES `log_intervals` WRITE;
/*!40000 ALTER TABLE `log_intervals` DISABLE KEYS */;
INSERT INTO `log_intervals` VALUES (1,'100ms'),(2,'200ms'),(3,'500ms'),(4,'1s'),(5,'Xs'),(6,'On change');
/*!40000 ALTER TABLE `log_intervals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_tags`
--

DROP TABLE IF EXISTS `log_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_tags` (
  `ltid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Logger identifier',
  `lttid` int(10) unsigned NOT NULL COMMENT 'Tag identifier',
  `ltInterval` int(10) unsigned NOT NULL COMMENT 'Interval identifier',
  `ltIntervalS` int(10) unsigned NOT NULL COMMENT 'Interval seconds',
  `ltLastUPD` timestamp(3) NULL DEFAULT NULL COMMENT 'Last log time',
  `ltLastValue` varchar(50) NOT NULL DEFAULT '0' COMMENT 'Tag last value',
  `ltEnable` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable logging',
  PRIMARY KEY (`ltid`),
  UNIQUE KEY `lttid` (`lttid`) USING BTREE,
  KEY `ltInterval` (`ltInterval`),
  CONSTRAINT `log_tags_ibfk_1` FOREIGN KEY (`lttid`) REFERENCES `tags` (`tid`),
  CONSTRAINT `log_tags_ibfk_2` FOREIGN KEY (`ltInterval`) REFERENCES `log_intervals` (`liid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_tags`
--

LOCK TABLES `log_tags` WRITE;
/*!40000 ALTER TABLE `log_tags` DISABLE KEYS */;
INSERT INTO `log_tags` VALUES (1,13,6,0,NULL,'0',0),(2,14,6,0,NULL,'0',0),(3,15,6,0,NULL,'0',0),(4,16,6,0,NULL,'0',0);
/*!40000 ALTER TABLE `log_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scripts`
--

DROP TABLE IF EXISTS `scripts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scripts` (
  `scid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Script identifier',
  `scTagId` int(10) unsigned NOT NULL COMMENT 'Script trigger tag',
  `scName` varchar(50) NOT NULL COMMENT 'Script name (name.sh)',
  `scRun` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Script run flag',
  `scLock` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Script lock flag',
  `scFeedbackRun` int(10) unsigned DEFAULT NULL COMMENT 'Tag informs controller that script is running',
  `scEnable` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable script',
  PRIMARY KEY (`scid`),
  UNIQUE KEY `scTagId` (`scTagId`) USING BTREE,
  UNIQUE KEY `scName` (`scName`) USING BTREE,
  KEY `scFeedbackRun` (`scFeedbackRun`),
  CONSTRAINT `scripts_ibfk_1` FOREIGN KEY (`scTagId`) REFERENCES `tags` (`tid`),
  CONSTRAINT `scripts_ibfk_2` FOREIGN KEY (`scFeedbackRun`) REFERENCES `tags` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scripts`
--

LOCK TABLES `scripts` WRITE;
/*!40000 ALTER TABLE `scripts` DISABLE KEYS */;
/*!40000 ALTER TABLE `scripts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_areas`
--

DROP TABLE IF EXISTS `tag_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag_areas` (
  `taid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taName` varchar(20) NOT NULL COMMENT 'Tag area name',
  `taPrefix` varchar(5) NOT NULL COMMENT 'Tag area name prefix',
  PRIMARY KEY (`taid`),
  UNIQUE KEY `taName` (`taName`),
  UNIQUE KEY `taPrefix` (`taPrefix`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_areas`
--

LOCK TABLES `tag_areas` WRITE;
/*!40000 ALTER TABLE `tag_areas` DISABLE KEYS */;
INSERT INTO `tag_areas` VALUES (1,'Input','I'),(2,'Output','Q'),(3,'Memory','M');
/*!40000 ALTER TABLE `tag_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag_types`
--

DROP TABLE IF EXISTS `tag_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag_types` (
  `ttid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ttName` varchar(20) NOT NULL COMMENT 'Tag type name',
  PRIMARY KEY (`ttid`),
  UNIQUE KEY `ttName` (`ttName`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag_types`
--

LOCK TABLES `tag_types` WRITE;
/*!40000 ALTER TABLE `tag_types` DISABLE KEYS */;
INSERT INTO `tag_types` VALUES (1,'Bit'),(2,'Byte'),(4,'DWord'),(5,'INT'),(6,'REAL'),(3,'Word');
/*!40000 ALTER TABLE `tag_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tName` varchar(50) NOT NULL COMMENT 'Tag name',
  `tType` int(10) unsigned NOT NULL COMMENT 'Tag data type',
  `tArea` int(10) unsigned NOT NULL COMMENT 'Tag data area',
  `tByteAddress` int(10) unsigned NOT NULL COMMENT 'Tag byte number',
  `tBitAddress` tinyint(3) unsigned NOT NULL COMMENT 'Tag bit number',
  `tReadAccess` varchar(20) NOT NULL DEFAULT 'ROLE_USER' COMMENT 'Read access role',
  `tWriteAccess` varchar(20) NOT NULL DEFAULT 'ROLE_USER' COMMENT 'Write access role',
  PRIMARY KEY (`tid`),
  UNIQUE KEY `tName` (`tName`),
  KEY `tType` (`tType`),
  KEY `tArea` (`tArea`),
  CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`tType`) REFERENCES `tag_types` (`ttid`),
  CONSTRAINT `tags_ibfk_2` FOREIGN KEY (`tArea`) REFERENCES `tag_areas` (`taid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'S1Trigger',1,3,10,0,'ROLE_GUEST','ROLE_GUEST'),(2,'S2Trigger',1,3,10,1,'ROLE_GUEST','ROLE_GUEST'),(3,'S3Trigger',1,3,10,2,'ROLE_GUEST','ROLE_GUEST'),(4,'S4Trigger',1,3,10,3,'ROLE_GUEST','ROLE_GUEST'),(5,'S1TriggerLock',1,3,11,0,'ROLE_GUEST','ROLE_ADMIN'),(6,'S2TriggerLock',1,3,11,1,'ROLE_GUEST','ROLE_ADMIN'),(7,'S3TriggerLock',1,3,11,2,'ROLE_GUEST','ROLE_ADMIN'),(8,'S4TriggerLock',1,3,11,3,'ROLE_GUEST','ROLE_ADMIN'),(9,'S1NotAck',1,3,12,0,'ROLE_GUEST','ROLE_ADMIN'),(10,'S2NotAck',1,3,12,1,'ROLE_GUEST','ROLE_ADMIN'),(11,'S3NotAck',1,3,12,2,'ROLE_GUEST','ROLE_ADMIN'),(12,'S4NotAck',1,3,12,3,'ROLE_GUEST','ROLE_ADMIN'),(13,'S1Out',1,2,10,0,'ROLE_GUEST','ROLE_ADMIN'),(14,'S2Out',1,2,10,1,'ROLE_GUEST','ROLE_ADMIN'),(15,'S3Out',1,2,10,2,'ROLE_GUEST','ROLE_ADMIN'),(16,'S4Out',1,2,10,3,'ROLE_GUEST','ROLE_ADMIN'),(17,'S1Alarm',1,2,11,0,'ROLE_GUEST','ROLE_ADMIN'),(18,'S2Alarm',1,2,11,1,'ROLE_GUEST','ROLE_ADMIN'),(19,'S3Alarm',1,2,11,2,'ROLE_GUEST','ROLE_ADMIN'),(20,'S4Alarm',1,2,11,3,'ROLE_GUEST','ROLE_ADMIN'),(21,'S1Locked',1,2,12,0,'ROLE_GUEST','ROLE_ADMIN'),(22,'S2Locked',1,2,12,1,'ROLE_GUEST','ROLE_ADMIN'),(23,'S3Locked',1,2,12,2,'ROLE_GUEST','ROLE_ADMIN'),(24,'S4Locked',1,2,12,3,'ROLE_GUEST','ROLE_ADMIN');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-29 17:50:49

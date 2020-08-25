-- MySQL dump 10.17  Distrib 10.3.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: openNetworkHMI_DB
-- ------------------------------------------------------
-- Server version	10.3.18-MariaDB-1:10.3.18+maria~stretch

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
-- Current Database: `openNetworkHMI_DB`
--

/*!40000 DROP DATABASE IF EXISTS `openNetworkHMI_DB`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `openNetworkHMI_DB` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `openNetworkHMI_DB`;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=CURRENT_USER()*/ /*!50003 TRIGGER `tr1_alarms_pending` AFTER INSERT ON `alarms_pending` FOR EACH ROW UPDATE alarms_definition SET adPending = 1, adActive = 1 WHERE adid = NEW.apadid */;;
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
/*!50003 CREATE*/ /*!50017 DEFINER=CURRENT_USER()*/ /*!50003 TRIGGER `tr4_alarms_pending` AFTER UPDATE ON `alarms_pending` FOR EACH ROW IF OLD.ap_active <> NEW.ap_active THEN
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
/*!50003 CREATE*/ /*!50017 DEFINER=CURRENT_USER()*/ /*!50003 TRIGGER `tr2_alarms_pending` AFTER DELETE ON `alarms_pending`
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
/*!50003 CREATE*/ /*!50017 DEFINER=CURRENT_USER()*/ /*!50003 TRIGGER `tr3_alarms_pending` AFTER DELETE ON `alarms_pending` FOR EACH ROW INSERT INTO alarms_history (ahid, ahadid, ah_onTimestamp, ah_offTimestamp, ah_ackTimestamp) VALUES( NULL, OLD.apadid, OLD.ap_onTimestamp, OLD.ap_offTimestamp, OLD.ap_ackTimestamp) */;;
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
INSERT INTO `configuration` VALUES ('ackAccessRole','ROLE_USER'),('alarmingUpdateInterval','100'),('processUpdateInterval','100'),('scriptSystemExecuteScript','php /home/virtual/Dokumenty/openNetworkHMI/APP/SRC/openNetworkHMI_web/bin/console app:run-script'),('scriptSystemUpdateInterval','100'),('serverAppPath','/home/virtual/onh/APP/SRC/tests/bin/onh/'),('serverRestart','0'),('socketMaxConn','10'),('socketPort','8080'),('tagLoggerUpdateInterval','100'),('userScriptsPath','/home/virtual/onh/APP/SRC/tests/scripts/'),('webAppPath','/home/virtual/Dokumenty/openNetworkHMI/APP/SRC/openNetworkHMI_web/');
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_modbus`
--

DROP TABLE IF EXISTS `driver_modbus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driver_modbus` (
  `dmId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Modbus driver identifier',
  `dmMode` tinyint(3) unsigned NOT NULL COMMENT 'Modbus mode (0 - RTU, 1 - TCP)',
  `dmPollingInterval` int(10) unsigned NOT NULL COMMENT 'Modbus driver polling interval (ms)',
  `dmRegCount` int(10) unsigned NOT NULL COMMENT 'Modbus driver register count',
  `dmRTU_baud` int(10) unsigned NOT NULL DEFAULT 57600 COMMENT 'Modbus RTU baud rate',
  `dmRTU_dataBit` tinyint(3) unsigned NOT NULL DEFAULT 8 COMMENT 'Modbus RTU data bits',
  `dmRTU_parity` char(1) NOT NULL DEFAULT 'N' COMMENT 'Modbus RTU parity (N - none, E - even, O - odd)',
  `dmRTU_port` varchar(200) NOT NULL DEFAULT '/dev/ttyACM1' COMMENT 'Modbus RTU port',
  `dmRTU_stopBit` smallint(5) unsigned NOT NULL DEFAULT 1 COMMENT 'Modbus RTU stop bit',
  `dmSlaveID` int(10) unsigned NOT NULL COMMENT 'Modbus slave ID',
  `dmTCP_addr` varchar(15) NOT NULL DEFAULT '127.0.0.1' COMMENT 'Modbus TCP IP address',
  `dmTCP_port` int(10) unsigned NOT NULL DEFAULT 502 COMMENT 'Modbus TCP port number',
  PRIMARY KEY (`dmId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `driver_shm`
--

DROP TABLE IF EXISTS `driver_shm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driver_shm` (
  `dsId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'SHM driver identifier',
  `dsSegment` varchar(200) NOT NULL COMMENT 'SHM driver segment name',
  PRIMARY KEY (`dsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `driver_connections`
--

DROP TABLE IF EXISTS `driver_connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `driver_connections` (
  `dcId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Driver connection identifier',
  `dcName` varchar(100) NOT NULL COMMENT 'Driver connection name',
  `dcType` smallint(5) unsigned NOT NULL COMMENT 'Driver connection type (0 - SHM, 1 - Modbus)',
  `dcConfigModbus` int(10) unsigned DEFAULT NULL COMMENT 'Driver modbus configuration identifier',
  `dcConfigSHM` int(10) unsigned DEFAULT NULL COMMENT 'Driver SHM configuration identifier ',
  PRIMARY KEY (`dcId`),
  UNIQUE KEY `dcConfigSHM` (`dcConfigSHM`),
  UNIQUE KEY `dcConfigModbus` (`dcConfigModbus`),
  CONSTRAINT `driver_connections_ibfk_1` FOREIGN KEY (`dcConfigModbus`) REFERENCES `driver_modbus` (`dmId`),
  CONSTRAINT `driver_connections_ibfk_2` FOREIGN KEY (`dcConfigSHM`) REFERENCES `driver_shm` (`dsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `tConnId` int(10) unsigned NOT NULL COMMENT 'Tag driver connection identifier',
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
  KEY `tConnId` (`tConnId`),
  CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`tType`) REFERENCES `tag_types` (`ttid`),
  CONSTRAINT `tags_ibfk_2` FOREIGN KEY (`tArea`) REFERENCES `tag_areas` (`taid`),
  CONSTRAINT `tags_ibfk_3` FOREIGN KEY (`tConnId`) REFERENCES `driver_connections` (`dcId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-19 10:55:39

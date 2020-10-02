<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\TagLogger;
use App\Entity\Paginator;
use App\Entity\AppException;

/**
 * Class to read/write Tags loggers
 *
 * @author Mateusz Mirosławski
 */
class TagLoggerMapper
{
    private Connection $dbConn;
    
    public function __construct(Connection $connection)
    {
        $this->dbConn = $connection;
    }
    
    /**
     * Get Tag loggers
     *
     * @param int $area Tag logger area (0 - all, 1 - input, 2 - output, 3 - memory)
     * @param int $sort Tag logger sorting (0 - ID, 1 - tag name, 2 - interval, 3 - last update, 4 - enabled flag)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     * @param Paginator $paginator Paginator object
     * @return array Array with Tag loggers
     */
    public function getLoggers(int $area = 0, int $sort = 0, int $sortDESC = 0, Paginator $paginator = null): array
    {
        // Basic query
        $sql = 'SELECT * FROM log_tags lt, tags t';
        $sql .= ' WHERE lt.lttid=t.tid';
        
        // Area
        if ($area > 0) {
            $sql .= ' AND t.tArea = ?';
        }
        
        // Order direction
        $oDirection = ($sortDESC == 1) ? ('DESC') : ('ASC');
        
        // Order
        switch ($sort) {
            case 0:
                $sql .= ' ORDER BY lt.ltid ' . $oDirection;
                break;
            case 1:
                $sql .= ' ORDER BY t.tName ' . $oDirection;
                break;
            case 2:
                $sql .= ' ORDER BY lt.ltInterval ' . $oDirection;
                break;
            case 3:
                $sql .= ' ORDER BY lt.ltLastUPD ' . $oDirection;
                break;
            case 4:
                $sql .= ' ORDER BY lt.ltEnable ' . $oDirection;
                break;
            default:
                $sql .= ' ORDER BY lt.ltid ' . $oDirection;
        }
        
        // Check paginator
        if (!is_null($paginator)) {
            $sql .= " " . $paginator->getSqlQuery();
        }
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        
        if ($area > 0) {
            $statement->bindValue(1, $area, ParameterType::INTEGER);
        }
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        $ret = array();
        
        foreach ($items as $item) {
            // New tag
            $tag = new Tag();
            $tag->setId($item['tid']);
            $tag->setName($item['tName']);
            $tag->setType($item['tType']);
            $tag->setArea($item['tArea']);
            $tag->setByteAddress($item['tByteAddress']);
            $tag->setBitAddress($item['tBitAddress']);
            $tag->setReadAccess($item['tReadAccess']);
            $tag->setWriteAccess($item['tWriteAccess']);
            
            // Tag logger
            $tagLog = new TagLogger($tag);
            $tagLog->setId($item['ltid']);
            $tagLog->setInterval($item['ltInterval']);
            $tagLog->setIntervalS($item['ltIntervalS']);
            $tagLog->setLastLogTime((($item['ltLastUPD'] == null) ? ('none') : ($item['ltLastUPD'])));
            $tagLog->setLastValue($item['ltLastValue']);
            $tagLog->setEnabled((($item['ltEnable'] == 1) ? (true) : (false)));
            
            // Add to the array
            array_push($ret, $tagLog);
        }
        
        return $ret;
    }
    
    /**
     * Get number of all tag loggers in DB
     *
     * @param int $area Tag area
     * @return int Number of tag loggers in DB
     * @throws Exception
     */
    public function getLoggersCount(int $area = 0): int
    {
        // Base query
        $sql = "SELECT count(*) AS 'cnt' FROM tags t, log_tags lt WHERE lt.lttid=t.tid";
        
        // Area
        if ($area > 0) {
            $sql .= ' AND t.tArea = ?';
        }
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        
        if ($area > 0) {
            $statement->bindValue(1, $area, ParameterType::INTEGER);
        }
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items) || count($items) != 1) {
            throw new Exception("Error during executing count query!");
        }
        
        $item = $items[0];
        
        return $item['cnt'];
    }
    
    /**
     * Get Tag logger data
     *
     * @param int $loggerId Tag identifier
     * @return TagLogger Tag Logger object
     * @throws Exception Logger identifier invalid or Logger not exist
     */
    public function getLogger(int $loggerId): TagLogger
    {
        // Check logger identifier
        TagLogger::checkId($loggerId);
        
        // Basic query
        $sql = 'SELECT * FROM log_tags lt, tags t';
        $sql .= ' WHERE lt.lttid=t.tid';
        $sql .= ' AND lt.ltid = ?';
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $loggerId, ParameterType::INTEGER);
        $statement->execute();
        
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Logger with identifier " . $loggerId . " does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New tag
        $tag = new Tag();
        $tag->setId($item['tid']);
        $tag->setName($item['tName']);
        $tag->setType($item['tType']);
        $tag->setArea($item['tArea']);
        $tag->setByteAddress($item['tByteAddress']);
        $tag->setBitAddress($item['tBitAddress']);
        $tag->setReadAccess($item['tReadAccess']);
        $tag->setWriteAccess($item['tWriteAccess']);

        // Tag logger
        $tagLog = new TagLogger($tag);
        $tagLog->setId($item['ltid']);
        $tagLog->setInterval($item['ltInterval']);
        $tagLog->setIntervalS($item['ltIntervalS']);
        $tagLog->setLastLogTime((($item['ltLastUPD'] == null) ? ('none') : ($item['ltLastUPD'])));
        $tagLog->setLastValue($item['ltLastValue']);
        $tagLog->setEnabled((($item['ltEnable'] == 1) ? (true) : (false)));
        
        return $tagLog;
    }
    
    /**
     * Add Tag logger to the DB
     *
     * @param TagLogger $newLogger Tag logger to add
     */
    public function addLogger(TagLogger $newLogger)
    {
        // Check if Tag logger is valid
        $newLogger->isValid();
                
        // Start transaction
        $this->dbConn->beginTransaction();
        
        $stmt1 = $this->dbConn->prepare('INSERT INTO log_tags (lttid, ltInterval, ltIntervalS) VALUES(?, ?, ?);');
        
        $stmt1->bindValue(1, $newLogger->getTag()->getId(), ParameterType::INTEGER);
        $stmt1->bindValue(2, $newLogger->getInterval(), ParameterType::INTEGER);
        $stmt1->bindValue(3, $newLogger->getIntervalS(), ParameterType::INTEGER);
        
        try {
            if (!$stmt1->execute()) {
                $this->dbConn->rollBack();
                throw new Exception("Error during execute sql add query!");
            }
            
            // Get inserted logger identifier
            $lastID = $this->dbConn->lastInsertId();
            
            // Prepare SQL statements for table and trigger
            $q = $this->prepareTagLoggerSql($newLogger, $lastID);
            
            // Create logger table
            $stmt2 = $this->dbConn->prepare($q['table']);
            if (!$stmt2->execute()) {
                $this->dbConn->rollBack();
                throw new Exception("Error during creating table query!");
            }
            
            // Create trigger
            $stmt3 = $this->dbConn->prepare($q['trigger']);
            if (!$stmt3->execute()) {
                $this->dbConn->rollBack();
                throw new Exception("Error during creating trigger query!");
            }
            
            // Modify DB
            $this->dbConn->commit();
        } catch (UniqueConstraintViolationException $ex) {
            $this->dbConn->rollBack();
            
            throw new AppException(
                "Tag logger with tag: " . $newLogger->getTag()->getName() . " exist in DB!",
                AppException::LOGGER_TAG_EXIST
            );
        }
    }
    
    /**
     * Prepare BIT logger SQL statements for table and trigger
     *
     * @param int $logTagDefID Tag logger definition identifier
     * @return array Array with 'table' and 'trigger' SQL statements
     * @throws Exception
     */
    private function prepareBITsql(int $logTagDefID): array
    {
        // Check ID
        if ($logTagDefID < 1) {
            throw new Exception("Tag logger identifier wrong value!");
        }
        
        // Table SQL
        $qTable = "CREATE TABLE `log_BIT_" . $logTagDefID . "` (";
        $qTable .= "`lbtid` int(11) unsigned NOT NULL COMMENT 'Tag identifier',";
        $qTable .= "`lbValue` tinyint(1) NOT NULL COMMENT 'Tag value',";
        $qTable .= "`lbTimeStamp` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)" .
                    " ON UPDATE CURRENT_TIMESTAMP(3) COMMENT 'Tag value timestamp',";
        $qTable .= "PRIMARY KEY (`lbTimeStamp`),";
        $qTable .= "KEY `lbtid` (`lbtid`) USING BTREE,";
        $qTable .= "CONSTRAINT `log_BIT_" . $logTagDefID . "_ibfk_1` FOREIGN KEY (`lbtid`) REFERENCES `tags` (`tid`)";
        $qTable .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        // Trigger SQL
        $qTrigger = "CREATE TRIGGER `tr1_log_BIT_" . $logTagDefID . "` ";
        $qTrigger .= "AFTER INSERT ON `log_BIT_" . $logTagDefID .
                    "` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.lbTimeStamp, ";
        $qTrigger .= "ltLastValue = CAST(NEW.lbValue AS CHAR(50)) WHERE lttid = NEW.lbtid";
                
        return array(
            'table' => $qTable,
            'trigger' => $qTrigger
        );
    }
    
    /**
     * Prepare BYTE logger SQL statements for table and trigger
     *
     * @param int $logTagDefID Tag logger definition identifier
     * @return array Array with 'table' and 'trigger' SQL statements
     * @throws Exception
     */
    private function prepareBYTEsql(int $logTagDefID): array
    {
        // Check ID
        if ($logTagDefID < 1) {
            throw new Exception("Tag logger identifier wrong value!");
        }
        
        // Table SQL
        $qTable = "CREATE TABLE `log_BYTE_" . $logTagDefID . "` (";
        $qTable .= "`lbytid` int(10) unsigned NOT NULL COMMENT 'Tag identifier',";
        $qTable .= "`lbyValue` tinyint(3) unsigned NOT NULL COMMENT 'Tag value',";
        $qTable .= "`lbyTimeStamp` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)" .
                    " ON UPDATE CURRENT_TIMESTAMP(3) COMMENT 'Tag value timestamp',";
        $qTable .= "PRIMARY KEY (`lbyTimeStamp`),";
        $qTable .= "KEY `lbytid` (`lbytid`),";
        $qTable .= "CONSTRAINT `log_BYTE_" . $logTagDefID . "_ibfk_1` FOREIGN KEY (`lbytid`) REFERENCES `tags` (`tid`)";
        $qTable .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        // Trigger SQL
        $qTrigger = "CREATE TRIGGER `tr1_log_BYTE_" . $logTagDefID . "` ";
        $qTrigger .= "AFTER INSERT ON `log_BYTE_" . $logTagDefID .
                    "` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.lbyTimeStamp, ";
        $qTrigger .= "ltLastValue = CAST(NEW.lbyValue AS CHAR(50)) WHERE lttid = NEW.lbytid;";
                
        return array(
            'table' => $qTable,
            'trigger' => $qTrigger
        );
    }
    
    /**
     * Prepare WORD logger SQL statements for table and trigger
     *
     * @param int $logTagDefID Tag logger definition identifier
     * @return array Array with 'table' and 'trigger' SQL statements
     * @throws Exception
     */
    private function prepareWORDsql(int $logTagDefID): array
    {
        // Check ID
        if ($logTagDefID < 1) {
            throw new Exception("Tag logger identifier wrong value!");
        }
        
        // Table SQL
        $qTable = "CREATE TABLE `log_WORD_" . $logTagDefID . "` (";
        $qTable .= "`lwtid` int(10) unsigned NOT NULL COMMENT 'Tag identifier',";
        $qTable .= "`lwValue` smallint(5) unsigned NOT NULL COMMENT 'Tag value',";
        $qTable .= "`lwTimeStamp` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)" .
                    " ON UPDATE CURRENT_TIMESTAMP(3) COMMENT 'Tag value timestamp',";
        $qTable .= "PRIMARY KEY (`lwTimeStamp`),";
        $qTable .= "KEY `lwtid` (`lwtid`),";
        $qTable .= "CONSTRAINT `log_WORD_" . $logTagDefID . "_ibfk_1` FOREIGN KEY (`lwtid`) REFERENCES `tags` (`tid`)";
        $qTable .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        // Trigger SQL
        $qTrigger = "CREATE TRIGGER `tr1_log_WORD_" . $logTagDefID . "` ";
        $qTrigger .= "AFTER INSERT ON `log_WORD_" . $logTagDefID .
                    "` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.lwTimeStamp, ";
        $qTrigger .= "ltLastValue = CAST(NEW.lwValue AS CHAR(50)) WHERE lttid = NEW.lwtid;";
                
        return array(
            'table' => $qTable,
            'trigger' => $qTrigger
        );
    }
    
    /**
     * Prepare DWORD logger SQL statements for table and trigger
     *
     * @param int $logTagDefID Tag logger definition identifier
     * @return array Array with 'table' and 'trigger' SQL statements
     * @throws Exception
     */
    private function prepareDWORDsql(int $logTagDefID): array
    {
        // Check ID
        if ($logTagDefID < 1) {
            throw new Exception("Tag logger identifier wrong value!");
        }
        
        // Table SQL
        $qTable = "CREATE TABLE `log_DWORD_" . $logTagDefID . "` (";
        $qTable .= "`ldtid` int(10) unsigned NOT NULL COMMENT 'Tag identifier',";
        $qTable .= "`ldValue` int(10) unsigned NOT NULL COMMENT 'Tag value',";
        $qTable .= "`ldTimeStamp` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)" .
                    " ON UPDATE CURRENT_TIMESTAMP(3) COMMENT 'Tag value timestamp',";
        $qTable .= "PRIMARY KEY (`ldTimeStamp`),";
        $qTable .= "KEY `ldtid` (`ldtid`),";
        $qTable .= "CONSTRAINT `log_DWORD_" . $logTagDefID . "_ibfk_1` FOREIGN KEY (`ldtid`) REFERENCES `tags` (`tid`)";
        $qTable .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        // Trigger SQL
        $qTrigger = "CREATE TRIGGER `tr1_log_DWORD_" . $logTagDefID . "` ";
        $qTrigger .= "AFTER INSERT ON `log_DWORD_" . $logTagDefID .
                    "` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.ldTimeStamp, ";
        $qTrigger .= "ltLastValue = CAST(NEW.ldValue AS CHAR(50)) WHERE lttid = NEW.ldtid;";
                
        return array(
            'table' => $qTable,
            'trigger' => $qTrigger
        );
    }
    
    /**
     * Prepare INT logger SQL statements for table and trigger
     *
     * @param int $logTagDefID Tag logger definition identifier
     * @return array Array with 'table' and 'trigger' SQL statements
     * @throws Exception
     */
    private function prepareINTsql(int $logTagDefID): array
    {
        // Check ID
        if ($logTagDefID < 1) {
            throw new Exception("Tag logger identifier wrong value!");
        }
        
        // Table SQL
        $qTable = "CREATE TABLE `log_INT_" . $logTagDefID . "` (";
        $qTable .= "`linttid` int(10) unsigned NOT NULL COMMENT 'Tag identifier',";
        $qTable .= "`lintValue` int(11) NOT NULL COMMENT 'Tag value',";
        $qTable .= "`lintTimeStamp` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)" .
                    " ON UPDATE CURRENT_TIMESTAMP(3) COMMENT 'Tag value timestamp',";
        $qTable .= "PRIMARY KEY (`lintTimeStamp`),";
        $qTable .= "KEY `linttid` (`linttid`),";
        $qTable .= "CONSTRAINT `log_INT_" . $logTagDefID . "_ibfk_1` FOREIGN KEY (`linttid`) REFERENCES `tags` (`tid`)";
        $qTable .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        // Trigger SQL
        $qTrigger = "CREATE TRIGGER `tr1_log_INT_" . $logTagDefID . "` ";
        $qTrigger .= "AFTER INSERT ON `log_INT_" . $logTagDefID .
                    "` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.lintTimeStamp, ";
        $qTrigger .= "ltLastValue = CAST(NEW.lintValue AS CHAR(50)) WHERE lttid = NEW.linttid;";
                
        return array(
            'table' => $qTable,
            'trigger' => $qTrigger
        );
    }
    
    /**
     * Prepare REAL logger SQL statements for table and trigger
     *
     * @param int $logTagDefID Tag logger definition identifier
     * @return array Array with 'table' and 'trigger' SQL statements
     * @throws Exception
     */
    private function prepareREALsql(int $logTagDefID): array
    {
        // Check ID
        if ($logTagDefID < 1) {
            throw new Exception("Tag logger identifier wrong value!");
        }
        
        // Table SQL
        $qTable = "CREATE TABLE `log_REAL_" . $logTagDefID . "` (";
        $qTable .= "`lrtid` int(10) unsigned NOT NULL COMMENT 'Tag identifier',";
        $qTable .= "`lrValue` double NOT NULL COMMENT 'Tag value',";
        $qTable .= "`lrTimeStamp` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)" .
                    " ON UPDATE CURRENT_TIMESTAMP(3) COMMENT 'Tag value timestamp',";
        $qTable .= "PRIMARY KEY (`lrTimeStamp`),";
        $qTable .= "KEY `lrtid` (`lrtid`),";
        $qTable .= "CONSTRAINT `log_REAL_" . $logTagDefID . "_ibfk_1` FOREIGN KEY (`lrtid`) REFERENCES `tags` (`tid`)";
        $qTable .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        // Trigger SQL
        $qTrigger = "CREATE TRIGGER `tr1_log_REAL_" . $logTagDefID . "` ";
        $qTrigger .= "AFTER INSERT ON `log_REAL_" . $logTagDefID .
                    "` FOR EACH ROW UPDATE log_tags SET ltLastUPD = NEW.lrTimeStamp, ";
        $qTrigger .= "ltLastValue = CAST(NEW.lrValue AS CHAR(50)) WHERE lttid = NEW.lrtid;";
                
        return array(
            'table' => $qTable,
            'trigger' => $qTrigger
        );
    }
    
    /**
     * Prepare Tag logger SQL statement for table and trigger
     *
     * @param TagLogger $tagLogger Tag logger to add
     * @param int $logTagDefID Tag logger definition identifier
     * @return array Array with 'table' and 'trigger' SQL statements
     * @throws Exception
     */
    private function prepareTagLoggerSql(TagLogger $tagLogger, int $logTagDefID): array
    {
        $ret = array();
        
        switch ($tagLogger->getTag()->getType()) {
            case TagType::BIT:
                $ret = $this->prepareBITsql($logTagDefID);
                break;
            case TagType::BYTE:
                $ret = $this->prepareBYTEsql($logTagDefID);
                break;
            case TagType::WORD:
                $ret = $this->prepareWORDsql($logTagDefID);
                break;
            case TagType::DWORD:
                $ret = $this->prepareDWORDsql($logTagDefID);
                break;
            case TagType::INT:
                $ret = $this->prepareINTsql($logTagDefID);
                break;
            case TagType::REAL:
                $ret = $this->prepareREALsql($logTagDefID);
                break;
            default:
                throw new Exception("Unknow tag type");
        }
        
        return $ret;
    }
    
    /**
     * Edit Logger
     *
     * @param TagLogger $newLogger New Logger object
     */
    public function editLogger(TagLogger $newLogger)
    {
        // Check if Tag is valid
        $newLogger->isValid(true);
        
        $q = 'UPDATE log_tags SET lttid = ?, ltInterval = ?, ltIntervalS = ? WHERE ltid = ?;';
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newLogger->getTag()->getId(), ParameterType::INTEGER);
        $stmt->bindValue(2, $newLogger->getInterval(), ParameterType::INTEGER);
        $stmt->bindValue(3, $newLogger->getIntervalS(), ParameterType::INTEGER);
        $stmt->bindValue(4, $newLogger->getId(), ParameterType::INTEGER);
        
        try {
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
        } catch (UniqueConstraintViolationException $ex) {
            throw new AppException(
                "Tag logger with tag: " . $newLogger->getTag()->getName() . " exist in DB!",
                AppException::LOGGER_TAG_EXIST
            );
        }
    }
    
    /**
     * Prepare drop log table SQL statement
     *
     * @param TagLogger $tagLogger Tag logger to delete
     * @return string Drop SQL statement
     * @throws Exception
     */
    private function prepareDropSql(TagLogger $tagLogger): string
    {
        $q = "DROP TABLE log_";
        
        switch ($tagLogger->getTag()->getType()) {
            case TagType::BIT:
                $q .= "BIT_";
                break;
            case TagType::BYTE:
                $q .= "BYTE_";
                break;
            case TagType::WORD:
                $q .= "WORD_";
                break;
            case TagType::DWORD:
                $q .= "DWORD_";
                break;
            case TagType::INT:
                $q .= "INT_";
                break;
            case TagType::REAL:
                $q .= "REAL_";
                break;
            default:
                throw new Exception("Unknow tag type");
        }
        
        $q .= $tagLogger->getId();
        
        return $q;
    }
    
    /**
     * Delete Tag logger
     *
     * @param int $loggerId Tag logger identifier
     */
    public function deleteLogger(int $loggerId)
    {
        // Check logger identifier
        TagLogger::checkId($loggerId);
        
        $logger = $this->getLogger($loggerId);
        
        // Start transaction
        $this->dbConn->beginTransaction();
        
        $statement1 = $this->dbConn->prepare('DELETE FROM log_tags WHERE ltid = ?;');
        $statement1->bindValue(1, $loggerId, ParameterType::INTEGER);
                
        if (!$statement1->execute()) {
            $this->dbConn->rollBack();
            throw new Exception("Error during execute delete query!");
        }
        
        $q = $this->prepareDropSql($logger);
        $statement2 = $this->dbConn->prepare($q);
        if (!$statement2->execute()) {
            $this->dbConn->rollBack();
            throw new Exception("Error during execute delete table query!");
        }
        
        // Modify DB
        $this->dbConn->commit();
    }
    
    /**
     * Enable logger
     *
     * @param int $loggerId Tag logger identifier
     * @param bool $en Enable flag
     */
    public function enableLogger(int $loggerId, bool $en = true)
    {
        // Check logger identifier
        TagLogger::checkId($loggerId);
        
        $stmt = $this->dbConn->prepare('UPDATE log_tags SET ltEnable = ? WHERE ltid = ?;');
        
        $stmt->bindValue(1, (($en) ? (1) : (0)), ParameterType::INTEGER);
        $stmt->bindValue(2, $loggerId, ParameterType::INTEGER);
        
        if (!$stmt->execute()) {
            throw new Exception("Error during execute sql add query!");
        }
    }
}

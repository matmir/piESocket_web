<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use App\Entity\Admin\ConfigGeneral;
use App\Service\Admin\BaseConfigMapper;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class to read/write General configuration of the system
 *
 * @author Mateusz Mirosławski
 */
class ConfigGeneralMapper extends BaseConfigMapper {
        
    public function __construct(Connection $connection) {
        
        parent::__construct($connection);
    }
    
    /**
     * Get system configuration
     * 
     * @return ConfigGeneral Object with system configuration
     */
    public function getConfig(): ConfigGeneral {
        
        $statement = $this->dbConn->prepare('SELECT * FROM configuration;');
        $statement->execute();
        $items = $statement->fetchAll();
        
        $cg = new ConfigGeneral();
        
        for ($i=0; $i<count($items); ++$i) {
            
            if ($items[$i]['cName'] == 'alarmingUpdateInterval') {
                
                $cg->setAlarmingUpdateInterval($items[$i]['cValue']);
                
            } else if ($items[$i]['cName'] == 'processUpdateInterval') {
                
                $cg->setProcessUpdateInterval($items[$i]['cValue']);
                
            }else if ($items[$i]['cName'] == 'tagLoggerUpdateInterval') {
                
                $cg->setTagLoggerUpdateInterval($items[$i]['cValue']);
                
            } else if ($items[$i]['cName'] == 'serverAppPath') {
                
                $cg->setServerAppPath($items[$i]['cValue']);
                
            } else if ($items[$i]['cName'] == 'socketMaxConn') {
                
                $cg->setSocketMaxConn($items[$i]['cValue']);
                
            } else if ($items[$i]['cName'] == 'socketPort') {
                
                $cg->setSocketPort($items[$i]['cValue']);
                
            } else if ($items[$i]['cName'] == 'webAppPath') {
                
                $cg->setWebAppPath($items[$i]['cValue']);
                
            } else if ($items[$i]['cName'] == 'scriptSystemExecuteScript') {
                
                $cg->setScriptSystemExecuteScript($items[$i]['cValue']);
                
            } else if ($items[$i]['cName'] == 'scriptSystemUpdateInterval') {
                
                $cg->setScriptSystemUpdateInterval($items[$i]['cValue']);
            } else if ($items[$i]['cName'] == 'userScriptsPath') {
                
                $cg->setUserScriptsPath($items[$i]['cValue']);
                
            } else if ($items[$i]['cName'] == 'ackAccessRole') {
                
                $cg->setAckAccessRole($items[$i]['cValue']);
            }
            
        }
        
        return $cg;
    }
    
    /**
     * Write system configuration to the DB
     * 
     * @param ConfigGeneral $newCFG Configuration object
     */
    public function setConfig(ConfigGeneral $newCFG) {
        
        // Get current configuration
        $currentCFG = $this->getConfig();
        
        $sqls = array();
        $vals = array();
        $sql = '';
        
        // alarmingUpdateInterval
        if ($newCFG->getAlarmingUpdateInterval() <> $currentCFG->getAlarmingUpdateInterval()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'alarmingUpdateInterval';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getAlarmingUpdateInterval());
        }
        
        // processUpdateInterval
        if ($newCFG->getProcessUpdateInterval() <> $currentCFG->getProcessUpdateInterval()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'processUpdateInterval';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getProcessUpdateInterval());
        }
        
        // tagLoggerUpdateInterval
        if ($newCFG->getTagLoggerUpdateInterval() <> $currentCFG->getTagLoggerUpdateInterval()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'tagLoggerUpdateInterval';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getTagLoggerUpdateInterval());
        }
        
        // serverAppPath
        if ($newCFG->getServerAppPath() <> $currentCFG->getServerAppPath()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'serverAppPath';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getServerAppPath());
        }
        
        // webAppPath
        if ($newCFG->getWebAppPath() <> $currentCFG->getWebAppPath()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'webAppPath';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getWebAppPath());
        }
        
        // socketMaxConn
        if ($newCFG->getSocketMaxConn() <> $currentCFG->getSocketMaxConn()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'socketMaxConn';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getSocketMaxConn());
        }
        
        // socketPort
        if ($newCFG->getSocketPort() <> $currentCFG->getSocketPort()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'socketPort';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getSocketPort());
        }
        
        // scriptSystemExecuteScript
        if ($newCFG->getScriptSystemExecuteScript() <> $currentCFG->getScriptSystemExecuteScript()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'scriptSystemExecuteScript';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getScriptSystemExecuteScript());
        }
        
        // scriptSystemUpdateInterval
        if ($newCFG->getScriptSystemUpdateInterval() <> $currentCFG->getScriptSystemUpdateInterval()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'scriptSystemUpdateInterval';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getScriptSystemUpdateInterval());
        }
        
        // userScriptsPath
        if ($newCFG->getUserScriptsPath() <> $currentCFG->getUserScriptsPath()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'userScriptsPath';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getUserScriptsPath());
        }
        
        // ackAccessRole
        if ($newCFG->getAckAccessRole() <> $currentCFG->getAckAccessRole()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'ackAccessRole';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getAckAccessRole());
        }
        
        // Set restart flag
        $this->setServerRestartFlag();
        
        // Update DB
        for ($i=0; $i<count($sqls); ++$i) {
            
            $stmt = $this->dbConn->prepare($sqls[$i]);
        
            $stmt->bindValue(1, $vals[$i], ParameterType::STRING);
        
            $stmt->execute();
            
        }
    }
    
    /**
     * Get web application path
     * 
     * @return string Web application path
     * @throws Exception
     */
    public function getWebAppPath(): string {
        
        $statement = $this->dbConn->prepare("SELECT * FROM configuration WHERE cName = 'webAppPath';");
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Configuration webAppPath does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        
        return $items[0]['cValue'];
    }
    
    /**
     * Get system socket port
     * 
     * @return int System socket port
     * @throws Exception
     */
    public function getSystemSocketPort(): int {
        
        $statement = $this->dbConn->prepare("SELECT * FROM configuration WHERE cName = 'socketPort';");
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Configuration socketPort does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        
        return $items[0]['cValue'];
    }
    
    /**
     * Get server application path
     * 
     * @return string Server application directory
     * @throws Exception
     */
    public function getServerAppPath(): string {
        
        $statement = $this->dbConn->prepare("SELECT * FROM configuration WHERE cName = 'serverAppPath';");
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Configuration serverAppPath does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        
        return $items[0]['cValue'];
    }
    
    /**
     * Get user scripts directory
     * 
     * @return string User scripts directory
     * @throws Exception
     */
    public function getUserScriptsPath(): string {
        
        $statement = $this->dbConn->prepare("SELECT * FROM configuration WHERE cName = 'userScriptsPath';");
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Configuration userScriptsPath does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        
        return $items[0]['cValue'];
    }
    
    /**
     * Get alarm acknowledgement rights role
     * 
     * @return string Alarm acknowledgement rights role
     * @throws Exception
     */
    public function getAckAccessRole(): string {
        
        $statement = $this->dbConn->prepare("SELECT * FROM configuration WHERE cName = 'ackAccessRole';");
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Configuration ackAccessRole does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        
        return $items[0]['cValue'];
    }
}

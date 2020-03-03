<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use App\Entity\Admin\ConfigDriverModbus;
use App\Entity\Admin\ConfigDriverSHM;
use App\Entity\Admin\Tag;
use App\Service\Admin\BaseConfigMapper;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\AppException;

/**
 * Class to read/write Driver configuration of the system
 *
 * @author Mateusz Mirosławski
 */
class ConfigDriverMapper extends BaseConfigMapper {
        
    public function __construct(Connection $connection) {
        
        parent::__construct($connection);
    }
    
    /**
     * Get selected driver name
     * 
     * @return string Driver name
     */
    public function getDriverName(): string {
        
        $statement = $this->dbConn->prepare("SELECT * FROM configuration WHERE cName = 'connectionDriver';");
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Configuration for connectionDriver does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        
        return $items[0]['cValue'];
    }
    
    /**
     * Get ModbusTCP configuration
     * 
     * @return ConfigDriverModbus Object with ModbusTCP configuration
     */
    public function getModbusConfig(): ConfigDriverModbus {
        
        $statement = $this->dbConn->prepare("SELECT * FROM configuration WHERE cName LIKE 'modbus%';");
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Configuration for ModbusTCP does not exist!");
        }
        if (count($items) != 5) {
            throw new Exception("Query return more than one element!");
        }
        
        $cg = new ConfigDriverModbus();
        
        for ($i=0; $i<count($items); ++$i) {
            
            if ($items[$i]['cName'] == 'modbusIP') {
                $cg->setIpAddress($items[$i]['cValue']);
            }
            if ($items[$i]['cName'] == 'modbusPort') {
                $cg->setPort($items[$i]['cValue']);
            }
            if ($items[$i]['cName'] == 'modbusRegCount') {
                $cg->setRegisterCount($items[$i]['cValue']);
            }
            if ($items[$i]['cName'] == 'modbusPollingInterval') {
                $cg->setDriverPolling($items[$i]['cValue']);
            }
            if ($items[$i]['cName'] == 'modbusSlaveID') {
                $cg->setSlaveID($items[$i]['cValue']);
            }
            
        }
        
        return $cg;
    }
    
    /**
     * Get SHM configuration
     * 
     * @return ConfigDriverSHM Object with SHM configuration
     */
    public function getSHMConfig(): ConfigDriverSHM {
        
        $statement = $this->dbConn->prepare("SELECT * FROM configuration WHERE cName LIKE 'shm%';");
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Configuration for SHM does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        
        $cg = new ConfigDriverSHM();
        
        $cg->setSegmentName($items[0]['cValue']);
        
        return $cg;
    }
    
    /**
     * Check if Tag byte address is out of driver range
     * 
     * @param Tag $tg Tag object
     * @throws AppException
     */
    public function checkDriverByteAddress(Tag $tg) {
        
        $maxByteAddress = 0;
        
        // Get max allowed Byte address
        switch ($this->getDriverName()) {
            case 'SHM': $maxByteAddress = ConfigDriverSHM::maxProcessAddress; break;
            case 'ModbusTCP': $maxByteAddress = $this->getModbusConfig()->getRegisterCount(); break;
        }
        
        // Check Tag address
        if ($tg->getByteAddress() >= $maxByteAddress) {
            throw new AppException(
                "Tag byte address is out of driver range.".
                " Max allowed byte address is ".($maxByteAddress-1),
                AppException::TAG_BYTE_ADDRESS_WRONG
            );
        }
    }
    
    /**
     * Write ModbusTCP configuration to the DB
     * 
     * @param ConfigDriverModbus $newCFG ModbusTCP configuration object
     */
    public function setModbusConfig(ConfigDriverModbus $newCFG) {
        
        // Get current configuration
        $currentCFG = $this->getModbusConfig();
        
        $sqls = array();
        $vals = array();
        $sql = '';
        
        // ipAddress
        if ($newCFG->getIpAddress() <> $currentCFG->getIpAddress()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusIP';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getIpAddress());
            
        }
        
        // port
        if ($newCFG->getPort() <> $currentCFG->getPort()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusPort';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getPort());
            
        }
        
        // registerCount
        if ($newCFG->getRegisterCount() <> $currentCFG->getRegisterCount()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusRegCount';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getRegisterCount());
            
        }
        
        // driverPolling
        if ($newCFG->getDriverPolling() <> $currentCFG->getDriverPolling()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusPollingInterval';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getDriverPolling());
            
        }
        
        // slaveID
        if ($newCFG->getSlaveID() <> $currentCFG->getSlaveID()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusSlaveID';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getSlaveID());
            
        }
        
        // Update driver selection
        $this->selectModbusDriver();
        
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
     * Write SHM configuration to the DB
     * 
     * @param ConfigDriverSHM $newCFG SHM configuration object
     */
    public function setSHMConfig(ConfigDriverSHM $newCFG) {
        
        // Get current configuration
        $currentCFG = $this->getSHMConfig();
        
        $sqls = array();
        $vals = array();
        $sql = '';
        
        // segmentName
        if ($newCFG->getSegmentName() <> $currentCFG->getSegmentName()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'shmSegmentName';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getSegmentName());
            
        }
        
        // Update driver selection
        $this->selectSHMDriver();
        
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
     * Select SHM driver in DB
     */
    private function selectSHMDriver() {
        
        $stmt = $this->dbConn->prepare("UPDATE configuration SET cValue = 'SHM' WHERE cName = 'connectionDriver';");
        $stmt->execute();
    }
    
    /**
     * Select ModbusTCP driver in DB
     */
    private function selectModbusDriver() {
        
        $stmt = $this->dbConn->prepare("UPDATE configuration SET cValue = 'ModbusTCP' WHERE cName = 'connectionDriver';");
        $stmt->execute();
    }
}

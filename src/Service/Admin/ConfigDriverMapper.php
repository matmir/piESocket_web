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
            throw new Exception("Configuration for Modbus does not exist!");
        }
        if (count($items) != 11) {
            throw new Exception("Invalid number of modbus configuration entries!");
        }
        
        $cg = new ConfigDriverModbus();
        
        for ($i=0; $i<count($items); ++$i) {
            
            if ($items[$i]['cName'] == 'modbusMode') {
                $cg->setMode($items[$i]['cValue']);
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
            
            if ($items[$i]['cName'] == 'modbusTCP_addr') {
                $cg->setTCPaddr($items[$i]['cValue']);
            }
            if ($items[$i]['cName'] == 'modbusTCP_port') {
                $cg->setTCPport($items[$i]['cValue']);
            }
            
            if ($items[$i]['cName'] == 'modbusRTU_port') {
                $cg->setRTUport($items[$i]['cValue']);
            }
            if ($items[$i]['cName'] == 'modbusRTU_baud') {
                $cg->setRTUbaud($items[$i]['cValue']);
            }
            if ($items[$i]['cName'] == 'modbusRTU_parity') {
                $cg->setRTUparity($items[$i]['cValue']);
            }
            if ($items[$i]['cName'] == 'modbusRTU_dataBit') {
                $cg->setRTUdataBit($items[$i]['cValue']);
            }
            if ($items[$i]['cName'] == 'modbusRTU_stopBit') {
                $cg->setRTUstopBit($items[$i]['cValue']);
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
        
        // TCP ip address
        if ($newCFG->getTCPaddr() <> $currentCFG->getTCPaddr()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusTCP_addr';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getTCPaddr());
            
        }
        
        // TCP port
        if ($newCFG->getTCPport() <> $currentCFG->getTCPport()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusTCP_port';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getTCPport());
            
        }
        
        // RTU port
        if ($newCFG->getRTUport() <> $currentCFG->getRTUport()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusRTU_port';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getRTUport());
            
        }
        
        // RTU baud rate
        if ($newCFG->getRTUbaud() <> $currentCFG->getRTUbaud()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusRTU_baud';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getRTUbaud());
            
        }
        
        // RTU parity
        if ($newCFG->getRTUparity() <> $currentCFG->getRTUparity()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusRTU_parity';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getRTUparity());
            
        }
        
        // RTU data bit
        if ($newCFG->getRTUdataBit() <> $currentCFG->getRTUdataBit()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusRTU_dataBit';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getRTUdataBit());
            
        }
        
        // RTU stop bit
        if ($newCFG->getRTUstopBit() <> $currentCFG->getRTUstopBit()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusRTU_stopBit';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getRTUstopBit());
            
        }
        
        // Mode
        if ($newCFG->getMode() <> $currentCFG->getMode()) {
            
            $sql = "UPDATE configuration SET cValue = ? WHERE cName = 'modbusMode';";
            array_push($sqls, $sql);
            array_push($vals, $newCFG->getMode());
            
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
        
        $stmt = $this->dbConn->prepare("UPDATE configuration SET cValue = 'Modbus' WHERE cName = 'connectionDriver';");
        $stmt->execute();
    }
}

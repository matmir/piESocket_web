<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\Config\Definition\Exception\Exception;

use App\Service\Admin\BaseConfigMapper;
use App\Entity\Admin\DriverConnection;
use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverModbusMode;
use App\Entity\Admin\DriverSHM;
use App\Entity\Admin\DriverType;
use App\Entity\Admin\Tag;
use App\Entity\AppException;

/**
 * Class to read/write Driver connection
 *
 * @author Mateusz Mirosławski
 */
class DriverConnectionMapper extends BaseConfigMapper {
        
    /**
     * Max connections
     */
    const maxConnections = 5;
    
    public function __construct(Connection $connection) {
        
        parent::__construct($connection);
    }
    
    /**
     * Get Driver connections
     * 
     * @return array Array with Driver connections
     */
    public function getConnections(bool $onlyActive=false) {
        
        // Basic query
        $sql = 'SELECT * FROM driver_connections';
        
        // Enabled?
        if ($onlyActive===true) {
            $sql .= ' WHERE dcEnable = 1';
        }
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (count($items) > self::maxConnections) {
            throw new Exception("Too much connections defined in DB");
        }
        
        $ret = array();
        
        foreach($items as $item) {
            
            // New connection
            $conn = new DriverConnection();
            
            $conn->setId($item['dcId']);
            $conn->setName($item['dcName']);
            $conn->setType($item['dcType']);
            
            // Check type
            if ($conn->getType() == DriverType::Modbus) {
                
                if ($item['dcConfigModbus']===null) {
                    throw new Exception("Missing modbus configuration for connection ".$conn->getName());
                }
                                
                // Get modbus configuration
                $conn->setModbusConfig($this->getModbusConfig($item['dcConfigModbus']));
                
            } else if ($conn->getType() == DriverType::SHM) {
                
                if ($item['dcConfigSHM']===null) {
                    throw new Exception("Missing SHM configuration for connection ".$conn->getName());
                }
                                
                // Get modbus configuration
                $conn->setShmConfig($this->getShmConfig($item['dcConfigSHM']));
                
            } else {
                throw new Exception("Unknown connection driver type");
            }
            
            $conn->setEnable($item['dcEnable']);
            
            // Add to the array
            array_push($ret, $conn);
        }
        
        return $ret;
    }
    
    /**
     * Get connection names with id
     * 
     * @return array Connection names with id
     * @throws Exception
     */
    public function getConnectionsName() {
        
        // Basic query
        $sql = 'SELECT * FROM driver_connections;';
        
        $statement = $this->dbConn->prepare($sql);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (count($items) > self::maxConnections) {
            throw new Exception("Too much connections defined in DB");
        }
        
        $ret = array();
        
        foreach($items as $item) {
            
            $ret[$item['dcName']] = $item['dcId'];
        }
        
        return $ret;
    }
    
    /**
     * Get Modbus configuration
     * 
     * @param int $mid Modbus configuration identifier
     * @return DriverModbus Object with Modbus configuration
     */
    private function getModbusConfig(int $mid): DriverModbus {
                
        // Check identifier
        DriverModbus::checkId($mid);
        
        // Basic query
        $sql = 'SELECT * FROM driver_modbus WHERE dmId = ?';
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $mid, ParameterType::INTEGER);
        $statement->execute();
        
        $items= $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Modbus configuration with identifier ".$mid." does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New Modbus config
        $mb = new DriverModbus();
        $mb->setId($item['dmId']);
        $mb->setMode($item['dmMode']);
        $mb->setDriverPolling($item['dmPollingInterval']);
        $mb->setRegisterCount($item['dmRegCount']);
        
        if ($mb->getMode() == DriverModbusMode::RTU) {
            $mb->setRTUbaud($item['dmRTU_baud']);
            $mb->setRTUdataBit($item['dmRTU_dataBit']);
            $mb->setRTUparity($item['dmRTU_parity']);
            $mb->setRTUport($item['dmRTU_port']);
            $mb->setRTUstopBit($item['dmRTU_stopBit']);
        } else if ($mb->getMode() == DriverModbusMode::TCP) {
            $mb->setTCPaddr($item['dmTCP_addr']);
            $mb->setTCPport($item['dmTCP_port']);
        }
        
        $mb->setSlaveID($item['dmSlaveID']);
        
        return $mb;
    }
    
    /**
     * Get SHM configuration
     * 
     * @param int $sid SHM configuration identifier
     * @return DriverSHM Object with SHM configuration
     */
    private function getShmConfig(int $sid): DriverSHM {
        
        // Check identifier
        DriverSHM::checkId($sid);
        
        // Basic query
        $sql = 'SELECT * FROM driver_shm WHERE dsId = ?';
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $sid, ParameterType::INTEGER);
        $statement->execute();
        
        $items= $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("SHM configuration with identifier ".$sid." does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New Shm config
        $shm = new DriverSHM();
        $shm->setId($item['dsId']);
        $shm->setSegmentName($item['dsSegment']);
        
        return $shm;
    }
    
    /**
     * Get Driver connection
     * 
     * @param int $cid Driver connection identifier
     * @return DriverConnection Driver connection
     */
    public function getConnection(int $cid): DriverConnection {
        
        // Check identifier
        DriverConnection::checkId($cid);
        
        // Basic query
        $sql = 'SELECT * FROM driver_connections WHERE dcId = ?';
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $cid, ParameterType::INTEGER);
        $statement->execute();
        
        $items= $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Driver connection with identifier ".$cid." does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New connection
        $conn = new DriverConnection();

        $conn->setId($item['dcId']);
        $conn->setName($item['dcName']);
        $conn->setType($item['dcType']);

        // Check type
        if ($conn->getType() == DriverType::Modbus) {

            if ($item['dcConfigModbus']===null) {
                throw new Exception("Missing modbus configuration for connection ".$conn->getName());
            }

            // Get modbus configuration
            $conn->setModbusConfig($this->getModbusConfig($item['dcConfigModbus']));

        } else if ($conn->getType() == DriverType::SHM) {

            if ($item['dcConfigSHM']===null) {
                throw new Exception("Missing SHM configuration for connection ".$conn->getName());
            }

            // Get modbus configuration
            $conn->setShmConfig($this->getShmConfig($item['dcConfigSHM']));

        } else {
            throw new Exception("Unknown connection driver type");
        }
        
        $conn->setEnable($item['dcEnable']);

        return $conn;
    }
    
    /**
     * Check if there is available space for new connection
     * 
     * @return bool True if there is available space for new connection
     * @throws Exception
     */
    private function isFreeConnectionPool(): bool {
        
        // Basic query
        $sql = "SELECT count(*) AS 'cnt' FROM driver_connections;";
        
        $statement = $this->dbConn->prepare($sql);
        $statement->execute();
        
        $items= $statement->fetchAll();
        
        if (empty($items) || count($items) != 1) {
            throw new Exception("Error during executing count query!");
        }
                
        return ($items[0]['cnt'] >= self::maxConnections)?(false):(true);
    }
    
    /**
     * Add Driver connection to the DB
     * 
     * @param DriverConnection $newConn Driver connection to add
     */
    public function addConnection(DriverConnection $newConn) {
        
        // Check connection limit
        if (!$this->isFreeConnectionPool()) {
            throw new AppException(
                "Driver connection limit exceeded",
                AppException::DRIVER_LIMIT
            );
        }
        
        // Check if driver connection is valid
        $newConn->isValid();
        
        $this->dbConn->beginTransaction();
        
        try {
            
            $q = 'INSERT INTO driver_connections (dcName, dcType';
        
            // Check specific driver
            $dcId = 0;
            if ($newConn->getType()==DriverType::Modbus && $newConn->isModbusConfig()) {
                $q .= ', dcConfigModbus';
                // Add modbus configuration
                $dcId = $this->addModbusConfiguration($newConn->getModbusConfig());
            } else if ($newConn->getType()==DriverType::SHM && $newConn->isShmConfig()) {
                $q .= ', dcConfigSHM';
                // Add SHM configuration
                $dcId = $this->addShmConfiguration($newConn->getShmConfig());
            } else {
                throw new Exception("Incorrect driver connection object");
            }

            $q .= ') VALUES(?, ?, ?);';

            $stmt = $this->dbConn->prepare($q);

            $stmt->bindValue(1, $newConn->getName(), ParameterType::STRING);
            $stmt->bindValue(2, $newConn->getType(), ParameterType::INTEGER);
            $stmt->bindValue(3, $dcId, ParameterType::INTEGER);
            
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
            
            $this->dbConn->commit();
            
            // Set restart flag
            $this->setServerRestartFlag();
            
        } catch (UniqueConstraintViolationException $ex) {
            
            $this->dbConn->rollBack();
            
            throw new AppException(
                "Driver connection exist in DB!",
                AppException::DRIVER_EXIST
            );
            
        }
    }
    
    /**
     * Check if Modbus TCP addres is used in DB
     * 
     * @param DriverModbus $newModbus Modbus driver configuration
     * @param bool $edit Edit flag
     * @return bool True if Modbus TCP addres is used in DB
     */
    private function isModbusAddressUsed(DriverModbus $newModbus, bool $edit=false): bool {
        
        $ret = false;
        
        if ($newModbus->getMode() == DriverModbusMode::TCP) {
            
            // Basic query
            $sql = 'SELECT * FROM driver_modbus WHERE dmTCP_addr=?';
            
            if ($edit) {
                $sql .= ' AND dmId <> ?';
            }
            $sql .= ';';

            $statement = $this->dbConn->prepare($sql);

            $statement->bindValue(1, $newModbus->getTCPaddr(), ParameterType::STRING);
            if ($edit) {
                $statement->bindValue(2, $newModbus->getId(), ParameterType::INTEGER);
            }

            $statement->execute();
            $items = $statement->fetchAll();

            foreach($items as $item) {

                if ($item['dmTCP_port'] == $newModbus->getTCPport()) {
                    $ret = true;
                    break;
                }
            }
        }
        
        return $ret;
    }
    
    /**
     * Add Modbus configuration to the DB
     * 
     * @param DriverModbus $newModbus Modbus configuration to add
     * @return int Inserted configuration identifier
     */
    private function addModbusConfiguration(DriverModbus $newModbus): int {
        
        // Check if driver is valid
        $newModbus->isValid();
        
        // Check modbus tcp address
        if ($this->isModbusAddressUsed($newModbus)) {
            throw new AppException(
                "Modbus address exist in DB!",
                AppException::MODBUS_ADDRESS_EXIST
            );
        }
        
        $q = 'INSERT INTO driver_modbus (dmMode, dmPollingInterval, dmRegCount, dmSlaveID';
        $q .= ', dmRTU_baud, dmRTU_dataBit, dmRTU_parity, dmRTU_port, dmRTU_stopBit';
        $q .= ', dmTCP_addr, dmTCP_port)';
        $q .= ' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newModbus->getMode(), ParameterType::INTEGER);
        $stmt->bindValue(2, $newModbus->getDriverPolling(), ParameterType::INTEGER);
        $stmt->bindValue(3, $newModbus->getRegisterCount(), ParameterType::INTEGER);
        $stmt->bindValue(4, $newModbus->getSlaveID(), ParameterType::INTEGER);
        
        if ($newModbus->getMode() == DriverModbusMode::RTU) {
            $stmt->bindValue(5, $newModbus->getRTUbaud(), ParameterType::INTEGER);
            $stmt->bindValue(6, $newModbus->getRTUdataBit(), ParameterType::INTEGER);
            $stmt->bindValue(7, $newModbus->getRTUparity(), ParameterType::STRING);
            $stmt->bindValue(8, $newModbus->getRTUport(), ParameterType::STRING);
            $stmt->bindValue(9, $newModbus->getRTUstopBit(), ParameterType::INTEGER);
            $stmt->bindValue(10, NULL, ParameterType::NULL);
            $stmt->bindValue(11, NULL, ParameterType::NULL);
        } else {
            $stmt->bindValue(5, NULL, ParameterType::NULL);
            $stmt->bindValue(6, NULL, ParameterType::NULL);
            $stmt->bindValue(7, NULL, ParameterType::NULL);
            $stmt->bindValue(8, NULL, ParameterType::NULL);
            $stmt->bindValue(9, NULL, ParameterType::NULL);
            $stmt->bindValue(10, $newModbus->getTCPaddr(), ParameterType::STRING);
            $stmt->bindValue(11, $newModbus->getTCPport(), ParameterType::INTEGER);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error during execute sql add query!");
        }
        
        return $this->dbConn->lastInsertId();
    }
    
    /**
     * Add SHM configuration to the DB
     * 
     * @param DriverSHM $newShm SHM configuration to add
     * @return int Inserted configuration identifier
     */
    private function addShmConfiguration(DriverSHM $newShm): int {
        
        // Check if driver is valid
        $newShm->isValid();
        
        $q = 'INSERT INTO driver_shm (dsSegment) VALUES(?);';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newShm->getSegmentName(), ParameterType::STRING);
        
        try {
            
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
            
        } catch (UniqueConstraintViolationException $ex) {
            
            throw new AppException(
                "SHM with segment name: ".$newShm->getSegmentName()." exist in DB!",
                AppException::SHM_EXIST
            );
        }
        
        return $this->dbConn->lastInsertId();
    }
    
    private function editModbusConfiguration(DriverModbus $mb) {
        
        // Check if driver connection is valid
        $mb->isValid(true);
        
        // Check modbus tcp address
        if ($this->isModbusAddressUsed($mb, true)) {
            throw new AppException(
                "Modbus address exist in DB!",
                AppException::MODBUS_ADDRESS_EXIST
            );
        }
        
        $q = 'UPDATE driver_modbus SET dmMode = ?, dmPollingInterval = ?, dmRegCount = ?, dmSlaveID = ?';
        $q .= ', dmRTU_baud = ?, dmRTU_dataBit = ?, dmRTU_parity = ?, dmRTU_port = ?, dmRTU_stopBit = ?';
        $q .= ', dmTCP_addr = ?, dmTCP_port = ? WHERE dmId = ?;';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $mb->getMode(), ParameterType::INTEGER);
        $stmt->bindValue(2, $mb->getDriverPolling(), ParameterType::INTEGER);
        $stmt->bindValue(3, $mb->getRegisterCount(), ParameterType::INTEGER);
        $stmt->bindValue(4, $mb->getSlaveID(), ParameterType::INTEGER);
        
        // Check modbus mode
        if ($mb->getMode() == DriverModbusMode::RTU) {
            $stmt->bindValue(5, $mb->getRTUbaud(), ParameterType::INTEGER);
            $stmt->bindValue(6, $mb->getRTUdataBit(), ParameterType::INTEGER);
            $stmt->bindValue(7, $mb->getRTUparity(), ParameterType::STRING);
            $stmt->bindValue(8, $mb->getRTUport(), ParameterType::STRING);
            $stmt->bindValue(9, $mb->getRTUstopBit(), ParameterType::INTEGER);
            $stmt->bindValue(10, NULL, ParameterType::NULL);
            $stmt->bindValue(11, NULL, ParameterType::NULL);
        } else if ($mb->getMode() == DriverModbusMode::TCP) {
            $stmt->bindValue(5, NULL, ParameterType::NULL);
            $stmt->bindValue(6, NULL, ParameterType::NULL);
            $stmt->bindValue(7, NULL, ParameterType::NULL);
            $stmt->bindValue(8, NULL, ParameterType::NULL);
            $stmt->bindValue(9, NULL, ParameterType::NULL);
            $stmt->bindValue(10, $mb->getTCPaddr(), ParameterType::STRING);
            $stmt->bindValue(11, $mb->getTCPport(), ParameterType::INTEGER);
        }
        $stmt->bindValue(12, $mb->getId(), ParameterType::INTEGER);
        
        if (!$stmt->execute()) {
            throw new Exception("Error during execute sql update query!");
        }
    }
    
    private function editShmConfiguration(DriverSHM $shm) {
        
        // Check if driver connection is valid
        $shm->isValid(true);
        
        $q = 'UPDATE driver_shm SET dsSegment = ? WHERE dsId = ?;';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $shm->getSegmentName(), ParameterType::STRING);
        $stmt->bindValue(2, $shm->getId(), ParameterType::INTEGER);
        
        try {
            
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
            
        } catch (UniqueConstraintViolationException $ex) {
            
            throw new AppException(
                "SHM with segment name: ".$shm->getSegmentName()." exist in DB!",
                AppException::SHM_EXIST
            );
        }
    }
    
    /**
     * Edit driver connection
     * 
     * @param DriverConnection $newConn Driver connection to edit
     */
    public function editConnection(DriverConnection $newConn) {
        
        // Check if driver connection is valid
        $newConn->isValid(true);
        
        $this->dbConn->beginTransaction();
        
        try {
            
            $q = 'UPDATE driver_connections SET dcName = ?, dcType = ?, dcConfigModbus = ?, dcConfigSHM = ?';
            $q .= ' WHERE dcId = ?;';

            $stmt = $this->dbConn->prepare($q);

            $stmt->bindValue(1, $newConn->getName(), ParameterType::STRING);
            $stmt->bindValue(2, $newConn->getType(), ParameterType::INTEGER);
            if ($newConn->getType() == DriverType::Modbus) {
                // Edit modbus connection
                $this->editModbusConfiguration($newConn->getModbusConfig());

                $stmt->bindValue(3, $newConn->getModbusConfig()->getId(), ParameterType::INTEGER);
                $stmt->bindValue(4, NULL, ParameterType::NULL);
            } else if ($newConn->getType() == DriverType::SHM) {
                // Edit SHM connection
                $this->editShmConfiguration($newConn->getShmConfig());

                $stmt->bindValue(3, NULL, ParameterType::NULL);
                $stmt->bindValue(4, $newConn->getShmConfig()->getId(), ParameterType::INTEGER);
            }
            $stmt->bindValue(5, $newConn->getId(), ParameterType::INTEGER);
            
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
            
            $this->dbConn->commit();
            
            // Set restart flag
            $this->setServerRestartFlag();
            
        } catch (UniqueConstraintViolationException $ex) {
            
            $this->dbConn->rollBack();
            
            throw new AppException(
                "Driver connection exist in DB!",
                AppException::DRIVER_EXIST
            );
        }
    }
    
    /**
     * Delete modbus configuration
     * 
     * @param type $mId Modbus configuration identifier
     * @throws Exception
     */
    private function deleteModbusConfiguration($mId) {
        
        DriverModbus::checkId($mId);
        
        $statement = $this->dbConn->prepare('DELETE FROM driver_modbus WHERE dmId = ?;');
        $statement->bindValue(1, $mId, ParameterType::INTEGER);
                
        if (!$statement->execute()) {
            throw new Exception("Error during execute delete query!");
        }
    }
    
    /**
     * Delete shm configuration
     * 
     * @param type $sId SHM configuration identifier
     * @throws Exception
     */
    private function deleteShmConfiguration($sId) {
        
        DriverSHM::checkId($sId);
        
        $statement = $this->dbConn->prepare('DELETE FROM driver_shm WHERE dsId = ?;');
        $statement->bindValue(1, $sId, ParameterType::INTEGER);
                
        if (!$statement->execute()) {
            throw new Exception("Error during execute delete query!");
        }
    }
    
    /**
     * Delete driver connection
     * 
     * @param numeric $connId Driver connection identifier
     */
    public function deleteConnection($connId) {
        
        // Get connection object
        $conn = $this->getConnection($connId);
        
        $this->dbConn->beginTransaction();
        
        $statement = $this->dbConn->prepare('DELETE FROM driver_connections WHERE dcId = ?;');
        $statement->bindValue(1, $conn->getId(), ParameterType::INTEGER);
        
        try {
            
            if (!$statement->execute()) {
                throw new Exception("Error during execute delete query!");
            }
            
            if ($conn->getType() == DriverType::Modbus) {
                $this->deleteModbusConfiguration($conn->getModbusConfig()->getId());
            } else if ($conn->getType() == DriverType::SHM) {
                $this->deleteShmConfiguration($conn->getShmConfig()->getId());
            }
            
            $this->dbConn->commit();
            
            // Set restart flag
            $this->setServerRestartFlag();
            
        } catch (ForeignKeyConstraintViolationException $ex) {
            
            $this->dbConn->rollBack();
            
            throw new AppException(
                "Connection with identifier: ".$connId." is used inside system!",
                AppException::DRIVER_USED
            );
        }
    }
    
    /**
     * Enable driver connection
     * 
     * @param numeric $connId Driver connection identifier
     * @param bool $en Enable flag
     */
    public function enableConnection($connId, bool $en = true) {
        
        // Check identifier
        DriverConnection::checkId($connId);
        
        $stmt = $this->dbConn->prepare('UPDATE driver_connections SET dcEnable = ? WHERE dcId = ?;');
        
        $stmt->bindValue(1, (($en)?(1):(0)), ParameterType::INTEGER);
        $stmt->bindValue(2, $connId, ParameterType::INTEGER);
        
        if (!$stmt->execute()) {
            throw new Exception("Error during execute sql update query!");
        }
        
        // Set restart flag
        $this->setServerRestartFlag();
    }
    
    /**
     * Check if Tag byte address is out of driver range
     * 
     * @param Tag $tg Tag object
     * @throws AppException
     */
    public function checkDriverByteAddress(Tag $tg) {
        
        $maxByteAddress = 0;
        
        // Get connection
        $conn = $this->getConnection($tg->getConnId());
        
        // Get max allowed Byte address
        switch ($conn->getType()) {
            case DriverType::SHM: $maxByteAddress = DriverSHM::maxProcessAddress; break;
            case DriverType::Modbus: $maxByteAddress = $conn->getModbusConfig()->getMaxByteAddress(); break;
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
}

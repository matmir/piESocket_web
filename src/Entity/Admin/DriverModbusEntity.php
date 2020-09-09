<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\DriverConnectionEntity;
use App\Entity\Admin\DriverConnection;
use App\Entity\Admin\DriverType;
use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverModbusMode;

/**
 * Class for Modbus driver configuration
 * 
 * @author Mateusz Mirosławski
 */
class DriverModbusEntity extends DriverConnectionEntity {
    
    /**
     * Modbus driver identifier
     * 
     * @Assert\PositiveOrZero
     */
    private $id;
    
    /**
     * Modbus mode
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 1
     * )
     */
    private $mode;
    
    /**
     * Modbus TCP IP address
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=15)
     */
    private $TCP_addr;
    
    /**
     * Modbus TCP port number
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 65535
     * )
     */
    private $TCP_port;
    
    /**
     * Modbus RTU port name
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=200)
     */
    private $RTU_port;
    
    /**
     * Modbus RTU baud rate
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 1000000
     * )
     */
    private $RTU_baud;
    
    /**
     * Modbus RTU parity
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=1)
     */
    private $RTU_parity;
    
    /**
     * Modbus RTU data bits
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 5,
     *      max = 8
     * )
     */
    private $RTU_dataBit;
    
    /**
     * Modbus RTU stop bits
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 2
     * )
     */
    private $RTU_stopBit;
    
    /**
     * Modbus slave ID
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 247
     * )
     */
    private $slaveID;
    
    /**
     * Register count
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 4096
     * )
     */
    private $registerCount;
    
    /**
     * Driver polling interval
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 10,
     *      max = 5000
     * )
     */
    private $driverPolling;
    
    /**
     * Default constructor
     */
    public function __construct() {
        
        parent::__construct();
        
        // Common data
        $this->id = 0;
        $this->mode = DriverModbusMode::TCP;
        $this->registerCount = 1;
        $this->driverPolling = 50;
        $this->slaveID = 2;
        
        // Modbus TCP
        $this->TCP_addr = "192.168.0.5";
        $this->TCP_port = 502;
        
        // Modbus RTU
        $this->RTU_port = "/dev/ttyACM1";
        $this->RTU_baud = 57600;
        $this->RTU_parity = 'N';
        $this->RTU_dataBit = 8;
        $this->RTU_stopBit = 1;
    }
    
    /**
     * Get Modbus driver identifier
     * 
     * @return int Modbus driver identifier
     */
    public function getId(): int {
        
        return $this->id;
    }
    
    /**
     * Set Modbus driver identifier
     * 
     * @param int $id Modbus driver identifier
     */
    public function setId(int $id) {
                
        $this->id = $id;
    }
    
    /**
     * Get Modbus mode (TCP/RTU)
     * 
     * @return int Modbus mode
     */
    public function getMode() {
        
        return $this->mode;
    }

    /**
     * Set Modbus mode
     * 
     * @param int $val Modbus mode
     */
    public function setMode(int $val) {
        
        $this->mode = $val;
    }

    /**
     * Get Modbus slave IP address
     * 
     * @return string Modbus slave IP address
     */
    public function getTCPaddr() {
        
        return $this->TCP_addr;
    }
    
    /**
     * Set Modbus slave IP address
     * 
     * @param string $val Modbus slave IP address
     */
    public function setTCPaddr(string $val) {
        
        $this->TCP_addr = $val;
    }
    
    /**
     * Get Modbus port number
     * 
     * @return int Modbus port number
     */
    public function getTCPport() {
        
        return $this->TCP_port;
    }
    
    /**
     * Set Modbus port number
     * 
     * @param int $val Modbus port number
     */
    public function setTCPport(int $val) {
        
        $this->TCP_port = $val;
    }
    
    /**
     * Get Modbus RTU port
     * 
     * @return string Modbus RTU port
     */
    public function getRTUport() {
        
        return $this->RTU_port;
    }

    /**
     * Set Modbus RTU port
     * 
     * @param string $val Modbus RTU port
     */
    public function setRTUport(string $val) {
        
        $this->RTU_port = $val;
    }
    
    /**
     * Get Modbus RTU baud rate
     * 
     * @return int Modbus RTU baud rate
     */
    public function getRTUbaud() {
        
        return $this->RTU_baud;
    }
    
    /**
     * Set Modbus RTU baud rate
     * 
     * @param int $val Modbus RTU baud rate
     */
    public function setRTUbaud(int $val) {
        
        $this->RTU_baud = $val;
    }
    
    /**
     * Get Modbus RTU parity
     * 
     * @return string Modbus RTU parity
     */
    public function getRTUparity() {
        
        return $this->RTU_parity;
    }

    /**
     * Set Modbus RTU parity
     * 
     * @param string $val Modbus RTU parity
     */
    public function setRTUparity(string $val) {
        
        $this->RTU_parity = $val;
    }
    
    /**
     * Get Modbus RTU data bit
     * 
     * @return int Modbus RTU data bit
     */
    public function getRTUdataBit() {
        
        return $this->RTU_dataBit;
    }
    
    /**
     * Set Modbus RTU data bit
     * 
     * @param int $val Modbus RTU data bit
     */
    public function setRTUdataBit(int $val) {
        
        $this->RTU_dataBit = $val;
    }
    
    /**
     * Get Modbus RTU stop bit
     * 
     * @return int Modbus RTU stop bit
     */
    public function getRTUstopBit() {
        
        return $this->RTU_stopBit;
    }
    
    /**
     * Set Modbus RTU stop bit
     * 
     * @param int $val Modbus RTU stop bit
     */
    public function setRTUstopBit(int $val) {
        
        $this->RTU_stopBit = $val;
    }
    
    /**
     * Get Modbus slave identifier
     * 
     * @return int Modbus slave ID
     */
    public function getSlaveID() {
        
        return $this->slaveID;
    }
    
    /**
     * Set Modbus slave identifier
     * 
     * @param int $val Modbus slave ID
     */
    public function setSlaveID(int $val) {
        
        $this->slaveID = $val;
    }
    
    /**
     * Get Modbus register count
     * 
     * @return int Modbus register count
     */
    public function getRegisterCount() {
        
        return $this->registerCount;
    }
    
    /**
     * Set Modbus register count
     * 
     * @param int $val Modbus register count
     */
    public function setRegisterCount(int $val) {
        
        $this->registerCount = $val;
    }
    
    /**
     * Get driver polling interval
     * 
     * @return int Driver polling interval
     */
    public function getDriverPolling() {
        
        return $this->driverPolling;
    }
    
    /**
     * Set driver polling interval
     * 
     * @param int $val Driver polling interval
     */
    public function setDriverPolling(int $val) {
        
        $this->driverPolling = $val;
    }
    
    /**
     * Get Driver Connection object
     * 
     * @return DriverConnection Driver connection object
     */
    public function getFullConnectionObject(): DriverConnection {
        
        // New Modbus
        $mb = new DriverModbus();
        $mb->setId($this->id);
        $mb->setMode($this->mode);
        $mb->setDriverPolling($this->driverPolling);
        $mb->setRegisterCount($this->registerCount);
        
        if ($mb->getMode() == DriverModbusMode::RTU) {
            $mb->setRTUbaud($this->RTU_baud);
            $mb->setRTUdataBit($this->RTU_dataBit);
            $mb->setRTUparity($this->RTU_parity);
            $mb->setRTUport($this->RTU_port);
            $mb->setRTUstopBit($this->RTU_stopBit);
        } else if ($mb->getMode() == DriverModbusMode::TCP) {
            $mb->setTCPaddr($this->TCP_addr);
            $mb->setTCPport($this->TCP_port);
        }
        $mb->setSlaveID($this->slaveID);
        
        // New connection
        $conn = new DriverConnection();
        $conn->setId($this->connId);
        $conn->setName($this->connName);
        $conn->setType(DriverType::Modbus);
        $conn->setModbusConfig($mb);
        
        return $conn;
    }
    
    /**
     * Initialize from Driver connection object
     * 
     * @param DriverConnection $conn Driver connection object
     */
    public function initFromConnectionObject(DriverConnection $conn) {
        
        // Init parent
        parent::initFromConnectionObject($conn);
        
        if (!$conn->isModbusConfig()) {
            throw new Exception("Missing modbus configuration in connection object");
        }
        
        $mb = $conn->getModbusConfig();
        
        $this->id = $mb->getId();
        $this->mode = $mb->getMode();
        $this->driverPolling = $mb->getDriverPolling();
        $this->registerCount = $mb->getRegisterCount();
        $this->RTU_baud = $mb->getRTUbaud();
        $this->RTU_dataBit = $mb->getRTUdataBit();
        $this->RTU_parity = $mb->getRTUparity();
        $this->RTU_port = $mb->getRTUport();
        $this->RTU_stopBit = $mb->getRTUstopBit();
        $this->TCP_addr = $mb->getTCPaddr();
        $this->TCP_port = $mb->getTCPport();
        $this->slaveID = $mb->getSlaveID();
    }
}

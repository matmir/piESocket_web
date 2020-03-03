<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class for ModbusTCP driver configuration
 * 
 * @author Mateusz Mirosławski
 */
class ConfigDriverModbus {
    
    /**
     * Modbus IP address
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=15)
     */
    private $ipAddress;
    
    /**
     * Modbus port number
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 65535
     * )
     */
    private $port;
    
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
                
        $this->ipAddress = "192.168.0.5";
        $this->port = 502;
        $this->registerCount = 1;
        $this->driverPolling = 50;
        $this->slaveID = 2;
    }
    
    /**
     * Get Modbus slave IP address
     * 
     * @return string Modbus slave IP address
     */
    public function getIpAddress() {
        
        return $this->ipAddress;
    }
    
    /**
     * Set Modbus slave IP address
     * 
     * @param string $val Modbus slave IP address
     */
    public function setIpAddress(string $val) {
        
        $this->ipAddress = $val;
    }
    
    /**
     * Get Modbus port number
     * 
     * @return int Modbus port number
     */
    public function getPort() {
        
        return $this->port;
    }
    
    /**
     * Set Modbus port number
     * 
     * @param int $val Modbus port number
     */
    public function setPort(int $val) {
        
        $this->port = $val;
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
}

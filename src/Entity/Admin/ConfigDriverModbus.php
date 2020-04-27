<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class for Modbus driver configuration
 * 
 * @author Mateusz Mirosławski
 */
class ConfigDriverModbus {
    
    /**
     * Modbus mode
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=3)
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
        
        // Common data
        $this->mode = "TCP";
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
     * Get Modbus mode (TCP/RTU)
     * 
     * @return string Modbus mode
     */
    public function getMode() {
        
        return $this->mode;
    }

    /**
     * Set Modbus mode
     * 
     * @param string $val Modbus mode
     */
    public function setMode(string $val) {
        
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
}

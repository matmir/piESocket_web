<?php

namespace App\Entity\Admin;

use App\Entity\Admin\DriverModbusMode;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class for Modbus driver configuration
 *
 * @author Mateusz Mirosławski
 */
class DriverModbus
{
    /**
     * Modbus driver identifier
     */
    private int $id;
    
    /**
     * Modbus mode
     */
    private int $mode;
    
    /**
     * Driver polling interval (ms)
     */
    private int $driverPolling;
    
    /**
     * Register count
     */
    private int $registerCount;
    
    /**
     * Modbus RTU baud rate
     */
    private int $RTU_baud;
    
    /**
     * Modbus RTU data bits
     */
    private int $RTU_dataBit;
    
    /**
     * Modbus RTU parity
     */
    private string $RTU_parity;
    
    /**
     * Modbus RTU port name
     */
    private string $RTU_port;
    
    /**
     * Modbus RTU stop bits
     */
    private int $RTU_stopBit;
    
    /**
     * Modbus slave ID
     */
    private int $slaveID;
    
    /**
     * Modbus TCP IP address
     */
    private string $TCP_addr;
    
    /**
     * Modbus TCP port number
     */
    private int $TCP_port;
    
    /**
     * Use slaveID in TCP mode
     */
    private bool $TCP_use_slaveID;
    
    /**
     * Default constructor
     *
     * @param int $id Configuration identifier
     * @param int $mode Modbus mode
     * @param int $regCnt Modbus register count
     * @param int $polling Modbus polling interval
     * @param int $slaveId Modbus slave identifier
     * @param string $TCPaddr TCP address
     * @param int $TCPport TCP port
     * @param bool $useSlaveId Flag for using slave id in TCP mode
     * @param string $RTUport RTU port name
     * @param int $RTUbaud RTU baud rate
     * @param string $RTUparity RTU parity
     * @param int $RTUdataBit RTU data bits
     * @param int $RTUstopBit RTU stop bits
     */
    public function __construct(
        int $id = 0,
        int $mode = DriverModbusMode::TCP,
        int $regCnt = 1,
        int $polling = 50,
        int $slaveId = 2,
        string $TCPaddr = '192.168.0.5',
        int $TCPport = 502,
        bool $useSlaveId = false,
        string $RTUport = '/dev/ttyACM1',
        int $RTUbaud = 57600,
        string $RTUparity = 'N',
        int $RTUdataBit = 8,
        int $RTUstopBit = 1
    ) {
        // Common data
        $this->id = $id;
        $this->mode = $mode;
        $this->registerCount = $regCnt;
        $this->driverPolling = $polling;
        $this->slaveID = $slaveId;
        
        // Modbus TCP
        $this->TCP_addr = $TCPaddr;
        $this->TCP_port = $TCPport;
        $this->TCP_use_slaveID = $useSlaveId;
        
        // Modbus RTU
        $this->RTU_port = $RTUport;
        $this->RTU_baud = $RTUbaud;
        $this->RTU_parity = $RTUparity;
        $this->RTU_dataBit = $RTUdataBit;
        $this->RTU_stopBit = $RTUstopBit;
    }
    
    /**
     * Get Modbus driver identifier
     *
     * @return int Modbus driver identifier
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Set Modbus driver identifier
     *
     * @param int $id Modbus driver identifier
     */
    public function setId(int $id)
    {
        $this->checkId($id);
        
        $this->id = $id;
    }
    
    /**
     * Check Modbus driver identifier
     *
     * @param int $id Modbus driver identifier
     * @return bool True if Modbus driver identifier is valid
     * @throws Exception if Modbus driver identifier is invalid
     */
    public static function checkId(int $id): bool
    {
        // Check values
        if ($id < 0) {
            throw new Exception('Modbus driver identifier wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Modbus mode (TCP/RTU)
     *
     * @return int Modbus mode
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * Set Modbus mode
     *
     * @param int $val Modbus mode
     */
    public function setMode(int $val)
    {
        DriverModbusMode::check($val);
        
        $this->mode = $val;
    }

    /**
     * Get driver polling interval
     *
     * @return int Driver polling interval
     */
    public function getDriverPolling(): int
    {
        return $this->driverPolling;
    }
    
    /**
     * Set driver polling interval
     *
     * @param int $val Driver polling interval
     */
    public function setDriverPolling(int $val)
    {
        $this->checkPolling($val);
        
        $this->driverPolling = $val;
    }
    
    /**
     * Check driver polling interval
     *
     * @param int $val Modbus driver polling interval
     * @return bool True if Modbus driver polling interval is valid
     * @throws Exception if Modbus driver polling interval is invalid
     */
    public static function checkPolling(int $val): bool
    {
        // Check values
        if ($val <= 0) {
            throw new Exception('Modbus driver polling interval wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Modbus register count
     *
     * @return int Modbus register count
     */
    public function getRegisterCount(): int
    {
        return $this->registerCount;
    }
    
    /**
     * Set Modbus register count
     *
     * @param int $val Modbus register count
     */
    public function setRegisterCount(int $val)
    {
        $this->checkRegisterCount($val);
        
        $this->registerCount = $val;
    }
    
    /**
     * Check Modbus register count
     *
     * @param int $val Modbus register count
     * @return bool True if Modbus register count is valid
     * @throws Exception if Modbus register count is invalid
     */
    public static function checkRegisterCount(int $val): bool
    {
        // Check values
        if ($val <= 0) {
            throw new Exception('Modbus register count wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Modbus RTU baud rate
     *
     * @return int Modbus RTU baud rate
     */
    public function getRTUbaud(): int
    {
        return $this->RTU_baud;
    }
    
    /**
     * Set Modbus RTU baud rate
     *
     * @param int $val Modbus RTU baud rate
     */
    public function setRTUbaud(int $val)
    {
        $this->checkRTUbaud($val);
        
        $this->RTU_baud = $val;
    }
    
    /**
     * Check Modbus RTU baud rate
     *
     * @param int $val Modbus RTU baud rate
     * @return bool True if Modbus RTU baud rate is valid
     * @throws Exception if Modbus RTU baud rate is invalid
     */
    public static function checkRTUbaud(int $val): bool
    {
        // Check values
        if ($val <= 0) {
            throw new Exception('Modbus baud rate wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Modbus RTU data bit
     *
     * @return int Modbus RTU data bit
     */
    public function getRTUdataBit(): int
    {
        return $this->RTU_dataBit;
    }
    
    /**
     * Set Modbus RTU data bit
     *
     * @param int $val Modbus RTU data bit
     */
    public function setRTUdataBit(int $val)
    {
        $this->checkRTUdataBit($val);
        
        $this->RTU_dataBit = $val;
    }
    
    /**
     * Check Modbus RTU data bit
     *
     * @param int $val Modbus RTU data bit
     * @return bool True if Modbus RTU data bit is valid
     * @throws Exception if Modbus RTU data bit is invalid
     */
    public static function checkRTUdataBit(int $val): bool
    {
        // Check values
        if ($val < 5 || $val > 8) {
            throw new Exception('Modbus data bit wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Modbus RTU parity
     *
     * @return string Modbus RTU parity
     */
    public function getRTUparity(): string
    {
        return $this->RTU_parity;
    }

    /**
     * Set Modbus RTU parity
     *
     * @param string $val Modbus RTU parity
     */
    public function setRTUparity(string $val)
    {
        $this->checkRTUparity($val);
        
        $this->RTU_parity = $val;
    }
    
    /**
     * Check Modbus RTU parity
     *
     * @param int $val Modbus RTU parity
     * @return bool True if Modbus RTU parity is valid
     * @throws Exception if Modbus RTU parity is invalid
     */
    public static function checkRTUparity(string $val): bool
    {
        if (strlen($val) != 1 || !($val == 'N' || $val == 'E' || $val == 'O')) {
            throw new Exception('Modbus RTU parity wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Modbus RTU port
     *
     * @return string Modbus RTU port
     */
    public function getRTUport(): string
    {
        return $this->RTU_port;
    }

    /**
     * Set Modbus RTU port
     *
     * @param string $val Modbus RTU port
     */
    public function setRTUport(string $val)
    {
        $this->checkRTUport($val);
        
        $this->RTU_port = $val;
    }
    
    /**
     * Check Modbus RTU port
     *
     * @param int $val Modbus RTU port
     * @return bool True if Modbus RTU port is valid
     * @throws Exception if Modbus RTU port is invalid
     */
    public static function checkRTUport(string $val): bool
    {
        // Check values
        if (trim($val) == false) {
            throw new Exception('Modbus RTU port can not be empty');
        }
        
        return true;
    }
    
    /**
     * Get Modbus RTU stop bit
     *
     * @return int Modbus RTU stop bit
     */
    public function getRTUstopBit(): int
    {
        return $this->RTU_stopBit;
    }
    
    /**
     * Set Modbus RTU stop bit
     *
     * @param int $val Modbus RTU stop bit
     */
    public function setRTUstopBit(int $val)
    {
        $this->checkRTUstopBit($val);
        
        $this->RTU_stopBit = $val;
    }
    
    /**
     * Check Modbus RTU stop bit
     *
     * @param int $val Modbus RTU stop bit
     * @return bool True if Modbus RTU stop bit is valid
     * @throws Exception if Modbus RTU stop bit is invalid
     */
    public static function checkRTUstopBit(int $val): bool
    {
        // Check values
        if ($val < 1 || $val > 2) {
            throw new Exception('Modbus stop bit wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Modbus slave identifier
     *
     * @return int Modbus slave ID
     */
    public function getSlaveID(): int
    {
        return $this->slaveID;
    }
    
    /**
     * Set Modbus slave identifier
     *
     * @param int $val Modbus slave ID
     */
    public function setSlaveID(int $val)
    {
        $this->checkSlaveID($val);
        
        $this->slaveID = $val;
    }
    
    /**
     * Check Modbus slave ID
     *
     * @param int $val Modbus slave ID
     * @return bool True if Modbus slave ID is valid
     * @throws Exception if Modbus slave ID is invalid
     */
    public static function checkSlaveID(int $val): bool
    {
        // Check values
        if ($val < 1 || $val > 247) {
            throw new Exception('Modbus slave ID wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Modbus slave IP address
     *
     * @return string Modbus slave IP address
     */
    public function getTCPaddr(): string
    {
        return $this->TCP_addr;
    }
    
    /**
     * Set Modbus slave IP address
     *
     * @param string $val Modbus slave IP address
     */
    public function setTCPaddr(string $val)
    {
        $this->checkTCPaddr($val);
        
        $this->TCP_addr = $val;
    }
    
    /**
     * Check Modbus slave IP address
     *
     * @param int $val Modbus slave IP address
     * @return bool True if Modbus slave IP address is valid
     * @throws Exception if Modbus slave IP address is invalid
     */
    public static function checkTCPaddr(string $val): bool
    {
        // Check values
        if (trim($val) == false) {
            throw new Exception('Modbus slave IP address can not be empty');
        }
        
        return true;
    }
    
    /**
     * Get Modbus port number
     *
     * @return int Modbus port number
     */
    public function getTCPport(): int
    {
        return $this->TCP_port;
    }
    
    /**
     * Set Modbus port number
     *
     * @param int $val Modbus port number
     */
    public function setTCPport(int $val)
    {
        $this->checkTCPport($val);
        
        $this->TCP_port = $val;
    }
    
    /**
     * Check Modbus TCP port
     *
     * @param int $val Modbus TCP port
     * @return bool True if Modbus TCP port bit is valid
     * @throws Exception if Modbus TCP port bit is invalid
     */
    public static function checkTCPport(int $val): bool
    {
        // Check values
        if ($val < 1) {
            throw new Exception('Modbus TCP port wrong value');
        }
        
        return true;
    }
    
    /**
     * Use slaveID in TCP mode?
     *
     * @return bool Use slaveID in TCP mode?
     */
    public function useSlaveIdInTCP(): bool
    {
        return $this->TCP_use_slaveID;
    }
    
    /**
     * Set slaveID usage in TCP mode
     *
     * @param bool $val usage flage
     */
    public function setSlaveIdUsageInTCP(bool $val)
    {
        $this->TCP_use_slaveID = $val;
    }
    
    /**
     * Get max byte address
     *
     * @return int Max byte address
     */
    public function getMaxByteAddress(): int
    {
        return $this->registerCount * 2;
    }
    
    /**
     * Check if Modbus object is valid
     *
     * @param bool $checkID Flag validating Modbus identifier
     * @return bool True if Modbus is valid
     * @throws Exception Throws when Modbus is invalid
     */
    public function isValid(bool $checkID = false): bool
    {
        // Check identifier
        if ($checkID) {
            $this->checkId($this->id);
        }
        
        // Check mode
        DriverModbusMode::check($this->mode);
        
        // Check polling
        $this->checkPolling($this->driverPolling);
        
        // Check register count
        $this->checkRegisterCount($this->registerCount);
        
        // Check RTU baud
        $this->checkRTUbaud($this->RTU_baud);
        
        // Check RTU data bit
        $this->checkRTUdataBit($this->RTU_dataBit);
        
        // Check RTU parity
        $this->checkRTUparity($this->RTU_parity);
        
        // Check RTU port
        $this->checkRTUport($this->RTU_port);
        
        // Check RTU stop bit
        $this->checkRTUstopBit($this->RTU_stopBit);
        
        // Check Slave ID
        $this->checkSlaveID($this->slaveID);
        
        // Check TCP address
        $this->checkTCPaddr($this->TCP_addr);
        
        // Check TCP port
        $this->checkTCPport($this->TCP_port);
        
        return true;
    }
}

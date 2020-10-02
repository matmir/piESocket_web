<?php

namespace App\Entity\Admin;

use App\Entity\Admin\DriverType;
use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverSHM;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class for driver connection configuration
 *
 * @author Mateusz Mirosławski
 */
class DriverConnection
{
    /**
     * Driver connection identifier
     */
    private int $id;
    
    /**
     * Driver connection name
     */
    private string $name;
    
    /**
     * Driver connection type
     */
    private int $type;
    
    /**
     * Modbus driver configuration instance
     */
    private ?DriverModbus $configModbus;
    
    /**
     * SHM driver configuration instance
     */
    private ?DriverShm $configShm;
    
    /**
     * Driver enable flag
     */
    private bool $enable;
    
    /**
     * Default constructor
     *
     * @param int $id Connection identifier
     * @param string $name Connection name
     * @param int $type Connection type
     * @param DriverModbus|null $mb DriverModbus object
     * @param DriverSHM|null $shm DriverSHM object
     */
    public function __construct(
        int $id = 0,
        string $name = 'conn1',
        int $type = DriverType::SHM,
        ?DriverModbus $mb = null,
        ?DriverSHM $shm = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->configModbus = $mb;
        $this->configShm = $shm;
        $this->enable = false;
    }
    
    /**
     * Get Driver connection identifier
     *
     * @return int Driver connection identifier
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Set Driver connection identifier
     *
     * @param int $id Driver connection identifier
     */
    public function setId(int $id)
    {
        $this->checkId($id);
        
        $this->id = $id;
    }
    
    /**
     * Check Driver connection identifier
     *
     * @param int $id Driver connection identifier
     * @return bool True if Driver connection identifier is valid
     * @throws Exception if Driver connection identifier is invalid
     */
    public static function checkId(int $id): bool
    {
        // Check values
        if ($id < 0) {
            throw new Exception('Driver connection identifier wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Driver connection name
     *
     * @return string Driver connection name
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Set Driver connection name
     *
     * @param string $val Driver connection name
     */
    public function setName(string $val)
    {
        $this->checkName($val);
        
        $this->name = $val;
    }
    
    /**
     * Check Driver connection name
     *
     * @param string $nm Driver connection name
     * @return bool True if Driver connection name is valid
     * @throws Exception if Driver connection name is invalid
     */
    public static function checkName(string $nm): bool
    {
        if (trim($nm) == false) {
            throw new Exception('Driver connection name can not be empty');
        }
        
        return true;
    }
    
    /**
     * Get Driver connection type
     *
     * @return int Driver connection type identifier
     */
    public function getType(): int
    {
        return $this->type;
    }
    
    /**
     * Set Driver connection type
     *
     * @param int $dtyp Driver connection type identifier
     */
    public function setType(int $dtyp)
    {
        DriverType::check($dtyp);
        
        $this->type = $dtyp;
    }
    
    /**
     * Get Modbus configuration
     *
     * @return DriverModbus object or null
     */
    public function getModbusConfig(): ?DriverModbus
    {
        return $this->configModbus;
    }
    
    /**
     * Check if Modbus configuration exist
     *
     * @return bool True if Modbus configuration exist
     */
    public function isModbusConfig(): bool
    {
        $ret = false;
        
        if ($this->configModbus instanceof DriverModbus) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Set Modbus configuration
     *
     * @param DriverModbus $mb Modbus configuration object
     */
    public function setModbusConfig(DriverModbus $mb)
    {
        $this->configModbus = $mb;
    }
    
    /**
     * Get SHM configuration
     *
     * @return DriverShm object or null
     */
    public function getShmConfig(): ?DriverShm
    {
        return $this->configShm;
    }
    
    /**
     * Check if SHM configuration exist
     *
     * @return bool True if SHM configuration exist
     */
    public function isShmConfig(): bool
    {
        $ret = false;
        
        if ($this->configShm instanceof DriverSHM) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Set SHM configuration
     *
     * @param DriverShm $shm SHM configuration object
     */
    public function setShmConfig(DriverShm $shm)
    {
        $this->configShm = $shm;
    }
    
    /**
     * Get Driver connection enable flag
     *
     * @return bool Driver connection enable flag
     */
    public function isEnabled(): bool
    {
        return $this->enable;
    }
    
    /**
     * Set Driver connection enable flag
     *
     * @param bool $val Driver connection enable flag
     */
    public function setEnable(bool $val)
    {
        $this->enable = $val;
    }
    
    /**
     * Check if Driver connection object is valid
     *
     * @param bool $checkID Flag validating driver connection identifier
     * @return bool True if Driver connection is valid
     * @throws Exception Throws when Driver connection is invalid
     */
    public function isValid(bool $checkID = false): bool
    {
        // Check identifier
        if ($checkID) {
            $this->checkId($this->id);
        }
        
        // Check Name
        $this->checkName($this->name);
        
        // Check Type
        DriverType::check($this->type);
        
        // Check driver instance
        if (!$this->isModbusConfig() && !$this->isShmConfig()) {
            throw new Exception('Missing driver configuration object');
        }
        
        // Check modbus
        if ($this->isModbusConfig()) {
            $this->configModbus->isValid($checkID);
        }
        
        // Check shm
        if ($this->isShmConfig()) {
            $this->configShm->isValid($checkID);
        }
        
        return true;
    }
}

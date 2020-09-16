<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class for SHM driver configuration
 *
 * @author Mateusz Mirosławski
 */
class DriverSHM
{
    /**
     * SHM driver identifier
     *
     * @Assert\PositiveOrZero
     */
    private $id;
    
    /**
     * SHM segment name
     */
    private $segmentName;
    
    /**
     * Max byte address
     */
    public const MAX_PROCESS_ADDRESS = 5000;
    
    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->id = 0;
        $this->segmentName = 'shm_segment';
    }
    
    /**
     * Get SHM driver identifier
     *
     * @return int SHM driver identifier
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Set SHM driver identifier
     *
     * @param int $id SHM driver identifier
     */
    public function setId(int $id)
    {
        $this->checkId($id);
        
        $this->id = $id;
    }
    
    /**
     * Check SHM driver identifier
     *
     * @param int $id SHM driver identifier
     * @return bool True if SHM driver identifier is valid
     * @throws Exception if SHM driver identifier is invalid
     */
    public static function checkId(int $id): bool
    {
        // Check values
        if ($id < 0) {
            throw new Exception("SHM driver identifier wrong value");
        }
        
        return true;
    }
    
    /**
     * Get SHM segment name
     *
     * @return string SHM segment name
     */
    public function getSegmentName()
    {
        return $this->segmentName;
    }
    
    /**
     * Set SHM segment name
     *
     * @param string $val SHM segment name
     */
    public function setSegmentName(string $val)
    {
        $this->checkSegmentName($val);
        
        $this->segmentName = $val;
    }
    
    /**
     * Check SHM segment name
     *
     * @param int $val SHM segment name
     * @return bool True if SHM segment name is valid
     * @throws Exception if SHM segment name is invalid
     */
    public static function checkSegmentName(string $val): bool
    {
        // Check values
        if (trim($val) == false) {
            throw new Exception("SHM segment name can not be empty");
        }
        
        return true;
    }
    
    /**
     * Check if SHM object is valid
     *
     * @param bool $checkID Flag validating SHM identifier
     * @return bool True if SHM is valid
     * @throws Exception Throws when SHM is invalid
     */
    public function isValid(bool $checkID = false): bool
    {
        // Check identifier
        if ($checkID) {
            $this->checkId($this->id);
        }
        
        // Check segment name
        $this->checkSegmentName($this->segmentName);
        
        return true;
    }
}

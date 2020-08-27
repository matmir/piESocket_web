<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class for Modbus driver configuration
 * 
 * @author Mateusz Mirosławski
 */
abstract class DriverConnectionEntity {
        
    /**
     * Driver connection identifier
     *
     * @Assert\PositiveOrZero
     */
    protected $connId;
    
    /**
     * Driver connection name
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     */
    protected $connName;
    
    /**
     * Default constructor
     */
    public function __construct() {
        
        $this->connId = 0;
        $this->connName = '';
    }
    
    /**
     * Get Connection driver identifier
     * 
     * @return int Connection driver identifier
     */
    public function getConnId(): int {
        
        return $this->connId;
    }
    
    /**
     * Set Connection driver identifier
     * 
     * @param int $id Connection driver identifier
     */
    public function setConnId(int $id) {
                
        $this->connId = $id;
    }
    
    /**
     * Get Driver connection name
     * 
     * @return string Driver connection name
     */
    public function getconnName() {
        
        return $this->connName;
    }
    
    /**
     * Set Driver connection name
     * 
     * @param string $val Driver connection name
     */
    public function setconnName(string $val) {
        
        $this->connName = $val;
    }
    
    /**
     * Initialize from Driver connection object
     * 
     * @param DriverConnection $conn Driver connection object
     */
    protected function initFromConnectionObject(DriverConnection $conn) {
        
        // Check if object is valid
        $conn->isValid(true);
        
        $this->connId = $conn->getId();
        $this->connName = $conn->getName();
    }
}
